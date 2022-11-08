<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Company;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\User;
use Carbon;
use DB;

class CompanyController extends Controller
{
    public function index()
    {
        
        $create_url = route('company.create');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;
        
        $company = Company::select('kode_company', DB::raw("concat(kode_company,' - ',nama_company) as compan"))->whereRaw('LENGTH(kode_company) = 2')->pluck('compan','kode_company');

        return view('admin.company.index',compact('create_url','period', 'nama_lokasi','company','nama_company'));

    }

    public function anyData()
    {
        return Datatables::of(Company::orderBy('kode_company'))->addColumn('action', function ($query){
                return '<a href="javascript:;" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs data-toggle="tooltip" title="Hapus"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
                           })
            ->make(true);

    }

    public function store(Request $request)
    {   
        $level = auth()->user()->level;
        if($level == 'superadministrator'){
            $kode_company = $request->kode_company;
            $nama_company = $request->nama_company;

            $cek_nama = Company::where('nama_company',$nama_company)->first();       
            if ($cek_nama==null){

                if ($request->tipe == "Cabang"){
                            
                    $comp = $request->kode_comp;
                    $cek_comp = Company::where('kode_company', 'like', $comp.'%')->orderBy('kode_company','desc')->first();
                    $data = Company::create($request->all());
                    if (strlen($cek_comp->kode_company) == 4){
                        $kode = substr($cek_comp->kode_company,3);
                        $kode += 1;
                        $no = $request->all();
                        if (strlen($kode) == 2){
                            $kode2 = substr($cek_comp->kode_company,0,2);
                            $no['kode_company'] = $kode2.$kode;
                            $data->update($no);
                        }else {
                            $kode2 = substr($cek_comp->kode_company,0,3);
                            $no['kode_company'] = $kode2.$kode;
                            $data->update($no);
                        }
                    }else {
                        $kode = $comp."01";
                        //$no = Company::where('nama_company',$request->nama_company)->first();
                        $no = $request->all();
                        $no['kode_company'] = $kode;
                        $data->update($no);
                    }
                }else {
                    Company::create($request->all());
                }
                    
                $message = [
                    'success' => true,
                    'title' => 'Simpan',
                    'message' => 'Data telah Disimpan.'
                ];
                return response()->json($message);
            }
            else{

                $message = [
                    'success' => false,
                    'title' => 'Simpan',
                    'message' => 'Gagal! Nama Company Sudah Ada.'
                ];
                return response()->json($message);
            }
        }
        else{
            $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'Anda tidak mempunyai akses tambah data',
                        ];
                return response()->json($message);
        }
        
        
    }

    public function edit_company()
    {
        $kode_company = request()->id;
        $data = Company::find($kode_company);
        $output = array(
            'kode_company'=>$data->kode_company,
            'nama_company'=>$data->nama_company,
            'alamat'=>$data->alamat,
            'telp'=>$data->telp,
            'npwp'=>$data->npwp,
            'status'=>$data->status,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $level = auth()->user()->level;
        if($level == 'superadministrator'){
            $nama_company = $request->nama_company;

            $cek_nama = Company::where('nama_company',$nama_company)->first();       
            if ($cek_nama==null){

                $request->validate([
                    'kode_company'=>'required',
                    'nama_company'=> 'required',
                    'alamat'=> 'required',
                    'telp'=> 'required',
                    'npwp'=> 'required',
                    'status'=> 'required',
                ]);

              Company::find($request->kode_company)->update($request->all());
           
              $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah di Update.'
                ];
                return response()->json($message);
            }
            else{
                $message = [
                    'success' => false,
                    'title' => 'Simpan',
                    'message' => 'Gagal! Nama Company Sudah Ada.'
                ];
                return response()->json($message);
            }
            
        }else{
            $message = [
            'success' => false,
            'title' => 'Update',
            'message' => 'Anda tidak mempunyai akses edit data'
            ];
            return response()->json($message);
        }
      
    }

    public function hapus_company()
    {   
        $kode_company = request()->id;
        $company = Company::find(request()->id);
        $cek_user = User::where('kode_company',$kode_company)->first();

        if ($cek_user == null){
            $company->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$company->nama_company.'] telah dihapus.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$company->nama_company.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }
}
