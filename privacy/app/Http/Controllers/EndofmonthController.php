<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\tb_akhir_bulan;
use App\Models\Reopen;
use App\Models\Endofmonth;
use App\Models\tb_item_bulanan;
use App\Models\MasterLokasi;
use PDF;
use Excel;
use DB;
use Carbon;

class EndofmonthController extends Controller
{
    public function index()
    {
        $create_url = route('endofmonth.create');
        $tanggal = tb_akhir_bulan::where('status_periode','Open')->pluck('periode','periode');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $level = auth()->user()->level;
        if($level == 'superadministrator' || $level == 'user_rince' || $level == 'user_tina'){
            return view('admin.endofmonth.index',compact('create_url','tanggal','period', 'nama_lokasi'));
        }
        else{
            return view('admin.endofmonth.blank',compact('create_url','tanggal','period', 'nama_lokasi'));
        }


    }

    public function change(Request $request)
    {   
         $tanggal_tutup = $request->tanggal_awal;
         $tanggal_buka = $request->tanggal_akhir;

         $tahun_tutup = Carbon\Carbon::createFromFormat('Y-m-d',$tanggal_tutup)->year;
         $bulan_tutup = Carbon\Carbon::createFromFormat('Y-m-d',$tanggal_tutup)->month;
         // dd($tanggal_buka);

         $tahun_buka = Carbon\Carbon::createFromFormat('Y-m-d',$tanggal_buka)->year;
         $bulan_buka = Carbon\Carbon::createFromFormat('Y-m-d',$tanggal_buka)->month;
         $hari_buka = '01';
         // dd($hari_buka);

         $tabel_tutup = tb_akhir_bulan::whereMonth('periode', $bulan_tutup)->whereYear('periode', $tahun_tutup)->first();
         $tabel_buka = tb_akhir_bulan::whereMonth('periode', $bulan_buka)->whereYear('periode', $tahun_buka)->first();
         // dd($tabel_tutup);
         
         $tabel_baru = array();

         $status_baru = 'Open';
         $reopen_baru = 'false';

                $tabel_baru = [ 
                      'periode' => $tanggal_buka,
                      'status_periode' => $status_baru,
                      'reopen_status' => $reopen_baru,
                    ];

                $periode_baru = tb_akhir_bulan::create($tabel_baru);

                $status = $tabel_tutup->status_periode;
                $re_status = $tabel_tutup->reopen_status;

                if($status == 'Open'){
                    $tabel_tutup->status_periode = "Closed";
                    $tabel_tutup->save(); 

                    $message = [
                    'success' => true,
                    'title' => 'Simpan',
                    'message' => 'End Of Month: '.$tanggal_tutup, 'Berhasil!',
                    ];
                    return response()->json($message);
                }
                else{
                  $message = [
                  'success' => false,
                  'title' => 'Simpan',
                  'message' => 'Gagal End Of Month'.$tanggal_buka, 'Error!',
                  ];
                  return response()->json($message);
                }
          
    }
}
