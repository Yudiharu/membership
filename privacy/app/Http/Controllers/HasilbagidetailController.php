<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\HasilbagiDetail;
use App\Models\Hasilbagi;
use App\Models\Mobil;
use App\Models\GudangDetail;
use App\Models\TruckingDetail;
use App\Models\TruckingnonDetail;
use App\Models\Spbnon;
use App\Models\SpbnonDetail;
use App\Models\Spb;
use App\Models\JobrequestDetail;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use DB;
use Carbon;

class HasilbagidetailController extends Controller
{
    public function index()
    {
        $create_url = route('hasilbagidetail.create');

        return view('admin.hasilbagidetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(HasilbagiDetail::with('mobil','customer','gudangdetail')->where('no_hasilbagi',request()->id)->orderBy('created_at','desc'))
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" data-toggle="tooltip" title="Edit" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" data-toggle="tooltip" title="Hapus" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs"> <i class="fa fa-times-circle"></i></a>';
           })->make(true);
    }

    public function getspb()
    {   
        $no_hasilbagi = request()->no_hasilbagi;

        // $cekhasilbagi = HasilbagiDetail::where('no_hasilbagi', $no_hasilbagi)->first();
        // if($cekhasilbagi != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Update',
        //         'message' => 'Data sudah ada.'
        //     ];
        //     return response()->json($message);
        // }

        $cek_nospb = HasilbagiDetail::where('no_hasilbagi', $no_hasilbagi)->pluck('no_spb','no_spb');

        $kode_sopir = request()->kode_sopir;
        $dari = request()->dari;
        $sampai = request()->sampai;

        $getspb = Spb::where('kode_sopir', $kode_sopir)->where('status_hasilbagi', 1)->whereBetween('tgl_kembali', array($dari, $sampai))->get();
        // dd($getspb);
        $data = array();
        
        if(!empty($getspb)){
            foreach ($getspb as $rowdata){

                $no_spb = $rowdata->no_spb;
                        
                $data[] = array(
                    'no_spb'=>$no_spb,
                );
            }
        

            $leng = count($getspb); 

            $i = 0;
            for($i = 0; $i < $leng; $i++){
                $gettrucking = TruckingDetail::select('trucking_detail.*','spb.*','trucking.no_joborder','trucking.kode_shipper')
                    ->where('trucking_detail.no_spb',$data[$i]['no_spb'])
                    ->join('trucking', 'trucking_detail.no_trucking', '=', 'trucking.no_trucking')
                    ->join('spb','trucking_detail.no_spb', '=', 'spb.no_spb')
                    ->whereBetween('tgl_kembali', array($dari, $sampai))
                    ->where('spb.status_hasilbagi',1)
                    ->whereNotIn('spb.no_spb',$cek_nospb)
                    ->first();

                if($gettrucking != null){
                    $cek_jobreq = $gettrucking->no_joborder;
                    $cek_container = $gettrucking->kode_container;
                    $get_jobreq = JobrequestDetail::where('no_joborder',$cek_jobreq)->where('kode_container',$cek_container)->first();

                    if($gettrucking != null){
                        $tabel_baru = [
                            'no_hasilbagi'=>$no_hasilbagi,
                            'no_spb'=>$gettrucking->no_spb,
                            'no_spb_manual'=>$gettrucking->no_spb_manual,
                            'tanggal_spb'=>$gettrucking->tgl_spb,
                            'tanggal_kembali'=>$gettrucking->tgl_kembali,
                            'kode_mobil'=>$gettrucking->kode_mobil,
                            'kode_gudang'=>$gettrucking->kode_gudang,
                            'kode_container'=>$gettrucking->kode_container,
                            'muatan'=>$gettrucking->muatan,
                            'tarif'=>$gettrucking->tarif_trucking,
                            'uang_jalan'=>$gettrucking->uang_jalan,
                            'bbm'=>$gettrucking->bbm,
                            'sisa'=>($gettrucking->tarif_trucking - $gettrucking->uang_jalan),
                            'sisa_ujbbm'=>($gettrucking->uang_jalan - $gettrucking->bbm),
                            'dari'=>$get_jobreq->dari,
                            'tujuan'=>$get_jobreq->tujuan,
                        ];

                        $createdetail = HasilbagiDetail::create($tabel_baru);
                    }
                }
            }
        }


        $getspbnon = TruckingnonDetail::where('kode_sopir', $kode_sopir)->where('status_hasilbagi', 1)->whereBetween('tanggal_kembali', array($dari, $sampai))->get();

        $datanon = array();
        
        if(!empty($getspbnon)){
            foreach ($getspbnon as $rowdata){

                $no_spb = $rowdata->no_spb;
                        
                $datanon[] = array(
                    'no_spb'=>$no_spb,
                );
            }
        
            $leng2 = count($getspbnon);

            $j = 0;
            for($j = 0; $j < $leng2; $j++){
                $gettruckingnon = TruckingnonDetail::select('truckingnoncontainer_detail.*','trucking_noncontainer.no_joborder','trucking_noncontainer.kode_customer')
                    ->where('truckingnoncontainer_detail.no_spb', $datanon[$j]['no_spb'])
                    ->join('trucking_noncontainer', 'truckingnoncontainer_detail.no_truckingnon', '=', 'trucking_noncontainer.no_truckingnon')
                    ->whereBetween('tanggal_kembali', array($dari, $sampai))
                    ->where('truckingnoncontainer_detail.status_hasilbagi',1)
                    ->whereNotIn('truckingnoncontainer_detail.no_spb',$cek_nospb)
                    ->first();

                if($gettruckingnon != null){
                    $tabel_baru2 = [
                        'no_hasilbagi'=>$no_hasilbagi,
                        'no_spb'=>$gettruckingnon->no_spb,
                        'no_spb_manual'=>$gettruckingnon->no_spb_manual,
                        'tanggal_spb'=>$gettruckingnon->tanggal_spb,
                        'tanggal_kembali'=>$gettruckingnon->tanggal_kembali,
                        'kode_mobil'=>$gettruckingnon->kode_mobil,
                        'kode_gudang'=>'-',
                        'kode_container'=>'-',
                        'muatan'=>'-',
                        'tarif'=>$gettruckingnon->tarif_gajisopir,
                        'uang_jalan'=>$gettruckingnon->uang_jalan,
                        'bbm'=>$gettruckingnon->bbm,
                        'sisa'=>($gettruckingnon->tarif_gajisopir - $gettruckingnon->uang_jalan),
                        'sisa_ujbbm'=>($gettruckingnon->uang_jalan - $gettruckingnon->bbm),
                        'dari'=>$gettruckingnon->dari,
                        'tujuan'=>$gettruckingnon->tujuan,
                    ];
                    // dd($tabel_baru2);

                    $createdetail = HasilbagiDetail::create($tabel_baru2);
                }
            }
        }

        $getdata2 = HasilbagiDetail::where('no_hasilbagi', $no_hasilbagi)->first();

        if ($getdata2 == null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data tidak ada.'
            ];
            return response()->json($message);
        }

        $getdata = HasilbagiDetail::where('no_hasilbagi', $no_hasilbagi)->get();

        $get = Hasilbagi::find($no_hasilbagi);
        $honor_kenek = $get->honor_kenek;
        $gaji_persen = ($get->gaji)/100;
        $tabungan_persen = ($get->tabungan)/100;

        $total_sisa = 0;

        foreach ($getdata as $row){
            $total_sisa += $row->sisa;
        }

        $tot_gaji = $total_sisa * $gaji_persen;
        $tot_tabungan = $tot_gaji * $tabungan_persen;
        $gt_hbu = $tot_gaji - $tot_tabungan + $honor_kenek;

        $gettotal = HasilbagiDetail::where('no_hasilbagi', $no_hasilbagi)->get();
        $totalitem = count($gettotal);

        $output = [
            'nilai_gaji'=>$tot_gaji,
            'nilai_tabungan'=>$tot_tabungan,
            'gt_hbu'=>$gt_hbu,
            'total_item'=>$totalitem,
        ];

        $update_total = Hasilbagi::where('no_hasilbagi', $no_hasilbagi)->update($output);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data berhasil di simpan.'
        ];
        return response()->json($message);
    }

    public function Showdetailspbnc()
    {
        $spbnondetail = SpbnonDetail::where('no_spbnon',request()->id)->orderBy('created_at', 'desc')->get();

        $output = array();
        
            foreach($spbnondetail as $row)
            {

                $kode_item = $row->kode_item;
                $kode_container = $row->kode_container;
                $qty = $row->qty;
                $berat_satuan = $row->berat_satuan;
                $total_berat = $row->total_berat;
                $keterangan = $row->keterangan;

                $output[] = array(
                    'kode_item'=>$kode_item,
                    'kode_container'=>$kode_container,
                    'qty'=>$qty,
                    'berat_satuan'=>$berat_satuan,
                    'total_berat'=>$total_berat,
                    'keterangan'=>$keterangan,
                );
            }

        return response()->json($output);
    }

    public function createspbnon()
    {
        $hasilbagidetail = HasilbagiDetail::where('no_hasilbagi',request()->no_hasilbagi)->first();
        if($hasilbagidetail != null){
            $last_spb = $hasilbagidetail->no_spb;
            $cek_spb = SpbnonDetail::where('no_spbnon',$last_spb)->first();
            if ($cek_spb == null){
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Detail No. SPB ['.$last_spb.'] harus diisi terlebih dahulu.'
                ];
                return response()->json($message);
            } 
        }

        $hasilbagi = Hasilbagi::where('no_hasilbagi',request()->no_hasilbagi)->first();

        $kode_company = auth()->user()->kode_company;
        $kode_noncontainer = 'SPBNC';
        $tahun = Carbon\Carbon::parse($hasilbagi->tanggal_hasilbagi)->format('y');
        $bulan = Carbon\Carbon::parse($hasilbagi->tanggal_hasilbagi)->format('m');

        $spbnon1 = $kode_company.$kode_noncontainer.$tahun.$bulan;
        $cek_spbnon = SpbnonDetail::where(DB::raw('LEFT(no_spbnon,11)'),$spbnon1)->orderBy('no_spbnon','desc')->first();
        // dd($cek_spbnon);

        if ($cek_spbnon != null){
            $spbnon2 = substr($cek_spbnon->no_spbnon,0,11);
            // dd($spbnon2);

            if ($spbnon1 == $spbnon2){
                $kode = substr($cek_spbnon->no_spbnon,14,1);
                if ($kode == 0){
                    $kode = substr($cek_spbnon->no_spbnon,15,1);
                    if ($kode == 0){
                        $kode2 = '00000';
                        $kode = substr($cek_spbnon->no_spbnon,16,1);
                        $kode += 1;
                        if ($kode >= 10){
                            $kode2 = '0000';
                            $kode3 = substr($cek_spbnon->no_spbnon,15,1);
                            $kode3 += 1;
                            if ($kode3 >= 10){
                                $kode2 = '000';
                                $kode4 = substr($cek_spbnon->no_spbnon, 14,1);
                                $kode4 += 1;
                                $kode3 = '0';
                                $hasil = $spbnon1.$kode2.$kode4.$kode3.$kode;
                            }else {
                                $kode = '0';
                                $hasil = $spbnon1.$kode2.$kode3.$kode;
                            }
                        }else {
                            $hasil = $spbnon1.$kode2.$kode;
                        }
                    }else {
                        $kode2 = '000';
                        $kode4 = substr($cek_spbnon->no_spbnon,14,1);
                        $kode3 = substr($cek_spbnon->no_spbnon,15,1);
                        $kode = substr($cek_spbnon->no_spbnon,16,1);
                        $kode += 1;
                        if ($kode >= 10){
                            $kode3 += 1;
                            if ($kode3 >= 10){
                                $kode4 += 1;
                                $kode3 = '0';
                                $kode = '0';
                                $hasil = $spbnon1.$kode2.$kode4.$kode3.$kode;
                            }else {
                                $kode = '0';
                                $hasil = $spbnon1.$kode2.$kode4.$kode3.$kode;
                            }
                        }else {
                            $hasil = $spbnon1.$kode2.$kode4.$kode3.$kode;
                        }
                    }
                }else {
                    $kode2 = '000';
                    $kode4 = substr($cek_spbnon->no_spbnon,14,1);
                    $kode3 = substr($cek_spbnon->no_spbnon,15,1);
                    $kode = substr($cek_spbnon->no_spbnon,16,1);
                    $kode += 1;
                    if ($kode >= 10){
                        $kode3 += 1;
                        if ($kode3 >= 10){
                            $kode4 += 1;
                            $kode3 = '0';
                            $kode = '0';
                            $hasil = $spbnon1.$kode2.$kode4.$kode3.$kode;
                        }else {
                            $kode = '0';
                            $hasil = $spbnon1.$kode2.$kode4.$kode3.$kode;
                        }
                    }else {
                            $hasil = $spbnon1.$kode2.$kode4.$kode3.$kode;
                    }
                }
                // dd($hasil);
            }else {
                $hasil = $spbnon1.'000001';
            }
        }else {
            $hasil = $spbnon1.'000001';
        }

        $cekhasil = TruckingnonDetail::where('no_spb',$hasil)->first();
        if($cekhasil == null){
            $output = array(
                'hasil'=>$hasil,
            );
            return response()->json($output);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Silahkan isi dahulu detail No. SPB ['.$hasil.'] pada transaksi Trucking Non Container.'
            ];
            return response()->json($message);
        }
    }
    
    public function hapusall()
    {
      $cek_hasilbagi = Hasilbagi::find(request()->id);
      $cek_status = $cek_hasilbagi->status;
      if($cek_status == 'POSTED'){  
          $message = [
              'success' => false,
              'title' => 'Simpan',
              'message' => 'Status No. Hasil Bagi: '.$cek_hasilbagi->no_hasilbagi.' sudah POSTED! Pastikan Anda tidak membuka menu HASIL BAGI lebih dari 1',
          ];
          return response()->json($message);
      }
          $no_hasilbagi = request()->id;
          $data_detail = HasilbagiDetail::where('no_hasilbagi',$no_hasilbagi)->delete();
          
            $total = [
                'total_item'=>0,
                'nilai_gaji'=>0,
                'nilai_tabungan'=>0,
                'gt_hbu'=>0,
            ];

            $update_total = Hasilbagi::where('no_hasilbagi', $no_hasilbagi)->update($total);

          $message = [
              'success' => true,
              'title' => 'Simpan',
              'message' => 'Data telah di hapus.',
          ];

          return response()->json($message);
    }

    public function hapus_hasilbagi()
    {   
        $no_hasilbagi = request()->no_hasilbagi;
        $no_spb = request()->no_spb;

        $data = HasilbagiDetail::where('no_hasilbagi',$no_hasilbagi)->where('no_spb',$no_spb)->first();
        $data->delete();

        $getdata = HasilbagiDetail::where('no_hasilbagi', $no_hasilbagi)->get();

        $get = Hasilbagi::find($no_hasilbagi);
        $honor_kenek = $get->honor_kenek;
        $gaji_persen = ($get->gaji)/100;
        $tabungan_persen = ($get->tabungan)/100;

        $total_sisa = 0;

        foreach ($getdata as $row){
            $total_sisa += $row->sisa;
        }

        $tot_gaji = $total_sisa * $gaji_persen;
        $tot_tabungan = $tot_gaji * $tabungan_persen;
        $gt_hbu = $tot_gaji - $tot_tabungan + $honor_kenek;

        $total_item = count($getdata);

        $output = [
            'nilai_gaji'=>$tot_gaji,
            'nilai_tabungan'=>$tot_tabungan,
            'gt_hbu'=>$gt_hbu,
            'total_item'=>$total_item,
        ];

        $update_total = Hasilbagi::where('no_hasilbagi', $no_hasilbagi)->update($output);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$no_hasilbagi.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
