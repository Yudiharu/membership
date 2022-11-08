<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\InsentifoperatorDetail;
use App\Models\Insentifoperator;
use App\Models\Operator;
use App\Models\PemakaianAlat;
use App\Models\PemakaianAlatDetail;
use App\Models\PremiOperator;
use App\Models\Alat;
use App\Models\Joborder;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use App\Models\Sizecontainer;
use DB;
use Carbon;

class InsentifoperatorDetailController extends Controller
{
    public function index()
    {
        $create_url = route('insentifoperatordetail.create');

        return view('admin.insentifoperatordetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(InsentifoperatorDetail::where('no_insentif',request()->id)->orderBy('created_at','desc'))->make(true);
    }

    public function getpakai()
    {
        $no_insentif = request()->no_insentif;
        $kode_operator = request()->kode_operator;
        $tgldari = request()->tgldari;
        $tglsampai = request()->tglsampai;

        $tothm = 0;
        $totjam = 0;
        $totmin = 0;

        $leng = 0;
        $harilibur = 0;
        $luarkota = 0;
        $premi = 0;
        $premi_libur = 0;
        $totalpremijam = 0;
        $totalpremilibur = 0;
        $totalqtyhm = 0;
        $totalqtyjam = 0;
        $gt_insentif = 0;

        $refresher = InsentifoperatorDetail::where('no_insentif', $no_insentif)->delete();

        $detail = PemakaianAlatDetail::where('operator', $kode_operator)->whereBetween('tgl_pakai', array($tgldari, $tglsampai))->where('no_insentif', null)->where('status', 'POSTED')->get();

        foreach ($detail as $row) {
            $cekleng = InsentifoperatorDetail::where('no_timesheet', $row->no_timesheet)->first();
            if ($cekleng == null) {
                $cekpremi = PremiOperator::where('kode_alat', $row->kode_alat)->where('tgl_berlaku', '<=', $row->tgl_pakai)->orderBy('tgl_berlaku','desc')->first();
                if ($cekpremi == null) {
                    $alat = Alat::find($row->kode_alat);
                    $refresher = InsentifoperatorDetail::where('no_insentif', $no_insentif)->delete();
                    $message = [
                        'success' => false,
                        'title' => 'Gagal',
                        'message' => 'Premi Operator untuk kode aset alat '.$alat->no_asset_alat.' belum terdaftar untuk rentang di bawah tanggal '.$row->tgl_pakai.'.'
                    ];
                    return response()->json($message);
                }else {
                    $pakaihd = PemakaianAlat::find($row->no_pemakaian);
                    $cek_jobor = Joborder::find($pakaihd->no_joborder);
                    $opera = Operator::find($kode_operator);

                    if ($cek_jobor->type_kegiatan == '1') {
                        $premi = $cekpremi->premi_jam_nontranshipment;
                    }else {
                        $premi = $cekpremi->premi_jam_transhipment;
                    }

                    if ($opera->status_tembak == '1') {
                        $premi = $cekpremi->premi_opr_tembak;
                    }

                    if ($row->hari_libur == '1') {
                        $premi_libur = $cekpremi->hari_libur;
                        $harilibur += 1;
                    }else {
                        $premi_libur = 0;
                    }

                    if ($cek_jobor->status_lokasi != '1') {
                        $luarkota += 1;
                        $statluar = 1;
                    }else {
                        $statluar = 0;
                    }
                }

                if ($row->hitungan_pemakaian == '1') {
                    $hitjam = substr($row->total_jam, 0,2);
                    $hitmin = substr($row->total_jam, 3,2);

                    $totaljam = $hitjam * $premi;
                    $totalmin = ($hitmin / 60) * $premi;

                    $total_insentif = $totaljam + $totalmin + $premi_libur;

                    $totjam += ($hitjam * 60);
                    $totmin += $hitmin;

                    $totalpremijam += $totaljam + $totalmin;
                    $totalpremilibur += $premi_libur;
                    $gt_insentif += $total_insentif;
                }else {
                    if ($row->total_hm < 10) {
                        $jamhm = substr($row->total_hm, 0,1);
                        $minhm = substr(number_format($row->total_hm,'2'), 2,2);
                    }else {
                        $jamhm = substr($row->total_hm, 0,2);
                        $minhm = substr(number_format($row->total_hm,'2'), 3,2);
                    }

                    $totaljamhm = $jamhm * $premi;
                    $totalminhm = ($minhm / 60) * $premi;

                    $total_insentif = $totaljamhm + $totalminhm + $premi_libur;

                    $tothm += number_format($row->total_hm,'2');

                    $totalpremijam += $totaljamhm + $totalminhm;
                    $totalpremilibur += $premi_libur;
                    $gt_insentif += $total_insentif;
                }

                $leng += 1;

                $simpan = [
                    'no_insentif'=>$no_insentif,
                    'no_timesheet'=>$row->no_timesheet,
                    'no_pemakaian'=>$row->no_pemakaian,
                    'no_joborder'=>$pakaihd->no_joborder,
                    'tgl_pakai'=>$row->tgl_pakai,
                    'hari_libur'=>$row->hari_libur,
                    'jam_dr'=>$row->jam_dr,
                    'jam_sp'=>$row->jam_sp,
                    'hm_dr'=>$row->hm_dr,
                    'hm_sp'=>$row->hm_sp,
                    'istirahat'=>$row->istirahat,
                    'stand_by'=>$row->stand_by,
                    'total_jam'=>$row->total_jam,
                    'total_hm'=>$row->total_hm,
                    'premi_perjam'=>$premi,
                    'premi_libur'=>$premi_libur,
                    'total_insentif'=>$total_insentif,
                    'luar_kota'=>$statluar,
                ];
                InsentifoperatorDetail::create($simpan);

            }
        }

        if ($tothm < 10) {
            $totsjam = substr($tothm, 0,1);
            $totsmin = substr(number_format($tothm,'2'), 2,2);
        }else {
            $totsjam = substr($tothm, 0,2);
            $totsmin = substr(number_format($tothm,'2'), 3,2);
        }

        if ($totsmin >= 60) {
            $totsjam += 1;
            $totsmin = $totsmin - 60;
        }

        $totalqtyhm = $totsjam.'.'.$totsmin;

        $grandjam = $totjam + $totmin;
        $grandiojam = $grandjam / 60;
        $grandeminute = $grandiojam - floor($grandiojam);
        $grandmin = $grandeminute * 60;
        if ($grandmin < 10) {
            $grandmin = '0'.($grandeminute * 60);
        }

        $totalqtyjam = floor($grandiojam).':'.$grandmin;

        $insentifhd = Insentifoperator::find($no_insentif);
        $insentifhd->total_hari = $leng;
        $insentifhd->total_jam = $totalqtyjam;
        $insentifhd->total_hm = $totalqtyhm;
        $insentifhd->total_libur = $harilibur;
        $insentifhd->total_luarkota = $luarkota;
        $insentifhd->total_premi = $totalpremijam;
        $insentifhd->total_premi_libur = $totalpremilibur;
        $insentifhd->gt_insentif = $gt_insentif;
        $insentifhd->save();

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
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);

    }

