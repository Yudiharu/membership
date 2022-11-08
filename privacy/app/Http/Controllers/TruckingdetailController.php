<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\TruckingDetail;
use App\Models\Trucking;
use App\Models\Spb;
use App\Models\TarifTrucking;
use App\Models\Pemilik;
use App\Models\PemilikDetail;
use App\Models\GudangDetail;
use App\Models\Sizecontainer;
use App\Models\PembayaranDetail;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use App\Models\JobrequestDetail;
use App\Models\Joborder;
use App\Models\JoborderDetail;
use App\Models\Sopir;
use App\Models\Mobil;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\TransaksiSetup;
use App\Models\HasilbagiDetail;
use DB;
use Carbon;

class TruckingdetailController extends Controller
{
    public function index()
    {
        $create_url = route('truckingdetail.create');

        return view('admin.truckingdetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        $get_trucking = Trucking::find(request()->id);

        return Datatables::of(TruckingDetail::select('trucking_detail.*','spb.tgl_spb','spb.tgl_kembali','spb.kode_mobil','jobrequest_detail.dari','jobrequest_detail.tujuan')->with('sizecontainer','gudangdetail','spb')->join('spb','spb.no_spb','=','trucking_detail.no_spb')->join('jobrequest_detail','jobrequest_detail.kode_container','=','trucking_detail.kode_container')->where('trucking_detail.no_trucking',request()->id)->where('jobrequest_detail.no_req_jo',$get_trucking->no_req_jo)->orderBy('trucking_detail.created_at','desc'))->make(true);
    }

    public function pemilik()
    {
        $pemilik_detail = PemilikDetail::where('kode_mobil',request()->kode_mobil)->first();
        $pemilik = Vendor::where('id',$pemilik_detail->kode_pemilik)->first();

        $cek_mobil = Mobil::find(request()->kode_mobil);
        
        $output = [
                'pemilik'=>$pemilik->nama_vendor,
                'kode_pemilik'=>$pemilik->kode_pemilik,
                'kode_sopir'=>$cek_mobil->kode_sopir,
                'no_asset_mobil'=>$cek_mobil->no_asset_mobil,
            ];
        return response()->json($output);
    }

    public function pemilik2()
    {
        $pemilik_detail = PemilikDetail::where('kode_mobil',request()->kode_mobil)->first();
        $pemilik = Vendor::where('id',$pemilik_detail->kode_pemilik)->first();
        $cekspb = Spb::find(request()->no_spb);
        $cek_mobil = Mobil::find(request()->kode_mobil);
        if($cekspb->no_asset_mobil != null){
            $output = [
                'pemilik'=>$pemilik->nama_vendor,
                'kode_pemilik'=>$pemilik->id,
                'kode_sopir'=>$cekspb->kode_sopir,
                'no_asset_mobil'=>$cekspb->no_asset_mobil,
            ];
        }else{
            $output = [
                'pemilik'=>$pemilik->nama_vendor,
                'kode_pemilik'=>$pemilik->id,
                'kode_sopir'=>$cekspb->kode_sopir,
                'no_asset_mobil'=>$cek_mobil->no_asset_mobil,
            ];
        }
        return response()->json($output);
    }
    
    public function gettarif()
    {
        $gudangdetail = GudangDetail::find(request()->kode_gudang);
        $getgudang = $gudangdetail->id;
        $getshipper = $gudangdetail->kode_shipper;

        $tgl = date('Y-m-d');

        $gettarif = TarifTrucking::where('kode_gudang',$getgudang)->where('kode_shipper',$getshipper)->where('tanggal_berlaku', '<=', request()->tgl_spb)->orderBy('tanggal_berlaku', 'desc')->first();

        if($gettarif != null){
            $tarif = $gettarif->tarif_trucking;
        }else{
            $tarif = '';
        }
         
        $output = array(
            'tarif_trucking'=>$tarif,
        );
       
        return response()->json($output);
    }

    public function gettariftgl()
    {
         $gudangdetail = GudangDetail::find(request()->kode_gudang);
         $getgudang = $gudangdetail->kode_gudang;
         $getshipper = $gudangdetail->kode_shipper;

         $tgl = date('Y-m-d');

         $gettarif = TarifTrucking::where('kode_gudang',$getgudang)->where('kode_shipper',$getshipper)->where('tanggal_berlaku', '<=', request()->tgl_spb)->orderBy('tanggal_berlaku', 'desc')->first();

         $tarif = $gettarif->tarif_trucking;

            $output = array(
                'tarif_trucking'=>$tarif,
            );
        return response()->json($output);
    }

    public function Showdetailspb()
    {
        $spb = Spb::join('u5611458_db_pusat.vendor','spb.kode_pemilik','=','u5611458_db_pusat.vendor.id')->with('sopir','mobil')->where('no_spb',request()->id)->first();
        $tgl_spb = $spb->tgl_spb;

        $output = array();
        if($tgl_spb != null){
            $no_spb_manual = $spb->no_spb_manual;
            $tgl_spb = $spb->tgl_spb;
            $tgl_kembali = $spb->tgl_kembali;

            $cek_mobil = Mobil::find($spb->kode_mobil);
            if($cek_mobil != null){
                $kode_mobil = $spb->mobil->nopol;
            }else{
                $kode_mobil = $spb->kode_mobil;
            }

            $cek_sopir = Sopir::find($spb->kode_sopir);
            if($cek_sopir != null){
                $kode_sopir = $spb->sopir->nama_sopir;
            }else{
                $kode_sopir = $spb->kode_sopir;
            }
            
            $kode_pemilik = $spb->nama_vendor;
            $uang_jalan = $spb->uang_jalan;
            $bbm = $spb->bbm;
            $bpa = $spb->bpa;
            $honor = $spb->honor;
            $biaya_lain = $spb->biaya_lain;
            $trucking = $spb->trucking;
                    
            $output[] = array(
                'no_spb_manual'=>$no_spb_manual,
                'tgl_spb'=>$tgl_spb,
                'tgl_kembali'=>$tgl_kembali,
                'kode_mobil'=>$kode_mobil,
                'kode_sopir'=>$kode_sopir,
                'kode_pemilik'=>$kode_pemilik,
                'uang_jalan'=>$uang_jalan,
                'bbm'=>$bbm,
                'bpa'=>$bpa,
                'honor'=>$honor,
                'biaya_lain'=>$biaya_lain,
                'trucking'=>$trucking,
            );

            return response()->json($output);
        }
        else{
            $output[] = array(
                'no_spb_manual'=>'',
                'tgl_spb'=>'',
                'tgl_kembali'=>'',
                'kode_mobil'=>'',
                'kode_sopir'=>'',
                'kode_pemilik'=>'',
                'uang_jalan'=>'',
                'bbm'=>'',
                'bpa'=>'',
                'honor'=>'',
                'biaya_lain'=>'',
                'trucking'=>'',
            );

            return response()->json($output);
        }
    }

    public function getjor()
    {   
        $no_req_jo = request()->no_req_jo;
        $no_trucking = request()->no_trucking;

        // $cekdetail = TruckingDetail::where('no_trucking', $no_trucking)->first();
        // if($cekdetail != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Update',
        //         'message' => 'Data sudah ada.'
        //     ];
        //     return response()->json($message);
        // }

        $get_jo = Trucking::find($no_trucking);
        $get_job = Joborder::where('no_joborder',$get_jo->no_joborder)->first();
        if($get_job->status != '2'){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'No Job Order ['.$no_trucking.'] belum di POSTING.'
            ];
            return response()->json($message);
        }

        $leng1 = $get_jo->total_item;
        $no_joborder = $get_jo->no_joborder;
        $cek_spb = Spb::where('no_joborder',$no_joborder)->first();
        if($cek_spb == null){
            $getjor = JobrequestDetail::where('no_req_jo',$no_req_jo)->get();
            $leng = count($getjor); 

            $total = [
                    'total_item'=>$leng,
            ];
            $update_total = Trucking::where('no_trucking', $no_trucking)->update($total);

            $data = array();
            
            if(!empty($getjor)){
                foreach ($getjor as $rowdata){

                    $kode_container = $rowdata->kode_container;
                    $kode_size = $rowdata->kode_size;
                            
                    $data[] = array(
                        'kode_container'=>$kode_container,
                        'kode_size'=>$kode_size,
                    );
                }
            }

            $get_trucking = Trucking::find($no_trucking);
            $no_joborder = $get_trucking->no_joborder;

            $i = 0;
            for($i = 0; $i < $leng; $i++){
                $getspb = $this->createspb($no_trucking);
                $tabel_baru = [
                    'kode_container'=>$data[$i]['kode_container'],
                    'kode_size'=>$data[$i]['kode_size'],
                    'no_spb'=>$getspb,
                    'no_trucking'=>$no_trucking,
                    'no_joborder'=>$no_joborder,
                ];
                // dd($tabel_baru);
                $createdetail = TruckingDetail::create($tabel_baru);


                $spb_baru = [
                    'no_spb'=>$getspb,
                    'no_joborder'=>$no_joborder,
                ];
                $createspb = Spb::create($spb_baru);
            }

            $cekspb2 = Spb::where('no_joborder',$get_jo->no_joborder)->where('tgl_kembali',null)->first();
            if($cekspb2 != null){
                $status_job = Trucking::where('no_joborder',$get_jo->no_joborder)->first();
                $status_job->status_kembali = "FALSE";
                $status_job->save();
            }
            
            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data berhasil di simpan.'
            ];
            return response()->json($message);
        }else{
            $gettruckcek = TruckingDetail::where('no_joborder',$no_joborder)->pluck('kode_container','kode_container');
            $getcon = JobrequestDetail::where('no_req_jo',$no_req_jo)->whereNotIn('kode_container',$gettruckcek)->first();
            $gettruck = JobrequestDetail::where('no_req_jo',$no_req_jo)->whereNotIn('kode_container',$gettruckcek)->get();
            if($getcon == null){
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Detail Job Request sudah terisi.'
                ];
                return response()->json($message);
            }

