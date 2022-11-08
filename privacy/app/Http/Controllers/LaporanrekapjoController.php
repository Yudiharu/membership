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
use App\Models\Signature;
use App\Models\Company;
use App\Models\Customer;
use App\Exports\RekapjoExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class LaporanrekapjoController extends Controller
{

    public function index()
    {
        $create_url = route('laporanrekapjo.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporanrekapjo.index',compact('create_url','period','nama_lokasi'));
    }

    public function exportPDF()
    {
        $jam_awal =' 00:00:00';
        $jam_akhir =' 23:59:59';
        $tanggal_awal = $_GET['tanggal_awal'];
        $tanggal_akhir = $_GET['tanggal_akhir'];
        $report = $_GET['jenis_report'];
        $jenis = $_GET['jenis_jo'];
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

        if($jenis=='SEMUA'){
            if ($report=='PDF'){
                $jo = Joborder::with('customer1')->whereBetween('tgl_joborder', array($tanggal_awal, $tanggal_akhir))->get();
                $pdf = PDF::loadView('/admin/laporanrekapjo/pdf', compact('jo','tanggal_awal','tanggal_akhir','nama','nama2','dt','date','jenis','dt','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Laporan Rekap JO Periode '.$tanggal_awal.' s/d '.$tanggal_akhir.'.pdf');
            }else if ($report == 'excel'){
                return Excel::download(new RekapjoExport($tanggal_awal, $tanggal_akhir, $report, $jenis), 'Laporan Rekap JO '.$tanggal_awal.' sd '.$tanggal_akhir.'.xlsx');
            }
        } else{
            if ($report=='PDF'){
                $jo = Joborder::with('customer1')->where('type_kegiatan',$jenis)->whereBetween('tgl_joborder', array($tanggal_awal, $tanggal_akhir))->get();

                $pdf = PDF::loadView('/admin/laporanrekapjo/pdf', compact('jo','tanggal_awal','tanggal_akhir','nama','nama2','dt','date','jenis','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Laporan Rekap JO Periode '.$tanggal_awal.' s/d '.$tanggal_akhir.'.pdf');
            }else if ($report == 'excel'){
                return Excel::download(new RekapjoExport($tanggal_awal, $tanggal_akhir, $report, $jenis), 'Laporan Rekap JO '.$tanggal_awal.' sd '.$tanggal_akhir.'.xlsx');
            }
        }
    }
}