    public function show_alat()
    {
        $insdetail = InsentifoperatorDetail::find(request()->id);
        $pakaidetail = PemakaianAlatDetail::where('no_timesheet', $insdetail->no_timesheet)->first();
        $alat = Alat::find($pakaidetail->kode_alat);
        $output = array(
            'nama_alat'=>$alat->nama_alat,
            'type'=>$alat->no_asset_alat, 
        );
        return response()->json($output);
    }
    
    public function hapusdetail()
    {
        $detail = InsentifoperatorDetail::where('no_timesheet', request()->no_ts)->first();
        $head = Insentifoperator::where('no_insentif', $detail->no_insentif)->first();

        $head->total_hari -= 1;
        // $head->total_jam = $totalqtyjam;

        //Total HM
        if ($detail->total_hm > 0) {
            $tothm = $head->total_hm - $detail->total_hm;
            if ($tothm < 10) {
                $jamhm = substr($tothm, 0,1);
                $minhm = substr(number_format($tothm,'2'), 2,2);
            }else {
                $jamhm = substr($tothm, 0,2);
                $minhm = substr(number_format($tothm,'2'), 3,2);
            }

            if ($minhm >= 60) {
                $jamhm += 1;
                $minhm = $minhm - 60;
            }

            $head->total_hm = $jamhm.'.'.$minhm;
        }

        //Total Jam
        if ($detail->total_jam != 0) {
            $jamdt = substr($detail->total_jam, 0,2);
            $mindt = substr($detail->total_jam, 3,2);
            $totjamdt = ((int)$jamdt * 60) + (int)$mindt;

            if ($head->total_jam > 10) {
                $jamhd = substr($head->total_jam, 0,2);
                $minhd = substr($head->total_jam, 3,2);
            }else {
                $jamhd = substr($head->total_jam, 0,1);
                $minhd = substr($head->total_jam, 2,2);
            }
            
            $totjamhd = ((int)$jamhd * 60) + (int)$minhd;

            $totjam = $totjamhd - $totjamdt;
            $grandjam = floor($totjam / 60);
            
            $grandmin = (($totjam / 60) - $grandjam) * 60;
            
            if ($grandjam < 10) {
                $grandjam = '0'.floor($grandjam);
            }
            
            if ($grandmin < 10) {
                $grandmin = '0'.floor($grandmin);
            }else {
                $grandmin = floor($grandmin);
            }
    
            $head->total_jam = floor($grandjam).':'.$grandmin;
        }

        if ($detail->hari_libur == 1) {
            $head->total_libur -= 1;
            $head->total_premi_libur -= $detail->premi_libur;
        }

        if ($detail->luar_kota == 1) {
            $head->total_luarkota -= 1;
        }
        
        $head->total_premi -= ($detail->total_insentif - $detail->premi_libur);
        $head->gt_insentif -= $detail->total_insentif;
        $head->save();

        InsentifoperatorDetail::where('no_timesheet', request()->no_ts)->delete();

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Dihapus.',
        ];
        return response()->json($message);
    }

    public function hapusall()
    {
        $cek_insentif = Insentifoperator::find(request()->id);
        $cek_status = $cek_insentif->status;
        if($cek_status == 'POSTED'){  
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Status No. Premi: '.$cek_insentif->no_insentif.' sudah POSTED! Pastikan Anda tidak membuka menu PREMI OPERATOR lebih dari 1 TAB',
            ];
            return response()->json($message);
        }

        $no_insentif = request()->id;
        $data_detail = InsentifoperatorDetail::where('no_insentif',$no_insentif)->delete();
          
        $total = [
            'total_hari'=>0,
            'total_jam'=>0,
            'total_hm'=>0,
            'total_libur'=>0,
            'total_luarkota'=>0,
            'total_premi'=>0,
            'total_premi_libur'=>0,
            'gt_insentif'=>0,
        ];

        $update_total = Insentifoperator::where('no_insentif', $no_insentif)->update($total);

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di hapus.',
        ];

        return response()->json($message);
    }

    public function Showdetailjobreq()
    {
        $jobrequestdetail = JobrequestDetail::where('no_req_jo',request()->id)->orderBy('created_at', 'desc')->get();

        $output = array();
        
            foreach($jobrequestdetail as $row)
            {

                $kode_container = $row->kode_container;
                $kode_size = $row->sizecontainer->nama_size;
                $status_muatan = $row->status_muatan;
                $dari = $row->dari;
                $tujuan = $row->tujuan;

                $output[] = array(
                    'kode_container'=>$kode_container,
                    'kode_size'=>$kode_size,
                    'status_muatan'=>$status_muatan,
                    'dari'=>$dari,
                    'tujuan'=>$tujuan,
                );
            }

        return response()->json($output);
    }

    public function numberFormatPrecision($number, $precision = 2, $separator = '.')
    {
        $numberParts = explode($separator, $number);
        $response = $numberParts[0];
        if (count($numberParts)>1 && $precision > 0) {
            $response .= $separator;
            $response .= substr($numberParts[1], 0, $precision);
        }
        return $response;
    }

    public function store(Request $request)
    {
        
        $simpan = [
            'no_joborder'=>$request->no_joborder,
            'deskripsi'=>$request->deskripsi,
            'qty'=>$request->qty,
            'satuan'=>$request->satuan,
            'harga'=>$request->harga,
            'mob_demob'=>$request->mob_demob,
            'total_harga'=>($request->qty*$request->harga)+$request->mob_demob,
        ];
        
        JoborderDetail::create($simpan);

        $joborderdetail = JoborderDetail::where('no_joborder', $request->no_joborder)->get();
        $leng = count($joborderdetail);
        $mob = 0;
        $grand = 0;
        foreach ($joborderdetail as $row) {
            $mob += $row->mob_demob;
            $grand += ($row->qty*$row->harga) + $row->mob_demob;
        }

        $jobor = Joborder::find($request->no_joborder);
        $jobor->total_item = $leng;
        $jobor->mob_demob = $mob;
        $jobor->grand_total = $grand;
        $jobor->save();
                    
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }
}
