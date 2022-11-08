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
use App\Models\Pemilik;
use App\Exports\RekapbyrExport;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class LaporanPembayaranController extends Controller
{

    public function index()
    {
        $create_url = route('laporanpembayaran.create');
        $Pemilik = Pembayaran::join('u5611458_db_pusat.vendor','pembayaran_pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->pluck('nama_vendor','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporanpembayaran.index',compact('create_url','Joborder','period','nama_lokasi','Pemilik'));
    }

    public function exportPDF()
    {
        $jam_awal =' 00:00:00';
        $jam_akhir =' 23:59:59';
        $tanggal_awal = $_GET['tanggal_awal'];
        $tanggal_akhir = $_GET['tanggal_akhir'];
        $report = $_GET['jenis_report'];
        $stat = $_GET['status_bayar'];
        $kode_pemilik = $_GET['kode_pemilik'];
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

        if($kode_pemilik == 'SEMUA'){
            if ($report=='PDF'){
                if ($stat == 'SEMUA'){
                    $byr = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->get();

                    $leng = count($byr);
                }else {
                    $byr = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->where('status', $stat)->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->get();
                    
                    $leng = count($byr);
                }
                
                $pdf = PDF::loadView('/admin/laporanpembayaran/pdf', compact('tanggal_awal','tanggal_akhir','grandtotal1','grandtotal2','grandtotal3','grandtotal4','grandtotal5','grandtotal6','nama','nama2','dt','date','kode_pemilik','stat','byr','leng','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Laporan Rekap Pembayaran Periode '.$tanggal_awal.' s/d '.$tanggal_akhir.'.pdf');
            }else if ($report == 'excel'){
                return Excel::download(new RekapbyrExport($tanggal_awal, $tanggal_akhir, $report, $stat, $kode_pemilik), 'Laporan Rekap Pembayaran '.$tanggal_awal.' sd '.$tanggal_akhir.'.xlsx');
            }else{
                $fileName = 'Pembayaran.csv';
                if ($stat =='SEMUA') {
                    $tasks = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->get();
                }else {
                    $tasks = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->where('status', $stat)->get();
                }

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );

                $columns = array('no_pembayaran','tanggal_pembayaran','no_joborder','no_spb','tgl_spb','tgl_kembali','status');

                $callback = function() use($tasks, $columns) {
                    $file = fopen('php://output', 'w');
                    // fputcsv($file, $columns,chr(59));

                    foreach ($tasks as $task) {
                        $row['no_pembayaran']  = $task->no_pembayaran;
                        $row['tanggal_pembayaran'] = $task->tanggal_pembayaran;
                        $row['no_joborder'] = $task->no_joborder;
                        $row['no_spb'] = $task->no_spb;
                        $row['tgl_spb'] = $task->tgl_spb;
                        $row['tgl_kembali'] = $task->tgl_kembali;
                        $row['status'] = $task->status;
                        
                        fputcsv($file, array($row['no_pembayaran'], $row['tanggal_pembayaran'], $row['no_joborder'], $row['no_spb'], $row['tgl_spb'], $row['tgl_kembali'], $row['status'],chr(59)));
                    }

                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }
        }else{
            $get_pemilik = Pemilik::find($kode_pemilik);
            $nama_pemilik = $get_pemilik->nama_pemilik;

            if ($report=='PDF'){
                if ($stat == 'SEMUA'){
                    $byr = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->where('pembayaran_pemilik.kode_pemilik',$kode_pemilik)->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->get();

                    $leng = count($byr);
                }else {
                    $byr = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->where('pembayaran_pemilik.kode_pemilik',$kode_pemilik)->where('status', $stat)->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->get();

                    $leng = count($byr);
                }
                
                $pdf = PDF::loadView('/admin/laporanpembayaran/pdf', compact('tanggal_awal','tanggal_akhir','grandtotal1','grandtotal2','grandtotal3','grandtotal4','grandtotal5','grandtotal6','nama','nama2','dt','date','nama_pemilik','kode_pemilik','stat','byr','leng','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Laporan Rekap Pembayaran Periode '.$tanggal_awal.' s/d '.$tanggal_akhir.'.pdf');
            }else if ($report == 'excel'){
                return Excel::download(new RekapbyrExport($tanggal_awal, $tanggal_akhir, $report, $stat, $kode_pemilik), 'Laporan Rekap Pembayaran '.$tanggal_awal.' sd '.$tanggal_akhir.'.xlsx');
            }else{
                $fileName = 'Pembayaran.csv';
                if ($stat =='SEMUA') {
                    $tasks = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->where('pembayaran_pemilik.kode_pemilik',$kode_pemilik)->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->get();
                }else {
                    $tasks = PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->where('pembayaran_pemilik.kode_pemilik',$kode_pemilik)->whereBetween('tanggal_pembayaran',array($tanggal_awal,$tanggal_akhir))->where('status', $stat)->get();
                }

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );

                $columns = array('no_pembayaran','tanggal_pembayaran','no_joborder','no_spb','tgl_spb','tgl_kembali','status');

                $callback = function() use($tasks, $columns) {
                    $file = fopen('php://output', 'w');
                    // fputcsv($file, $columns,chr(59));

                    foreach ($tasks as $task) {
                        $row['no_pembayaran']  = $task->no_pembayaran;
                        $row['tanggal_pembayaran'] = $task->tanggal_pembayaran;
                        $row['no_joborder'] = $task->no_joborder;
                        $row['no_spb'] = $task->no_spb;
                        $row['tgl_spb'] = $task->tgl_spb;
                        $row['tgl_kembali'] = $task->tgl_kembali;
                        $row['status'] = $task->status;
                        
                        fputcsv($file, array($row['no_pembayaran'], $row['tanggal_pembayaran'], $row['no_joborder'], $row['no_spb'], $row['tgl_spb'], $row['tgl_kembali'], $row['status'],chr(59)));
                    }

                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            }
        }
        
    }
}
