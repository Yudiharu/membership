<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\tb_akhir_bulan;
use App\Models\Reopen;
use App\Models\MasterLokasi;
use App\Models\Company;
use PDF;
use Excel;
use DB;
use Carbon;

class ReopenController extends Controller
{
    public function index()
    {
        $create_url = route('reopen.create');
        $tanggal = tb_akhir_bulan::where('status_periode','Closed')->pluck('periode','periode');

        $info_open = tb_akhir_bulan::where('reopen_status','true')->first();
        if ($info_open == null){
            $info_bulan = null;
            $info_tahun = null;
            $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
            $tgl_jalan2 = $tgl_jalan->periode;
            $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
            $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
            $nama_lokasi = $get_lokasi->nama_lokasi;

            $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
            $nama_company = $get_company->nama_company;

            $level = auth()->user()->level;
            if($level == 'superadministrator' || $level == 'user_rince' || $level == 'user_tina'){
                return view('admin.reopen.index',compact('create_url','tanggal','tgl_edit','tahun_open','period','info_bulan','info_tahun','info_open2', 'nama_lokasi','nama_company'));
            }
            else{
                return view('admin.reopen.blank',compact('create_url','tanggal','tgl_edit','tahun_open','period','info_bulan','info_tahun','info_open2', 'nama_lokasi','nama_company'));
            }
        }else{
            $info_open2 = $info_open->periode;
            $info_bulan = Carbon\Carbon::parse($info_open2)->format('n');
            $info_tahun = Carbon\Carbon::parse($info_open2)->format('Y');

            $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
            $tgl_jalan2 = $tgl_jalan->periode;
            $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
            $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
            $nama_lokasi = $get_lokasi->nama_lokasi;

            $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
            $nama_company = $get_company->nama_company;

            $level = auth()->user()->level;
            if($level == 'superadministrator' || $level == 'user_rince' || $level == 'user_tina'){
                return view('admin.reopen.index',compact('create_url','tanggal','tgl_edit','tahun_open','period','info_bulan','info_tahun','info_open2', 'nama_lokasi','nama_company'));
            }
            else{
                return view('admin.reopen.blank',compact('create_url','tanggal','tgl_edit','tahun_open','period','info_bulan','info_tahun','info_open2', 'nama_lokasi','nama_company'));
            }
        }
    }

    public function change(Request $request)
    {  
         $cek_reopen = tb_akhir_bulan::where('reopen_status','true')->first();

         if($cek_reopen == null){
             $tanggal_buka = '01';
             $bulan_buka = $request->month;
             $tahun_buka = $request->year;
             // dd($tanggal_buka);

             $tanggal_baru = Carbon\Carbon::createFromDate($tahun_buka, $bulan_buka, $tanggal_buka)->toDateString();
             // dd($tanggal_baru);

             $tanggal_re = tb_akhir_bulan::where('periode',$tanggal_baru)->first();
             // dd($tanggal_re);

             $stat = $tanggal_re->status_periode;

             if($stat == 'Open' || $stat == 'Disable'){
                alert()->success('Periode: '.$tanggal_baru, 'Status Telah Open!')->persistent('Close');
                return redirect()->back();
             }

             if($tanggal_re != null){
                $cek_periode = tb_akhir_bulan::where('status_periode','Open')->first();
                //$cek_periode2 = tb_akhir_bulan::where('status_periode','Open')->first();

                $status = $tanggal_re->reopen_status;

                if($status == 'false'){
                    $tanggal_re->reopen_status = "true";
                    $cek_periode->status_periode = "Disable";
                    
                    $tanggal_re->save(); 
                    $cek_periode->save(); 

                    alert()->success('Re-Open Periode: '.$tanggal_baru, 'Berhasil!')->persistent('Close');
                    return redirect()->back();
                }

                else if($status == 'true'){
                    alert()->success('Periode: '.$tanggal_baru, 'Sudah Dibuka!')->persistent('Close');
                    return redirect()->back();
                }
             }
             else{
                alert()->success('Tidak Ada Periode: '.$tanggal_baru, 'Gagal!')->persistent('Close');
                return redirect()->back();
             }
         }
         else{
            $periode_reopen = $cek_reopen->periode;
            alert()->success('Silahkan Re-Open Close: '.$periode_reopen, 'Gagal!')->persistent('Close');
            return redirect()->back();
         }
         
    }

    public function change2(Request $request)
    {  
         $cek_reopen = tb_akhir_bulan::where('reopen_status','true')->first();

         if($cek_reopen != null){
             $tanggal_tutup = '01';
             $bulan_tutup = $request->month2;
             $tahun_tutup = $request->year2;

             $tanggal_baru = Carbon\Carbon::createFromDate($tahun_tutup, $bulan_tutup, $tanggal_tutup)->toDateString();
             // dd($tanggal_baru);

             $tanggal_re = tb_akhir_bulan::where('periode',$tanggal_baru)->first();
             // dd($tanggal_re);

             if($tanggal_re != null){
                $cek_periode = tb_akhir_bulan::where('status_periode','Disable')->first();

                $status = $tanggal_re->reopen_status;

                if($status == 'true'){
                    $tanggal_re->reopen_status = "false";
                    $cek_periode->status_periode = "Open";
                    
                    $tanggal_re->save(); 
                    $cek_periode->save(); 

                    alert()->success('Re-Open Close Periode: '.$tanggal_baru, 'Berhasil!')->persistent('Close');
                    return redirect()->back();
                }

                else if($status == 'false'){
                    alert()->success('Periode: '.$tanggal_baru, 'Sudah Ditutup!')->persistent('Close');
                    return redirect()->back();
                }
             }
             else{
                alert()->success('Tidak Ada Periode: '.$tanggal_baru, 'Gagal!')->persistent('Close');
                return redirect()->back();
             }
         }

         else{
            alert()->success('Tidak Ada Periode yang Re-Open', 'Gagal!')->persistent('Close');
            return redirect()->back();
         }
         
    }
}