            $data = array();
            if(!empty($gettruck)){
                foreach ($gettruck as $rowdata){

                    $kode_container = $rowdata->kode_container;
                    $kode_size = $rowdata->kode_size;
                            
                    $data[] = array(
                        'kode_container'=>$kode_container,
                        'kode_size'=>$kode_size,
                    );
                }
            }

            $leng = count($gettruck); 

            $total = [
                    'total_item'=>$leng + $leng1,
            ];
            $update_total = Trucking::where('no_trucking', $no_trucking)->update($total);

            $get_trucking = Trucking::find($no_trucking);
            $no_joborder = $get_trucking->no_joborder;

            $i = 0;
            for($i = 0; $i < $leng; $i++){
                $getspb = $this->createspb($no_trucking);
                $tabel_baru = [
                    'kode_container'=>$data[$i]['kode_container'],
                    'kode_size'=>$data[$i]['kode_size'],
                    'no_spb'=>$getspb,
                    'no_trucking'=>$no_trucking,
                    'no_joborder'=>$no_joborder,
                ];
                // dd($tabel_baru);
                $createdetail = TruckingDetail::create($tabel_baru);


                $spb_baru = [
                    'no_spb'=>$getspb,
                    'no_joborder'=>$no_joborder,
                ];
                $createspb = Spb::create($spb_baru);
            }

