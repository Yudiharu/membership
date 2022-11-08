<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Vendor;
use App\Models\Joborder;
use App\Models\JobrequestDetail;
use App\Models\JoborderDetail;
use App\Models\MasterLokasi;
use App\Models\tb_akhir_bulan;
use App\Models\Hasilbagi;
use App\Models\HasilbagiDetail;
use App\Models\Spb;
use App\Models\TruckingnonDetail;
use App\Models\Pemakaian;
use App\Models\PemakaianDetail;
use App\Models\Pemakaianban;
use App\Models\PemakaianbanDetail;
use App\Models\Signature;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Mobil;
use App\Exports\RekaphbuExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class LaporanlrMobilController extends Controller
{

    public function index()
    {
        $create_url = route('laporanlrmobil.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporanlrmobil.index',compact('create_url','period','nama_lokasi'));
    }

    public function exportPDF()
    {
        
        $tgl_awal = $_GET['tanggal_awal'];
        $tgl_akhir = $_GET['tanggal_akhir'];
        $report = $_GET['jenis_report'];

        if(isset($_GET['ttd'])){
            $format_ttd = $_GET['ttd']; 
        }else{
            $format_ttd = 0;
        }
        $ttd = auth()->user()->name;
        
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;
        $date = date("Y-m-d h-m-i");

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        if ($report=='PDF'){
            $mobil = Mobil::get();

            $pdf = PDF::loadView('/admin/laporanlrmobil/pdf', compact('mobil','tgl_awal','tgl_akhir','report','get_lokasi','get_company','date','nama','nama2','dt','ttd','format_ttd'));
            $pdf->setPaper('a4','landscape');

            return $pdf->stream('Laporan Laba Rugi Mobil periode '.$tgl_awal.' sd '.$tgl_akhir.'.pdf');
        }else if ($report == 'excel'){
            return Excel::download(new RekaphbuExport($bulan, $tahun, $report), 'Laporan Rekap HBU '.$bulan.' .xlsx');
        }
    }
}
