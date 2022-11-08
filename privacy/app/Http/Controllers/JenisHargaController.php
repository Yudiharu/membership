<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\JenisHarga;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Coa;
use App\Models\Systemsetup;
use Carbon;
use DB;

class JenisHargaController extends Controller
{
    public function index()
    {
        $create_url = route('jenisharga.create');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.jenisharga.index',compact('create_url','Coa','period', 'nama_lokasi','nama_company'));
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(JenisHarga::query())->make(true);
    }

    public function store(Request $request)
    {
        $nama_sopir = $request->description;
        $cek_sopir = JenisHarga::where('description',$nama_sopir)->first();
        if($cek_sopir == null){
            JenisHarga::create($request->all());
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
                'message' => 'Nama Sudah Ada',
            ];
            return response()->json($message);
        }
    }

    public function edit_jenis()
    {
        $kode_sopir = request()->id;
        $data = JenisHarga::find($kode_sopir);
        $output = array(
            'id'=>$data->id,
            'description'=>$data->description,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_sopir = $request->id;
        JenisHarga::find($request->id)->update($request->all());
       
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
        $sopir = JenisHarga::find(request()->id);
        $sopir->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$sopir->description.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