            $cekspb2 = Spb::where('no_joborder',$get_jo->no_joborder)->where('tgl_kembali',null)->first();
            if($cekspb2 != null){
                $status_job = Trucking::where('no_joborder',$get_jo->no_joborder)->first();
                $status_job->status_kembali = "FALSE";
                $status_job->save();
            }
            
            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data berhasil di simpan.'
            ];
            return response()->json($message);
        }
    }

    public function createspb($no_trucking)
    {
        $truckingdetail = TruckingDetail::where('no_trucking',$no_trucking)->orderBy('no_spb','desc')->first();
        if($truckingdetail != null){
            $last_spb = $truckingdetail->no_spb;
            $cek_spb = Spb::find($last_spb);
            if ($cek_spb == null){
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Detail No. SPB ['.$last_spb.'] harus diisi terlebih dahulu.'
                ];
                return response()->json($message);
            } 
        }

        $trucking = Trucking::where('no_trucking',$no_trucking)->first();

        $kode_company = auth()->user()->kode_company;
        $getkode = TransaksiSetup::where('kode_setup','009')->first();
        $kode_container = $getkode->kode_transaksi;
        $tahun = Carbon\Carbon::parse($trucking->tanggal_trucking)->format('y');
        $bulan = Carbon\Carbon::parse($trucking->tanggal_trucking)->format('m');

        $spb1 = $kode_company.$kode_container.$tahun.$bulan;
        $cek_spb = Spb::where(DB::raw('LEFT(no_spb,9)'),$spb1)->orderBy('no_spb','desc')->first();

        if ($cek_spb != null){
            $spb2 = substr($cek_spb->no_spb,0,9);
            // dd($spbnon2);

            if ($spb1 == $spb2){
                $kode = substr($cek_spb->no_spb,12,1);
                if ($kode == 0){
                    $kode = substr($cek_spb->no_spb,13,1);
                    if ($kode == 0){
                        $kode2 = '00000';
                        $kode = substr($cek_spb->no_spb,14,1);
                        $kode += 1;
                        if ($kode >= 10){
                            $kode2 = '0000';
                            $kode3 = substr($cek_spb->no_spb,13,1);
                            $kode3 += 1;
                            if ($kode3 >= 10){
                                $kode2 = '000';
                                $kode4 = substr($cek_spb->no_spb, 12,1);
                                $kode4 += 1;
                                $kode3 = '0';
                                $hasil = $spb1.$kode2.$kode4.$kode3.$kode;
                            }else {
                                $kode = '0';
                                $hasil = $spb1.$kode2.$kode3.$kode;
                            }
                        }else {
                            $hasil = $spb1.$kode2.$kode;
                        }
                    }else {
                        $kode2 = '000';
                        $kode4 = substr($cek_spb->no_spb,12,1);
                        $kode3 = substr($cek_spb->no_spb,13,1);
                        $kode = substr($cek_spb->no_spb,14,1);
                        $kode += 1;
                        if ($kode >= 10){
                            $kode3 += 1;
                            if ($kode3 >= 10){
                                $kode4 += 1;
                                $kode3 = '0';
                                $kode = '0';
                                $hasil = $spb1.$kode2.$kode4.$kode3.$kode;
                            }else {
                                $kode = '0';
                                $hasil = $spb1.$kode2.$kode4.$kode3.$kode;
                            }
                        }else {
                            $hasil = $spb1.$kode2.$kode4.$kode3.$kode;
                        }
                    }
                }else {
                    $kode2 = '000';
                    $kode4 = substr($cek_spb->no_spb,12,1);
                    $kode3 = substr($cek_spb->no_spb,13,1);
                    $kode = substr($cek_spb->no_spb,14,1);
                    $kode += 1;
                    if ($kode >= 10){
                        $kode3 += 1;
                        if ($kode3 >= 10){
                            $kode4 += 1;
                            $kode3 = '0';
                            $kode = '0';
                            $hasil = $spb1.$kode2.$kode4.$kode3.$kode;
                        }else {
                            $kode = '0';
                            $hasil = $spb1.$kode2.$kode4.$kode3.$kode;
                        }
                    }else {
                            $hasil = $spb1.$kode2.$kode4.$kode3.$kode;
                    }
                }
                // dd($hasil);
            }else {
                $hasil = $spb1.'000001';
            }
        }else {
            $hasil = $spb1.'000001';
        }

        $cekhasil = PembayaranDetail::where('no_spb',$hasil)->first();
        if($cekhasil == null){
            return $hasil;
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Silahkan isi dahulu detail No. SPB ['.$hasil.'] pada transaksi Pembayaran.'
            ];
            return response()->json($message);
        }
    }

    public function add_spb()
    {
        $no_spb = request()->no_spb;
        $no_trucking = request()->no_trucking;
        $cek_spb = Spb::find($no_spb);
        // dd($cek_spb);
        if($cek_spb == null){
            $output = array(
                'no_trucking'=>$no_trucking,
                'no_spb'=>$no_spb,
                'no_spb_manual'=>'',
                'tgl_spb'=>'',
                'tgl_kembali'=>'',
                'kode_mobil'=>'',
                'kode_sopir'=>'',
                'kode_pemilik'=>'',
                'uang_jalan'=>'',
                'bbm'=>'',
                'bpa'=>'',
                'honor'=>'',
                'biaya_lain'=>'',
                'trucking'=>'',
            );
            return response()->json($output);
        }else{
            $output = array(
                'no_trucking'=>$no_trucking,
                'no_spb'=>$cek_spb->no_spb,
                'no_spb_manual'=>$cek_spb->no_spb_manual,
                'tgl_spb'=>$cek_spb->tgl_spb,
                'tgl_kembali'=>$cek_spb->tgl_kembali,
                'kode_mobil'=>$cek_spb->kode_mobil,
                'kode_sopir'=>$cek_spb->kode_sopir,
                'kode_pemilik'=>$cek_spb->kode_pemilik,
                'uang_jalan'=>$cek_spb->uang_jalan,
                'bbm'=>$cek_spb->bbm,
                'bpa'=>$cek_spb->bpa,
                'honor'=>$cek_spb->honor,
                'biaya_lain'=>$cek_spb->biaya_lain,
                'trucking'=>$cek_spb->trucking,
            );
            return response()->json($output);
        }
        
    }

    public function store(Request $request)
    {
        $truckingdetail = TruckingDetail::where('no_trucking', $request->no_trucking)->where('kode_container', $request->kode_container)->get();

        $leng = count($truckingdetail);

                if($leng > 0){
                    $message = [
                        'success' => false,
                        'title' => 'Gagal',
                        'message' => 'Container Sudah Ada'
                    ];
                    return response()->json($message);
                }
                else{
                    $truckingdetail = TruckingDetail::create($request->all());

                    $createspb = [
                        'no_spb'=>$request->no_spb,
                    ];

                    $update_spb = Spb::create($createspb);

                    $trucking = Trucking::find($request->no_trucking);
                    
                    $hitung = TruckingDetail::where('no_trucking', $request->no_trucking)->get();
                    $total_item = count($hitung);

                    $trucking->total_item = $total_item;
                    $trucking->save();

                    $message = [
                        'success' => true,
                        'title' => 'Update',
                        'message' => 'Data telah disimpan'
                    ];
                    return response()->json($message);
                }
    }

    public function edit_trucking()
    {
        $no_trucking = request()->no_trucking;
        $no_spb = request()->no_spb;

        $data = TruckingDetail::where('no_trucking',$no_trucking)->where('no_spb',$no_spb)->first();
        $getspb = Spb::find($no_spb);
        $getjor = JobrequestDetail::where('no_joborder',$getspb->no_joborder)->where('kode_container',$data->kode_container)->first();
        $getheader = Trucking::find($no_trucking);
        $getcust = Customer::find($getheader->kode_shipper);
        $getsize = Sizecontainer::find($data->kode_size);
        $output = array(
            'no_trucking'=>$data->no_trucking,
            'nama_shipper'=>$getcust->nama_customer,
            'kode_gudang'=>$data->kode_gudang,
            'kode_container'=>$data->kode_container,
            'kode_size'=>$data->kode_size,
            'nama_size'=>$getsize->nama_size,
            'no_seal'=>$data->no_seal,
            'no_spb'=>$data->no_spb,
            'tgl_spb'=>$getspb->tgl_spb,
            'muatan'=>$data->muatan,
            'colie'=>$data->colie,
            'tarif_trucking'=>$data->tarif_trucking,
            'id'=>$data->id,
            'dari'=>$getjor->dari,
            'tujuan'=>$getjor->tujuan,
        );

        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $cek_nospb = TruckingDetail::where('no_trucking', $request->no_trucking)->pluck('no_spb','no_spb');

        $cek_detail_byr = PembayaranDetail::where('no_spb',$request->no_spb)->first();
        if($cek_detail_byr != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di EDIT, SPB '.$cek_detail_byr->no_spb.' ada dalam detail no BYR '.$cek_detail_byr->no_pembayaran.' Silahakan hapus terlebih dulu dari detail BYR.'
            ];
            return response()->json($message);
        }

        $cek_detail_hbu = HasilbagiDetail::where('no_spb',$request->no_spb)->first();

        if($cek_detail_hbu != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di EDIT, SPB '.$cek_detail_hbu->no_spb.' ada dalam detail no HBU '.$cek_detail_hbu->no_hasilbagi.' Silahakan hapus terlebih dulu dari detail HBU.'
            ];
            return response()->json($message);
        }
        
        if($request->tarif_trucking == 0){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Tarif tidak boleh 0'
            ];
            return response()->json($message);
        }

        $cek_pemilik = Trucking::find($request->no_trucking);

        $request->validate([
            'no_trucking'=> 'required',
            'kode_gudang'=> 'required',
            'kode_container'=> 'required',
            'kode_size'=> 'required',
            'no_seal'=> 'required',
        ]);

        $satuan = TruckingDetail::find($request->id)->update($request->all());
        
        $hitung = TruckingDetail::where('no_trucking', $request->no_trucking)->get();
        $leng = count($hitung);

        $update_trucking = Trucking::where('no_trucking', $request->no_trucking)->first();
        $update_trucking->total_item = $leng;
        $update_trucking->save();

        $get_spb = Spb::find($request->no_spb);
        $get_spb->trucking = $request->tarif_trucking;
        $get_spb->save();
        $get_spb->tgl_spb = $request->tgl_spb;
        $get_spb->save();

        $update_jor = [
            'dari'=>$request->dari,
            'tujuan'=>$request->tujuan,
        ];

        $jor_update = JobrequestDetail::where('kode_container', $request->kode_container)->update($update_jor);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function edit_spb()
    {
        $no_spb = request()->no_spb;
        $data = Spb::find($no_spb);
        if($data->kode_pemilik != null){
            $output = array(
                'no_spb'=>$data->no_spb,
                'no_spb_manual'=>$data->no_spb_manual,
                'tgl_spb'=>$data->tgl_spb,
                'tgl_kembali'=>$data->tgl_kembali,
                'nama_pemilik'=>$data->nama_vendor,
                'kode_mobil'=>$data->kode_mobil,
                'no_asset_mobil'=>$data->no_asset_mobil,
                'kode_sopir'=>$data->kode_sopir,
                'kode_pemilik'=>$data->kode_pemilik,
                'uang_jalan'=>$data->uang_jalan,
                'bbm'=>$data->bbm,
                'bpa'=>$data->bpa,
                'honor'=>$data->honor,
                'biaya_lain'=>$data->biaya_lain,
                'trucking'=>$data->trucking,
            );
        }else{
            $output = array(
                'no_spb'=>$data->no_spb,
                'no_spb_manual'=>$data->no_spb_manual,
                'tgl_spb'=>$data->tgl_spb,
                'tgl_kembali'=>$data->tgl_kembali,
                'nama_pemilik'=>$data->kode_pemilik,
                'kode_mobil'=>$data->kode_mobil,
                'no_asset_mobil'=>$data->no_asset_mobil,
                'kode_sopir'=>$data->kode_sopir,
                'kode_pemilik'=>$data->kode_pemilik,
                'uang_jalan'=>$data->uang_jalan,
                'bbm'=>$data->bbm,
                'bpa'=>$data->bpa,
                'honor'=>$data->honor,
                'biaya_lain'=>$data->biaya_lain,
                'trucking'=>$data->trucking,
            );
        }
        return response()->json($output);
    }

    public function updateAjax2(Request $request)
    {   
        $cek_nospb = TruckingDetail::where('no_trucking', $request->no_trucking)->pluck('no_spb','no_spb');

        $cek_detail_byr = PembayaranDetail::where('no_spb',$request->no_spb)->first();
        if($cek_detail_byr != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di EDIT, SPB '.$cek_detail_byr->no_spb.' ada dalam detail no BYR '.$cek_detail_byr->no_pembayaran.' Silahakan hapus terlebih dulu dari detail BYR.'
            ];
            return response()->json($message);
        }

        $cek_detail_hbu = HasilbagiDetail::where('no_spb',$request->no_spb)->first();

        if($cek_detail_hbu != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di EDIT, SPB '.$cek_detail_hbu->no_spb.' ada dalam detail no HBU '.$cek_detail_hbu->no_hasilbagi.' Silahakan hapus terlebih dulu dari detail HBU.'
            ];
            return response()->json($message);
        }
        
        Spb::find($request->no_spb)->update($request->all());

        if($request->nama_pemilik != 'GEMILANG UTAMA INTERNASIONAL, PT'){
            $update_sopir = Spb::find($request->no_spb);
            $update_sopir->kode_sopir = $request->sopir;
            $update_sopir->save();
        }

            $update_tarif = [
                'uang_jalan'=>$request->uang_jalan,
            ];

            $tarif_update = TruckingDetail::where('no_spb', $request->no_spb)->update($update_tarif);

            $cek_truckingdetail = TruckingDetail::where('no_trucking', $request->no_trucking)->get();

            $total_uang_jalan = 0;
            foreach ($cek_truckingdetail as $row){
                $total_uang_jalan += $row->uang_jalan;
            }

            $update_trucking = Trucking::find($request->no_trucking);
            $update_trucking->gt_uang_jalan = $total_uang_jalan;
            $update_trucking->save();

            $cekspb = Spb::find($request->no_spb);
            $cekspb2 = Spb::where('no_joborder',$cekspb->no_joborder)->where('tgl_kembali',null)->first();
            if($cekspb2 == null){
                $status_job = Trucking::where('no_joborder',$cekspb->no_joborder)->first();
                $status_job->status_kembali = "TRUE";
                $status_job->save();
            }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_trucking()
    {   
        $no_trucking = request()->no_trucking;
        $no_spb = request()->no_spb;
        $kode_container = request()->kode_container;
        $get_trucking = Trucking::find($no_trucking);

        $cek_nospb = TruckingDetail::where('no_trucking', $no_trucking)->pluck('no_spb','no_spb');

        $cek_detail_byr = PembayaranDetail::where('no_spb',$no_spb)->first();
        if($cek_detail_byr != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di HAPUS, SPB '.$cek_detail_byr->no_spb.' ada dalam detail no BYR '.$cek_detail_byr->no_pembayaran.' Silahakan hapus terlebih dulu dari detail BYR.'
            ];
            return response()->json($message);
        }

        $cek_detail_hbu = HasilbagiDetail::where('no_spb',$no_spb)->first();

        if($cek_detail_hbu != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di HAPUS, SPB '.$cek_detail_hbu->no_spb.' ada dalam detail no HBU '.$cek_detail_hbu->no_hasilbagi.' Silahakan hapus terlebih dulu dari detail HBU.'
            ];
            return response()->json($message);
        }

        $data = TruckingDetail::where('no_trucking',$no_trucking)->where('no_spb',$no_spb)->first();
        $data->delete();
        
        
        $data_jor = JobrequestDetail::where('kode_container', $kode_container)->first();
        $data_jor->delete();

        $hitung_con = JobrequestDetail::where('no_req_jo', $get_trucking->no_req_jo)->get();

        $total_con = count($hitung_con);

        $update_con = [
            'total_item'=>$total_con,
        ];

        $jor_update = JoborderDetail::where('no_req_jo', $get_trucking->no_req_jo)->update($update_con);
        $jor_update2 = Joborder::where('no_joborder', $get_trucking->no_joborder)->update($update_con);


        $data_spb = Spb::find($no_spb)->delete();

        $cek_truckingdetail = TruckingDetail::where('no_trucking', $no_trucking)->get();

        $total_uang_jalan = 0;
        foreach ($cek_truckingdetail as $row){
            $total_uang_jalan += $row->uang_jalan;
        }

        $update_trucking = Trucking::find($no_trucking);
        $update_trucking->gt_uang_jalan = $total_uang_jalan;
        $update_trucking->save();

        $total_item = count($cek_truckingdetail);

        $update_item = [
            'total_item'=>$total_item,
        ];

        $item_update = Trucking::where('no_trucking', $no_trucking)->update($update_item);

        $cekspb = Spb::find($no_spb);
        $cekspb2 = Spb::where('no_joborder',$update_trucking->no_joborder)->where('tgl_kembali',null)->first();
        if($cekspb2 == null){
            $update_trucking->status_kembali = "TRUE";
            $update_trucking->save();
        }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$no_trucking.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
