<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\JobrequestDetail;
use App\Models\JoborderDetail;
use App\Models\Joborder;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use App\Models\Sizecontainer;
use App\Models\PemakaianAlatDetail;
use App\Models\PemakaianAlat;
use App\Models\TanggalSetup;
use DB;
use Carbon;
use DateTime;

class PemakaianAlatDetailController extends Controller
{
    public function index()
    {
        $create_url = route('pemakaianalatdetail.create');

        return view('admin.pemakaianalatdetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(PemakaianAlatDetail::with('alat','operator','helper1','helper2')->where('no_pemakaian',request()->id)->orderBy('no_timesheet','asc'))
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" data-toggle="tooltip" title="Edit" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" data-toggle="tooltip" title="Hapus" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
           })->make(true);
    }
    
    public function cekts()
    {
        $no_pemakaian = request()->no_pemakaian;
        $awalan = PemakaianAlatDetail::where('no_pemakaian', $no_pemakaian)->orderBy('no_timesheet','asc')->first();
        $detail = PemakaianAlatDetail::where('no_pemakaian', $no_pemakaian)->orderBy('no_timesheet','asc')->get();
        $timesheet = $awalan->no_timesheet;
        foreach ($detail as $row) {
            if ($timesheet == $row->no_timesheet) {
                $tanda = PemakaianAlatDetail::where('no_timesheet', $row->no_timesheet)->first();
                $tanda->marks = '';
                $tanda->save();
                $timesheet += 1;
            }else {
                $timesheet = $row->no_timesheet + 1;
                $tanda = PemakaianAlatDetail::where('no_timesheet', $row->no_timesheet)->first();
                $tanda->marks = 'MARKED';
                $tanda->save();
            }
        }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Pemeriksaan selesai.'
        ];
        return response()->json($message);
    }

    public function store(Request $request)
    {
        $head = PemakaianAlat::find($request->no_pemakaian);
        $datetime1 = new DateTime($head->tgl_pemakaian);
        $datetime2 = new DateTime($request->tgl_pakai);

        $diff = $datetime1->diff($datetime2);
        $hari = $diff->d;
        
        $limit = TanggalSetup::find(1);
        if ($limit->hari != 0) {
            if ($hari > $limit->hari) {
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Tanggal Pakai tidak boleh selisih lebih dari '.$limit->hari.' hari dari tanggal transaksi.'
                ];
                return response()->json($message);
            }
        }
        
        if ($request->hitungan_pemakaian == '1') {
            $totalhm = 0;
            
            //Pembentukan total menit dari input time.
            $hourdr = substr($request->jam_dr, 0,2);
            if ($hourdr == '00') {
                $hourdr = 24;
            }
            $mindr = substr($request->jam_dr, 3,2);
            $jam_awal = ($hourdr * 60) + $mindr;

            $hoursp = substr($request->jam_sp, 0,2);
            if ($hoursp == '00') {
                $hoursp = 24;
            }
            $minsp = substr($request->jam_sp, 3,2);
            $jam_akhir = ($hoursp * 60) + $minsp;

            if ($request->istirahat != null) {
                $restjam = substr($request->istirahat, 0,2);
                $restmin = substr($request->istirahat, 3,2);
                $istirahat = ($restjam * 60) + $restmin;
            }else {
                $istirahat = 0;
            }
            
            if ($request->stand_by != null) {
                $standjam = substr($request->stand_by, 0,2);
                $standmin = substr($request->stand_by, 3,2);
                $standby = ($standjam * 60) + $standmin;
            }else {
                $standby = 0;
            }
            
            $potongan = $istirahat + $standby;

            //Total Jam dalam bentuk menit.
            if ($hourdr < $hoursp) {
                $totals = ($jam_akhir - $jam_awal) - $potongan;
            }else {
                $totals = ((1440 + $jam_akhir) - $jam_awal) - $potongan;
            }

            //Konversi total jam dari bentuk menit ke bentuk desimal.
            $total_jam = $totals / 60;

            if ($total_jam < 10) {
                $totjam = '0'.substr($total_jam, 0,1);
            }else {
                $totjam = substr($total_jam, 0,2);
            }

            $mins = $total_jam - $totjam;
            $totmin = $mins * 60;
            if ($totmin < 10) {
                $totmin = '0'.($mins * 60);
            }

            $totaljam = $totjam.':'.$totmin;
        }else {
            $totaljam = 0;
            if ($request->hm_dr > $request->hm_sp) {
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Error Input !!!, HM Dari lebih besar dari HM Sampai.'
                ];
                return response()->json($message);
            }

            $tothm = $request->hm_sp - $request->hm_dr;
            $tothm = number_format($tothm,'2');
            if ($tothm < 10) {
                $minhm = substr($tothm, 2,2);
            }else {
                $minhm = substr($tothm, 3,2);
            }
            
            if ($minhm >= 60) {
                if ($tothm < 10) {
                    $jamhm = substr($tothm, 0,1);
                }else {
                    $jamhm = substr($tothm, 0,2);
                }

                $totjam = $jamhm + 1;
                $totmin = $minhm - 60;
                $totalhm = $totjam.'.'.$totmin;
            }else {
                $totalhm = $tothm;
            }
        }

        $simpan_tabel = [
            'kode_alat'=>$request->kode_alat,
            'no_pemakaian'=>$request->no_pemakaian,
            'hitungan_pemakaian'=>$request->hitungan_pemakaian,
            'no_timesheet'=>$request->no_timesheet,
            'tgl_pakai'=>$request->tgl_pakai,
            'operator'=>$request->operator,
            'helper1'=>$request->helper1,
            'helper2'=>$request->helper2,
            'pekerjaan'=>$request->pekerjaan,
            'hari_libur'=>$request->hari_libur,
            'jam_dr'=>$request->jam_dr,
            'jam_sp'=>$request->jam_sp,
            'istirahat'=>$request->istirahat,
            'stand_by'=>$request->stand_by,
            'hm_dr'=>$request->hm_dr,
            'hm_sp'=>$request->hm_sp,
            'total_jam'=>$totaljam,
            'total_hm'=>$totalhm,
        ];

        $ceker = PemakaianAlatDetail::where('no_timesheet', $request->no_timesheet)->first();
        if ($ceker != null) {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Nomor TimeSheet sudah ada.'
            ];
            return response()->json($message);
        }
        
        PemakaianAlatDetail::create($simpan_tabel);

        $joborderdetail = PemakaianAlatDetail::where('no_pemakaian', $request->no_pemakaian)->get();
        $leng = count($joborderdetail);

        $jobor = PemakaianAlat::find($request->no_pemakaian);
        $jobor->total_timesheet = $leng;
        $jobor->save();
                    
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function edit_detail()
    {
        $id = request()->id;

        $data = PemakaianAlatDetail::find($id);
        $output = array(
            'no_joborder'=>$data->no_joborder,
            'no_pemakaian'=>$data->no_pemakaian,
            'kode_alat'=>$data->kode_alat,
            'hitungan_pemakaian'=>$data->hitungan_pemakaian,
            'no_timesheet'=>$data->no_timesheet,
            'operator'=>$data->operator,
            'helper1'=>$data->helper1,
            'helper2'=>$data->helper2,
            'pekerjaan'=>$data->pekerjaan,
            'tgl_pakai'=>$data->tgl_pakai,
            'hari_libur'=>$data->hari_libur,
            'jam_dr'=>$data->jam_dr,
            'jam_sp'=>$data->jam_sp,
            'istirahat'=>$data->istirahat,
            'stand_by'=>$data->stand_by,
            'hm_dr'=>$data->hm_dr,
            'hm_sp'=>$data->hm_sp,
            'id'=>$data->id,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $head = PemakaianAlat::find($request->no_pemakaian);
        $datetime1 = new DateTime($head->tgl_pemakaian);
        $datetime2 = new DateTime($request->tgl_pakai);

        $diff = $datetime1->diff($datetime2);
        $hari = $diff->d;
        
        $limit = TanggalSetup::find(1);
        if ($hari > $limit->hari) {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Tanggal Pakai tidak boleh selisih lebih dari '.$limit->hari.' hari dari tanggal transaksi.'
            ];
            return response()->json($message);
        }
        
        if ($request->hitungan_pemakaian == '1') {
            $totalhm = 0;
            
            //Pembentukan total menit dari input time.
            $hourdr = substr($request->jam_dr, 0,2);
            if ($hourdr == '00') {
                $hourdr = 24;
            }
            $mindr = substr($request->jam_dr, 3,2);
            $jam_awal = ($hourdr * 60) + $mindr;

            $hoursp = substr($request->jam_sp, 0,2);
            if ($hoursp == '00') {
                $hoursp = 24;
            }
            $minsp = substr($request->jam_sp, 3,2);
            $jam_akhir = ($hoursp * 60) + $minsp;

            if ($request->istirahat != null) {
                $restjam = substr($request->istirahat, 0,2);
                $restmin = substr($request->istirahat, 3,2);
                $istirahat = ($restjam * 60) + $restmin;
            }else {
                $istirahat = 0;
            }
            
            if ($request->stand_by != null) {
                $standjam = substr($request->stand_by, 0,2);
                $standmin = substr($request->stand_by, 3,2);
                $standby = ($standjam * 60) + $standmin;
            }else {
                $standby = 0;
            }
            
            $potongan = $istirahat + $standby;

            //Total Jam dalam bentuk menit.
            if ($hourdr < $hoursp) {
                $totals = ($jam_akhir - $jam_awal) - $potongan;
            }else {
                $totals = ((1440 + $jam_akhir) - $jam_awal) - $potongan;
            }

            //Konversi total jam dari bentuk menit ke bentuk desimal.
            $total_jam = $totals / 60;

            if ($total_jam < 10) {
                $totjam = '0'.substr($total_jam, 0,1);
            }else {
                $totjam = substr($total_jam, 0,2);
            }

            $mins = $total_jam - $totjam;
            $totmin = $mins * 60;
            if ($totmin < 10) {
                $totmin = '0'.($mins * 60);
            }

            $totaljam = $totjam.':'.$totmin;
        }else {
            $totaljam = 0;
            if ($request->hm_dr > $request->hm_sp) {
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Error Input !!!, HM Dari lebih besar dari HM Sampai.'
                ];
                return response()->json($message);
            }

            $tothm = $request->hm_sp - $request->hm_dr;
            $tothm = number_format($tothm,'2');
            if ($tothm < 10) {
                $minhm = substr($tothm, 2,2);
            }else {
                $minhm = substr($tothm, 3,2);
            }
            
            if ($minhm >= 60) {
                if ($tothm < 10) {
                    $jamhm = substr($tothm, 0,1);
                }else {
                    $jamhm = substr($tothm, 0,2);
                }

                $totjam = $jamhm + 1;
                $totmin = $minhm - 60;
                $totalhm = $totjam.'.'.$totmin;
            }else {
                $totalhm = $tothm;
            }
        }

        $ceker = PemakaianAlatDetail::where('no_timesheet', $request->no_timesheet)->where('id', '<>', $request->id)->first();
        if ($ceker != null) {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Nomor TimeSheet sudah ada.'
            ];
            return response()->json($message);
        }

        $update_tabel = [
            'kode_alat'=>$request->kode_alat,
            'no_pemakaian'=>$request->no_pemakaian,
            'hitungan_pemakaian'=>$request->hitungan_pemakaian,
            'no_timesheet'=>$request->no_timesheet,
            'tgl_pakai'=>$request->tgl_pakai,
            'operator'=>$request->operator,
            'helper1'=>$request->helper1,
            'helper2'=>$request->helper2,
            'pekerjaan'=>$request->pekerjaan,
            'hari_libur'=>$request->hari_libur,
            'jam_dr'=>$request->jam_dr,
            'jam_sp'=>$request->jam_sp,
            'istirahat'=>$request->istirahat,
            'stand_by'=>$request->stand_by,
            'hm_dr'=>$request->hm_dr,
            'hm_sp'=>$request->hm_sp,
            'total_jam'=>$totaljam,
            'total_hm'=>$totalhm,
        ];

        $save = PemakaianAlatDetail::find($request->id)->update($update_tabel);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function hapus_detail()
    {   
        $id = request()->id;
        $data = PemakaianAlatDetail::find($id);
        $data->delete();

        $joborderdetail = PemakaianAlatDetail::where('no_pemakaian', $data->no_pemakaian)->get();
        $leng = count($joborderdetail);

        $jobor = PemakaianAlat::find($data->no_pemakaian);
        $jobor->total_timesheet = $leng;
        $jobor->save();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah dihapus.'
        ];
        return response()->json($message);
    }
}
