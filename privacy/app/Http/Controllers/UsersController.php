<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\Hash;
use App\Permission;
use App\Role;
use App\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use Carbon;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        $testing = User::with('roles')->take(2)->get();
//        dd($testing->toArray());
        $create_url = route('users.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        if($level != 'superadministrator'){
            return $dataTable->render('admin.users.index2',compact('create_url','period', 'nama_lokasi','nama_company'));
        }
        else{
            return $dataTable->render('admin.users.index',compact('create_url','period', 'nama_lokasi','nama_company'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $list_url = route('users.index');
        $Company= Company::pluck('nama_company','kode_company');

        $Lokasi = MasterLokasi::pluck('nama_lokasi','kode_lokasi');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.users.create', compact('list_url','roles','Company','period', 'nama_lokasi','Lokasi','nama_company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = $request->roles;
        $get_role = Role::where('id',$role)->first(); 
        $name = $get_role->name;
        $lokasi = $request->kode_lokasi;
        $username = $request->username;

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create($request->all());
        $user->level = $name;
        $user->kode_lokasi = $lokasi;
        $user->username = $username;
        $user->save();

        if ($request->has('roles')){
            $user->syncRoles($request->roles);
        }

        $req = $request->all();
        $req['password'] = bcrypt($request->password);
        $user->update($req);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $list_url = route('users.index');
        $roles = Role::pluck('display_name','id');
        $Company= Company::pluck('nama_company','kode_company');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        $Lokasi = MasterLokasi::pluck('nama_lokasi','kode_lokasi');

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;
        
        $level = auth()->user()->level;
        if($level == 'superadministrator'){   
            return view('admin.users.edit',compact('user','list_url','roles','Company','period', 'nama_lokasi','Lokasi','nama_company'));
        }else{
            return view('admin.users.edit2',compact('user','list_url','roles','Company','period', 'nama_lokasi','Lokasi','nama_company'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $level = auth()->user()->level;
        $username = $request->username;
        
        if($level == 'superadministrator'){   
            $role = $request->roles;
            $get_role = Role::where('id',$role)->first(); 
            $name = $get_role->name;
            $lokasi = $request->kode_lokasi;
            $username = $request->username;

            if ($request->has('password') && $request->password != null){

                $req = $request->all();
                $req['password'] = Hash::make($request->password);
                $user->update($req);

                $user->level = $name;
                $user->kode_lokasi = $lokasi;
                $user->username = $username;
                $user->update();
            }else {
                $user->update($request->except('password'));

                $user->level = $name;
                $user->kode_lokasi = $lokasi;
                $user->username = $username;
                $user->update();
            }

            if ($request->has('roles')){
                $user->syncRoles($request->roles);
            }

            return redirect()->route('users.index');
        }else{
            if ($request->has('password') && $request->password != null){

                $req = $request->all();
                $req['password'] = Hash::make($request->password);
                $user->update($req);

                $user->username = $username;
                $user->update();
            }else {
                $user->update($request->except('password'));

                $user->username = $username;
                $user->update();
            }

            return redirect()->route('users.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            $message = [
                'success' => true,
                'title' => 'Hapus',
                'message' => 'User ['.$user->name.'] berhasil dihapus.'
            ];
            return response()->json($message);

        }catch (\Exception $exception){
            $message = [
                'success' => false,
                'title' => 'Hapus',
                'message' => 'User gagal dihapus.'
            ];
            return response()->json($message);
        }
    }
}
