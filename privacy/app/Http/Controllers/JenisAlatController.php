<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\JenisAlat;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Coa;
use App\Models\Systemsetup;
use Carbon;
use DB;

class JenisAlatController extends Controller
{
    public function index()
    {
        $create_url = route('jenisalat.create');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.jenisalat.index',compact('create_url','Coa','period', 'nama_lokasi','nama_company'));
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(JenisAlat::query())->make(true);
    }

    public function store(Request $request)
    {
        $nama_sopir = $request->kode_jenis;
        $cek_sopir = JenisAlat::where('kode_jenis',$nama_sopir)->first();
        if($cek_sopir == null){
            JenisAlat::create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Nama Sopir Sudah Ada',
            ];
            return response()->json($message);
        }
    }

    public function edit_jenis()
    {
        $kode_sopir = request()->id;
        $data = JenisAlat::find($kode_sopir);
        $output = array(
            'id'=>$data->id,
            'kode_jenis'=>$data->kode_jenis,
            'description'=>$data->description,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_sopir = $request->id;
        JenisAlat::find($request->id)->update($request->all());
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_jenis()
    {   
        $kode_sopir = request()->id;
        $sopir = JenisAlat::find(request()->id);
        $sopir->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$sopir->kode_jenis.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
