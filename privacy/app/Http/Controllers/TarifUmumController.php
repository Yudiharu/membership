<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Customer;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Joborder;
use App\Models\Kegiatan;
use App\Models\JenisHarga;
use App\Models\TarifUmum;
use App\Models\HistoryTarifUmum;
use Carbon;
use DB;

class TarifUmumController extends Controller
{
    public function index()
    {
        $create_url = route('tarifumum.create');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        $date = Carbon\Carbon::now();

        $Tarif = TarifUmum::where('tgl_berlaku', '<=', $date)->orderBy('tgl_berlaku','desc')->first();

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.tarifumum.index',compact('create_url','period','nama_lokasi','nama_company','Kegiatan','Harga','Tarif'));
    }

    public function getDatabyID(){
        return Datatables::of(HistoryTarifUmum::orderBy('created_at','desc'))->make(true);
    }

    public function getDatabyID2(){
        return Datatables::of(TarifUmum::orderBy('created_at','desc'))->make(true);
    }

    public function store_tarif(Request $request)
    {
        $cektgl = TarifUmum::where('tgl_berlaku', $request->tgl_berlaku)->first();
        if ($cektgl != null) {
            $cektgl->update($request->all());
        }else {
            TarifUmum::create($request->all());
        }
        HistoryTarifUmum::create($request->all());
    }

    public function hapus_tarif()
    {
        $tabelalat = TarifUmum::where('id', request()->id)->delete();
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }
}
