<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\JenisMobil;
use App\Models\Pemakaian;
use App\Models\Mobil;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use Carbon;

class JenismobilController extends Controller
{
    public function index()
    {
        $create_url = route('jenismobil.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.jenismobil.index',compact('create_url','period', 'nama_lokasi','nama_company'));
        
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(JenisMobil::query())
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Hapus"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
                           })
            ->make(true);
    }

    public function store(Request $request)
    {
        $nama_jenis_mobil = $request->nama_jenis_mobil;
        $cek_jenis = JenisMobil::where('nama_jenis_mobil',$nama_jenis_mobil)->first();
        if ($cek_jenis == null ){
            $validator = $request->validate([
                'nama_jenis_mobil'=> 'required',
            ]);

            JenisMobil::create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah di Disimpan.'
            ];
            return response()->json($message);
            
        }else {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Jenis Mobil Sudah Ada',
            ];
            return response()->json($message);
        }  
    }

    public function edit_jenismobil()
    {
        $kode_jenis_mobil = request()->id;
        $data = JenisMobil::find($kode_jenis_mobil);
        $output = array(
            'id'=>$data->id,
            'nama_jenis_mobil'=>$data->nama_jenis_mobil,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_jenis_mobil = $request->kode_jenis_mobil;
        $cek_jenis = Mobil::where('kode_jenis_mobil',$kode_jenis_mobil)->first();
        $cek_nama = JenisMobil::where('nama_jenis_mobil',$request->nama_jenis_mobil)->first();
        if ($cek_jenis == null && $cek_nama == null){
            $request->validate([
                'kode_jenis_mobil'=>'required',
                'nama_jenis_mobil'=> 'required',
            ]);

            JenisMobil::find($request->kode_jenis_mobil)->update($request->all());
           
            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$request->kode_jenis_mobil.'] dipakai di master mobil / Jenis mobil ['.$request->nama_jenis_mobil.'] sudah ada.'
            ];
            return response()->json($message);
        }
      
    }

    public function hapus_jenismobil()
    {   
        $jenismobil = JenisMobil::find(request()->id);
        $kode_jenis_mobil = $jenismobil->kode_jenis_mobil;
        $cek_jenis = Mobil::where('kode_jenis_mobil',$kode_jenis_mobil)->first();

        if ($cek_jenis == null){
            $jenismobil->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$jenismobil->nama_jenis_mobil.'] telah dihapus.'
            ];
            return response()->json($message);
        } else {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$jenismobil->nama_jenis_mobil.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
        
    }
}
