<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\JoborderDetail;
use App\Models\Joborder;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use App\Models\Sizecontainer;
use App\Models\PemakaianAlatDetail;
use App\Models\PemakaianAlat;
use App\Models\PremiHelper;
use App\Models\Insentifhelper;
use App\Models\Alat;
use App\Models\InsentifhelperDetail;
use DB;
use Carbon;

class InsentifhelperDetailController extends Controller
{
    public function index()
    {
        $create_url = route('insentifhelperdetail.create');

        return view('admin.insentifhelperdetail.index',compact('create_url','header','tgldr','tglsp'));
    }

    public function getDatabyID()
    {
        return Datatables::of(InsentifhelperDetail::where('no_insentif',request()->id)->orderBy('created_at','desc'))->make(true);
    }
    
        public function getpakai()
    {
        $no_insentif = request()->no_insentif;
        $type_helper = request()->type_helper;
        $helper = request()->kode_helper;
        $tgl_dr = request()->tgl_pakai_dari;
        $tgl_sp = request()->tgl_pakai_sampai;

        //Delete ketika tarik
        InsentifhelperDetail::where('no_insentif',$no_insentif)->delete();

        //cek tipe helper
        if ($type_helper == '1')
        {
            $detail = PemakaianAlatDetail::where('helper1', $helper)->whereBetween('tgl_pakai',array($tgl_dr,$tgl_sp))->where('no_insentif_helper1', null)->where('status', 'POSTED')->get();
        }        
        else if ($type_helper =='2')
        {
            $detail = PemakaianAlatDetail::where('helper2', $helper)->whereBetween('tgl_pakai',array($tgl_dr,$tgl_sp))->where('no_insentif_helper2', null)->where('status', 'POSTED')->get();
        }
        
        //declare variable yg akan dipakai
        $leng = 0;
        $premidk = 0;
        $premilk = 0;
        $premilibur = 0;
        $totalinsentif = 0;
        $totaldk = 0;
        $totallk = 0;
        $totallibur = 0;
        $totalpremidk = 0;
        $totalpremilk = 0;
        $totalpremilibur = 0;
        $gt_insentif = 0;

        //perulangan
        foreach ($detail as $row)
        {
            $cekleng = InsentifhelperDetail::where('no_timesheet', $row->no_timesheet)->first();
            if($cekleng == null)
            {
                $no_timesheet = $row->no_timesheet;
                $no_pemakaian = $row->no_pemakaian;
                $tgl_pakai = $row->tgl_pakai;
                $hari_libur = $row->hari_libur;
                $kode_alat = $row->kode_alat;

                $cekpremi = PremiHelper::where('tgl_berlaku','<=',$tgl_pakai)->where('kode_alat',$kode_alat)->orderBy('tgl_berlaku','desc')->first();

                $getnojo = PemakaianAlat::find($no_pemakaian);
                $getjo = Joborder::find($getnojo->no_joborder);

                $cekluarkota = $getjo->status_lokasi;

                if($cekpremi == null)
                {
                    InsentifhelperDetail::where('no_insentif',$no_insentif)->delete();
                    $alat = Alat::where('kode_alat', $kode_alat)->first();

                    $message = [
                        'success' => false,
                        'title' => 'Update',
                        'message' => 'Alat '.$alat->type.' belum ditambahkan Premi Helper untuk tanggal '.$tgl_pakai
                    ];
                    return response()->json($message);
                }
                else
                {
                    if ($cekluarkota == '1')
                    {
                        $premidk = $cekpremi->premi_harian_dk;
                        $totalpremidk += $premidk;
                        $premilk = 0;
                        $luarkota = 0;
                        $totaldk++;
                    }

                    else
                    {
                        $premilk = $cekpremi->premi_harian_lk;
                        $totalpremilk += $premilk;
                        $premidk = 0;
                        $luarkota = 1;
                        $totallk++;
                    }

                    if ($hari_libur == '0')
                    {
                        $premilibur = 0;
                    }
                    else 
                    {
                        $premilibur = $cekpremi->hari_libur;
                        $totalpremilibur += $premilibur;
                        $totallibur++;
                    }

                    $totalinsentif = $premidk + $premilk + $premilibur;

                    $gt_insentif += $totalinsentif;

                    $simpan_tabel = 
                    [
                        'no_insentif'=>$no_insentif,
                        'no_timesheet'=>$no_timesheet,
                        'no_pemakaian'=>$no_pemakaian,
                        'tgl_pakai'=>$tgl_pakai,
                        'hari_libur'=>$hari_libur,
                        'no_joborder'=>$getjo->no_joborder,
                        'premi_dalamkota'=>$premidk,
                        'premi_luarkota'=>$premilk,
                        'premi_libur'=>$premilibur,
                        'total_insentif'=>$totalinsentif,
                        'luar_kota'=>$luarkota,
                    ];

                    $leng += 1;

                    InsentifhelperDetail::create($simpan_tabel);

                    $header = Insentifhelper::find($no_insentif);
                    $header->total_dalamkota = $totaldk;
                    $header->total_luarkota = $totallk;
                    $header->total_libur = $totallibur;
                    $header->gt_insentif = $gt_insentif;
                    $header->total_premi_dalamkota = $totalpremidk;
                    $header->total_premi_luarkota = $totalpremilk;
                    $header->total_premi_libur = $totalpremilibur;
                    $header->save();
                }          
            }
        }

        if ($leng == 0) {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Tidak ada data.'
            ];
            return response()->json($message);
        }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'simpan.'
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

    public function show_alat()
    {
        $insdetail = InsentifhelperDetail::find(request()->id);
        $pakaidetail = PemakaianAlatDetail::where('no_timesheet', $insdetail->no_timesheet)->first();
        $alat = Alat::find($pakaidetail->kode_alat);
        $output = array(
            'nama_alat'=>$alat->nama_alat,
            'type'=>$alat->no_asset_alat, 
        );
        return response()->json($output);
    }

    public function hapusall()
    {

        $header = Insentifhelper::find(request()->no_insentif);
        InsentifhelperDetail::where('no_insentif',request()->no_insentif)->delete();

        $header->total_dalamkota = 0;
        $header->total_luarkota = 0;
        $header->total_libur = 0;
        $header->gt_insentif = 0;
        $header->total_premi_dalamkota = 0;
        $header->total_premi_luarkota = 0;
        $header->total_premi_libur = 0;
        $header->save();
        $message = [
              'success' => true,
              'title' => 'Hapus',
              'message' => 'Semua detail No. Pemakaian: '.request()->id.' sudah DIHAPUS!',
          ];
          return response()->json($message);
    }
    
    public function hapus_detail()
    {   
        $id = request()->id;
        $data = InsentifhelperDetail::find($id);
        $header = Insentifhelper::find($data->no_insentif);
        $header->total_premi_dalamkota = $header->total_premi_dalamkota - $data->premi_dalamkota;
        $header->total_premi_luarkota = $header->total_premi_luarkota - $data->premi_luarkota;
        $header->total_premi_libur = $header->total_premi_libur - $data->premi_libur;
        $header->gt_insentif = $header->gt_insentif - $data->total_insentif;
        
        if($data->hari_libur == 0)
        {
            $header->total_libur = $header->total_libur;
        }
        else
        {
            $header->total_libur = $header->total_libur - 1;
        }

        if($data->luar_kota == 0)
        {
            $header->total_dalamkota = $header->total_dalamkota - 1;
        }
        else
        {
            $header->total_luarkota = $header->total_luarkota - 1;
        }

        $header->save();
        $data->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah dihapus.'
        ];
        return response()->json($message);
    }
}
