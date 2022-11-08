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
use App\Models\HistoryPremi;
use App\Models\Signature;
use App\Models\Company;
use App\Models\Customer;
use App\Exports\RekapPremiExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class LaporanpremiController extends Controller
{
    public function index()
    {
        $create_url = route('laporanpremi.create');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporanpremi.index',compact('create_url','period','nama_lokasi'));
    }

    public function exportPDF()
    {
        $bulan = $_GET['bulan'];
        $tahun = $_GET['tahun'];
        $report = $_GET['jenis_report'];
        $jenis = $_GET['jenis_rekap'];

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
            if ($jenis == 'BANK') {
                $jo = HistoryPremi::select('nama','no_rekening','nik','type', DB::raw('sum(premi) as total'))->whereMonth('tgl_insentif', $bulan)->whereYear('tgl_insentif', $tahun)->where('no_rekening', '<>', null)->groupBy('nama','no_rekening','nik','type')->orderBy('nama')->get();
            }else {
                $jo = HistoryPremi::select('nama','no_rekening','nik','type', DB::raw('sum(premi) as total'))->whereMonth('tgl_insentif', $bulan)->whereYear('tgl_insentif', $tahun)->where('no_rekening', null)->groupBy('nama','no_rekening','nik','type')->orderBy('nama')->get();
            }
            
            // $grandtotal1 = $jo->sum('biaya_estimasi');

            $pdf = PDF::loadView('/admin/laporanpremi/pdf', compact('jo','bulan','tahun','nama','nama2','dt','date','jenis','dt','ttd','format_ttd'));
            $pdf->setPaper('a4','portrait');

            return $pdf->stream('Laporan Rekap Premi Bulan '.$bulan.' tahun '.$tahun.'.pdf');
        }else if ($report == 'excel'){
                return Excel::download(new RekapPremiExport($bulan, $tahun, $report, $jenis), 'Laporan Rekap Premi Bulan '.$bulan.' Tahun '.$tahun.'.xlsx');
        }
    }
}
