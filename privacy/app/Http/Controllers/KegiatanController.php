<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Customer;
use App\Models\Coa;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Joborder;
use App\Models\Kegiatan;
use App\Models\TarifKegiatan;
use App\Models\JenisHarga;
use Carbon;
use DB;

class KegiatanController extends Controller
{
    public function index()
    {
        $create_url = route('kegiatan.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $Coa = Coa::select('coa.kode_coa', DB::raw("concat(coa.account,' - ',coa.ac_description) as coas"))->join('u5611458_gui_general_ledger_laravel.coa_detail','coa.kode_coa','=','u5611458_gui_general_ledger_laravel.coa_detail.kode_coa')->where('u5611458_gui_general_ledger_laravel.coa_detail.kode_company', auth()->user()->kode_company)->pluck('coas','coa.kode_coa');
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.kegiatan.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer','Coa'));
    }

    public function anyData()
    {
        return Datatables::of(Kegiatan::with('coa'))->make(true);
    }

    public function store(Request $request)
    {
        $ceknama = Kegiatan::where('description',$request->description)->first();
        if($ceknama == null){
            Kegiatan::create($request->all());
            
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

    public function detail($kode)
    {
        $kegiatan = Kegiatan::where('id',$kode)->first();
                    
        $tarifkegiatan = TarifKegiatan::where('id_kegiatan', $kegiatan->id)->orderBy('created_at','desc')->get();

        $jenis = JenisHarga::pluck('description','id');

        $list_url= route('kegiatan.index');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.tarifkegiatan.index', compact('kegiatan','tarifkegiatan','list_url','Mobil','period','nama_lokasi','nama_company','vendor','jenis'));
    }


    public function edit_kegiatan()
    {
        $kode = request()->id;
        $data = Kegiatan::find($kode);
        $output = array(
            'id'=>$data->id,
            'description'=>$data->description,
            'kode_coa'=>$data->kode_coa,
            'container'=>$data->container,
            'lainnya'=>$data->lainnya,
            'cfs'=>$data->cfs,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $id = $request->id;
        $cek_transaksi = Kegiatan::where('id',$id)->update($request->except('_token'));
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah diupdate.'
        ];
        return response()->json($message);
    }

    public function hapus_kegiatan()
    {   
        $kegiatan = Kegiatan::find(request()->id);
        $kegiatan->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$kegiatan->description.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
