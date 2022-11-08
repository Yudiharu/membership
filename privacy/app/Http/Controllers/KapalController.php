<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Kapal;
use App\Models\MasterLokasi;
use App\Models\tb_akhir_bulan;
use App\Models\Company;
use App\Models\Joborder;
use Carbon;

class KapalController extends Controller
{
    public function index()
    {
        $create_url = route('kapal.create');
        $lokasi= MasterLokasi::pluck('nama_lokasi','kode_lokasi');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        
        return view('admin.kapal.index',compact('create_url','period','lokasi', 'nama_lokasi','nama_company'));
        
    }


    public function anyData()
    {
        $level = auth()->user()->level;
        $lokasi = auth()->user()->kode_lokasi;
        if($lokasi == 'HO'){
            return Datatables::of(Kapal::with('masterlokasi'))->make(true);
        }
        else{
            return Datatables::of(Kapal::with('masterlokasi')->where('kode_lokasi',auth()->user()->kode_lokasi))->make(true);
        }
    }

    public function store(Request $request)
    {   
        $nama_kapal = $request->nama_kapal;
        $cek_nama = Kapal::where('nama_kapal',$nama_kapal)->first();       
        if ($cek_nama==null){
            $kapal = Kapal::create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah di Disimpan.',
            ];
            return response()->json($message);
        }
        else{
            $message = [
                    'success' => false,
                    'title' => 'Simpan',
                    'message' => 'Nama kapal Sudah Ada',
                    ];
            return response()->json($message);
        }
    }

    public function edit_kapal()
    {
        $kode_kapal = request()->id;
        $data = Kapal::find($kode_kapal);
        $output = array(
            'kode_kapal'=>$data->id,
            'nama_kapal'=>$data->nama_kapal,
            'type'=>$data->type,
        );
        return response()->json($output);
    }


    public function updateAjax(Request $request)
    {
        $kode_kapal = $request->kode_kapal;
        $kode_kapal = Joborder::where('kode_kapal',$kode_kapal)->first();
        if ($kode_kapal == null){
            Kapal::find($request->kode_kapal)->update($request->all());
           
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
                'message' => 'Data ['.$request->nama_kapal.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }

    public function hapus_kapal()
    {   
        $kode_kapal = request()->id;
        $kapal = Kapal::find(request()->id);
        $kode_kapal = Joborder::where('kode_kapal',$kode_kapal)->first();

        if ($kode_kapal == null){
            $kapal->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$kapal->nama_kapal.'] telah dihapus.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$kapal->nama_kapal.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
        
    }
}
