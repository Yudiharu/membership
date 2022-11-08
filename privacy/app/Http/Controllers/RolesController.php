<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;
use App\Role;
use Illuminate\Http\Request;
use App\Permission;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use Carbon;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RolesDataTable $dataTable)
    {
        $create_url = route('roles.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return $dataTable->render('admin.roles.index',compact('create_url','period', 'nama_lokasi','nama_company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $info['list_url'] = route('roles.index');
        $info['title'] = 'Create new role';
        $permissions = Permission::all();

//        dd($permissions->groupBy('tab'));
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.roles.create', compact('info','permissions','period', 'nama_lokasi','nama_company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
           'name' => 'required',
           'display_name' => 'required'
        ]);

        $role = Role::create($request->all());
        if ($request->has('permission')){
            $role->syncPermissions($request->permission);
        }


        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $info['list_url'] = route('roles.index');
        $info['title'] = 'Edit role : '. $role->name;
        $permissions = Permission::all();
        $get_permission = $role->permissions()->pluck('name','name');

//        dd([array_has($get_permission->toArray(), 'create-users'), $get_permission->toArray()]);
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.roles.edit', compact('role','info', 'permissions', 'get_permission','period', 'nama_lokasi','nama_company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {

        try {
            $role->update($request->all());

            if ($request->has('permission')){
                $role->syncPermissions($request->permission);
            }else{
                $role->permissions()->detach();
            }



            $message = [
                'success' => true,
                'title' => 'Hapus',
                'message' => 'Role ['.$role->name.'] berhasil diupdate.'
            ];
            return response()->json($message);

        }catch (\Exception $exception){
            $message = [
                'success' => false,
                'title' => 'Hapus',
                'message' => 'Role gagal diupdate.'. $exception
            ];

            \Log::error($exception);
            return response()->json($message,403);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Role ['.$role->name.'] berhasil dihapus.'
            ];
            return response()->json($message);

        }catch (\Exception $exception){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Role gagal dihapus.'
            ];
            return response()->json($message);
        }
    }
}
