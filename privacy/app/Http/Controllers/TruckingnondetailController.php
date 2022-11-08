<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\TruckingnonDetail;
use App\Models\Truckingnon;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Port;
use App\Models\Spbnon;
use App\Models\SpbnonDetail;
use App\Models\Pemilik;
use App\Models\PemilikDetail;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use App\Models\TransaksiSetup;
use App\Models\PembayaranDetail;
use App\Models\HasilbagiDetail;
use App\Models\Vendor;
use DB;
use Carbon;

class TruckingnondetailController extends Controller
{
    public function index()
    {
        $create_url = route('truckingnondetail.create');

        return view('admin.truckingnondetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(TruckingnonDetail::select('truckingnoncontainer_detail.*','u5611458_db_pusat.vendor.nama_vendor')->leftjoin('u5611458_db_pusat.vendor','truckingnoncontainer_detail.kode_pemilik','=','u5611458_db_pusat.vendor.id')->with('mobil','sopir')->where('no_truckingnon',request()->id)->orderBy('truckingnoncontainer_detail.created_at','desc'))->make(true);
    }

    public function getDataspb(){
        $data = SpbnonDetail::where('no_spbnon',request()->id)->orderBy('created_at','desc')->get();
        return response()->json($data);
    }

    public function get_aset()
    {
        $kodemobil = request()->kode_mobil;
        $aset = Mobil::find($kodemobil);
        $hasil = [
            'no_aset'=>$aset->no_asset_mobil,
        ];
        return response()->json($hasil);
    }

    public function pemilik()
    {
        $pemilik_detail = PemilikDetail::where('kode_mobil',request()->kode_mobil)->first();
        $pemilik = Vendor::where('id',$pemilik_detail->kode_pemilik)->first();
        $cekspb = TruckingnonDetail::where('no_spb',request()->no_spb)->first();

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
        $truckingnondetail = TruckingnonDetail::where('no_truckingnon',request()->no_truckingnon)->orderBy('created_at','desc')->first();
        if($truckingnondetail != null){
            $last_spb = $truckingnondetail->no_spb;
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

        $getall = SpbnonDetail::select('no_spbnon')->get();
        $cekall = TruckingnonDetail::select('no_spb','no_truckingnon')->whereNotIn('no_spb',$getall)->first();
        if ($cekall != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Detail No. SPB ['.$cekall->no_spb.'] pada ['.$cekall->no_truckingnon.'] harus diisi terlebih dahulu.'
            ];
            return response()->json($message);
        } 

        
        $truckingnon = Truckingnon::where('no_truckingnon',request()->no_truckingnon)->orderBy('created_at','desc')->first();

        $kode_company = auth()->user()->kode_company;
        $getkode = TransaksiSetup::where('kode_setup','010')->first();
        $kode_noncontainer = $getkode->kode_transaksi;
        $tahun = Carbon\Carbon::parse($truckingnon->tanggal_truckingnon)->format('y');
        $bulan = Carbon\Carbon::parse($truckingnon->tanggal_truckingnon)->format('m');

        $spbnon1 = $kode_company.$kode_noncontainer.$tahun.$bulan;
        $cek_spbnon = SpbnonDetail::where(DB::raw('LEFT(no_spbnon,11)'),$spbnon1)->orderBy('no_spbnon','desc')->first();

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

        $output = array(
            'hasil'=>$hasil,
        );
        return response()->json($output);
    }

    public function store(Request $request)
    {
        $truckingnondetail = TruckingnonDetail::create($request->all());

        if($request->nama_pemilik != 'GEMILANG UTAMA INTERNASIONAL, PT'){
            $get_detail = TruckingnonDetail::where('no_spb',$request->no_spb)->first();
            $update_sopir = TruckingnonDetail::find($get_detail->id);
            $update_sopir->kode_sopir = $request->sopir;
            $update_sopir->save();
        }

        $truckingnon = Truckingnon::find($request->no_truckingnon);
                    
        $hitung = TruckingnonDetail::where('no_truckingnon', $request->no_truckingnon)->get();
        $total_item = count($hitung);

        $gt_tarif = 0;
        $gt_uang_jalan = 0;
        $gt_bbm = 0;

        foreach ($hitung as $row){
            $gt_tarif += $row->tarif_gajisopir;
            $gt_uang_jalan += $row->uang_jalan;
            $gt_bbm += $row->bbm;
        }

        $update_tarif = [
            'gt_tarif'=>$gt_tarif,
            'gt_uang_jalan'=>$gt_uang_jalan,
            'gt_bbm'=>$gt_bbm,
        ];

        $update_truckingnon = Truckingnon::where('no_truckingnon', $request->no_truckingnon)->update($update_tarif);

        $truckingnon->total_item = $total_item;
        $truckingnon->save();

        $cekspb2 = TruckingnonDetail::where('no_truckingnon',$request->no_truckingnon)->where('tanggal_kembali',null)->first();
        if($cekspb2 != null){
            $status_job = Truckingnon::where('no_truckingnon',$request->no_truckingnon)->first();
            $status_job->status_kembali = "FALSE";
            $status_job->save();
        }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function store2(Request $request)
    {
        $spbnondetail = SpbnonDetail::where('no_spbnon', $request->no_spbnon)->where('kode_item', $request->kode_item)->get();

        $leng = count($spbnondetail);

        if($leng > 0){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Item Sudah Ada'
            ];
            return response()->json($message);
        }
        else{
            $spbnondetail = SpbnonDetail::create($request->all());
                    
            $hitung = SpbnonDetail::where('no_spbnon', $request->no_spbnon)->get();
            $total_berat = 0;

            foreach ($hitung as $row){
                $total_berat += $row->total_berat;
            }

            $total_item = count($hitung);

            $update_berat = [
                'total_berat'=>$total_berat,
                'total_item'=>$total_item,
            ];

            $berat_update = TruckingnonDetail::where('no_spb', $request->no_spbnon)->update($update_berat);

            $get_notruck = TruckingnonDetail::where('no_spb', $request->no_spbnon)->first();
            $no_truckingnon = $get_notruck->no_truckingnon;
            $trucknon = Truckingnon::find($no_truckingnon);
            $update_notruck = [
                'no_joborder'=>$trucknon->no_joborder,
            ];
            $spbnon = SpbnonDetail::where('no_spbnon', $request->no_spbnon)->update($update_notruck);

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah disimpan'
            ];
            return response()->json($message);
        }
    }

    public function edit_truckingnon()
    {
        $no_truckingnon = request()->no_truckingnon;
        $no_spbnon = request()->no_spbnon;

        $data = TruckingnonDetail::with('pemilik')->where('no_truckingnon',$no_truckingnon)->where('no_spb',$no_spbnon)->first();
        $output = array(
            'no_truckingnon'=>$data->no_truckingnon,
            'no_spb'=>$data->no_spb,
            // 'no_spb_manual'=>$data->no_spb_manual,
            'tanggal_spb'=>$data->tanggal_spb,
            // 'tanggal_kembali'=>$data->tanggal_kembali,
            'total_berat'=>$data->total_berat,
            // 'nama_pemilik'=>$data->pemilik->nama_pemilik,
            // 'kode_pemilik'=>$data->kode_pemilik,
            // 'kode_mobil'=>$data->kode_mobil,
            // 'kode_sopir'=>$data->kode_sopir,
            // 'tarif_gajisopir'=>$data->tarif_gajisopir,
            // 'uang_jalan'=>$data->uang_jalan,
            // 'bbm'=>$data->bbm,
            // 'dari'=>$data->dari,
            // 'tujuan'=>$data->tujuan,
            'id'=>$data->id,
        );
        return response()->json($output);
    }

    public function edit_truckingnon2()
    {
        $no_truckingnon = request()->no_truckingnon;
        $no_spbnon = request()->no_spbnon;

        $data = TruckingnonDetail::where('no_truckingnon',$no_truckingnon)->where('no_spb',$no_spbnon)->first();
        if($data->kode_pemilik != null){
            $output = array(
                'no_truckingnon'=>$data->no_truckingnon,
                'no_spb'=>$data->no_spb,
                'no_spb_manual'=>$data->no_spb_manual,
                'tanggal_spb'=>$data->tanggal_spb,
                'tanggal_kembali'=>$data->tanggal_kembali,
                'total_berat'=>$data->total_berat,
                'nama_pemilik'=>$data->nama_vendor,
                'kode_pemilik'=>$data->kode_pemilik,
                'kode_mobil'=>$data->kode_mobil,
                'no_asset_mobil'=>$data->no_asset_mobil,
                'kode_sopir'=>$data->kode_sopir,
                'tarif_gajisopir'=>$data->tarif_gajisopir,
                'uang_jalan'=>$data->uang_jalan,
                'bbm'=>$data->bbm,
                'dari'=>$data->dari,
                'tujuan'=>$data->tujuan,
                'id'=>$data->id,
            );
        }else{
            $output = array(
                'no_truckingnon'=>$data->no_truckingnon,
                'no_spb'=>$data->no_spb,
                'no_spb_manual'=>$data->no_spb_manual,
                'tanggal_spb'=>$data->tanggal_spb,
                'tanggal_kembali'=>$data->tanggal_kembali,
                'total_berat'=>$data->total_berat,
                'nama_pemilik'=>$data->kode_pemilik,
                'kode_pemilik'=>$data->kode_pemilik,
                'kode_mobil'=>$data->kode_mobil,
                'no_asset_mobil'=>$data->no_asset_mobil,
                'kode_sopir'=>$data->kode_sopir,
                'tarif_gajisopir'=>$data->tarif_gajisopir,
                'uang_jalan'=>$data->uang_jalan,
                'bbm'=>$data->bbm,
                'dari'=>$data->dari,
                'tujuan'=>$data->tujuan,
                'id'=>$data->id,
            );
        }
        
        return response()->json($output);
    }

    public function hapus_truckingnon()
    {   
        $no_truckingnon = request()->no_truckingnon;
        $no_spbnon = request()->no_spbnon;
    
        $cek_nospb = TruckingnonDetail::where('no_truckingnon', $no_truckingnon)->pluck('no_spb','no_spb');

        // $cek_detail_byr = PembayaranDetail::where('no_spb',$no_spbnon)->first();
        // if($cek_detail_byr != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Update',
        //         'message' => 'Data gagal di HAPUS, SPB '.$cek_detail_byr->no_spb.' ada dalam detail no BYR '.$cek_detail_byr->no_pembayaran.' Silahakan hapus terlebih dulu dari detail BYR.'
        //     ];
        //     return response()->json($message);
        // }

        $cek_detail_hbu = HasilbagiDetail::where('no_spb',$no_spbnon)->first();

        if($cek_detail_hbu != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di HAPUS, SPB '.$cek_detail_hbu->no_spb.' ada dalam detail no HBU '.$cek_detail_hbu->no_hasilbagi.' Silahakan hapus terlebih dulu dari detail HBU.'
            ];
            return response()->json($message);
        }
        
        $dataspbnon = Spbnon::find($no_spbnon);
        if($dataspbnon != null){
            $dataspbnon->delete();
        }   

        $data = TruckingnonDetail::where('no_truckingnon',$no_truckingnon)->where('no_spb',$no_spbnon)->first();
        $data->delete();

        $hitung = TruckingnonDetail::where('no_truckingnon', $no_truckingnon)->get();
        $leng = count($hitung);

        $update_truckingnon = Truckingnon::where('no_truckingnon', $no_truckingnon)->first();
        $update_truckingnon->total_item = $leng;
        $update_truckingnon->save();

        $cekspb2 = TruckingnonDetail::where('no_truckingnon',$no_truckingnon)->where('tanggal_kembali',null)->first();
        if($cekspb2 == null){
            $status_job = Truckingnon::where('no_truckingnon',$no_truckingnon)->first();
            $status_job->status_kembali = "TRUE";
            $status_job->save();
        }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$no_truckingnon.'] telah dihapus.'
        ];
        return response()->json($message);
    }

    public function updateAjax(Request $request)
    {
        $cek_nospb = TruckingnonDetail::where('no_truckingnon', $request->no_truckingnon)->pluck('no_spb','no_spb');

        // $cek_detail_byr = PembayaranDetail::where('no_spb',$request->no_spb)->first();
        // if($cek_detail_byr != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Update',
        //         'message' => 'Data gagal di EDIT, SPB '.$cek_detail_byr->no_spb.' ada dalam detail no BYR '.$cek_detail_byr->no_pembayaran.' Silahakan hapus terlebih dulu dari detail BYR.'
        //     ];
        //     return response()->json($message);
        // }

        $cek_detail_hbu = HasilbagiDetail::where('no_spb',$request->no_spb)->first();

        if($cek_detail_hbu != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di EDIT, SPB '.$cek_detail_hbu->no_spb.' ada dalam detail no HBU '.$cek_detail_hbu->no_hasilbagi.' Silahakan hapus terlebih dulu dari detail HBU.'
            ];
            return response()->json($message);
        }
        
        $cek_pemilik = Truckingnon::find($request->no_truckingnon);

        $satuan = TruckingnonDetail::find($request->id)->update($request->all());

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function updateAjax3(Request $request)
    {
        $cek_nospb = TruckingnonDetail::where('no_truckingnon', $request->no_truckingnon)->pluck('no_spb','no_spb');
        
        // $cek_detail_byr = PembayaranDetail::where('no_spb',$request->no_spb)->first();
        // if($cek_detail_byr != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Update',
        //         'message' => 'Data gagal di EDIT, SPB '.$cek_detail_byr->no_spb.' ada dalam detail no BYR '.$cek_detail_byr->no_pembayaran.' Silahakan hapus terlebih dulu dari detail BYR.'
        //     ];
        //     return response()->json($message);
        // }

        $cek_detail_hbu = HasilbagiDetail::where('no_spb',$request->no_spb)->first();

        if($cek_detail_hbu != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data gagal di EDIT, SPB '.$cek_detail_hbu->no_spb.' ada dalam detail no HBU '.$cek_detail_hbu->no_hasilbagi.' Silahakan hapus terlebih dulu dari detail HBU.'
            ];
            return response()->json($message);
        }
        
        $cek_pemilik = Truckingnon::find($request->no_truckingnon);

        $get_detail = TruckingnonDetail::where('no_spb',$request->no_spb)->first();
        $satuan = TruckingnonDetail::find($get_detail->id)->update($request->all());
        
        
        $get_detail = TruckingnonDetail::where('no_spb',$request->no_spb)->first();
        $update_sopir = TruckingnonDetail::find($get_detail->id);
        $update_sopir->kode_sopir = $request->kode_sopir;
        $update_sopir->save();
        
        $hitung = TruckingnonDetail::where('no_truckingnon', $request->no_truckingnon)->get();
        $leng = count($hitung);

        $gt_tarif = 0;
        $gt_uang_jalan = 0;
        $gt_bbm = 0;

        foreach ($hitung as $row){
            $gt_tarif += $row->tarif_gajisopir;
            $gt_uang_jalan += $row->uang_jalan;
            $gt_bbm += $row->bbm;
        }

        $update_tarif = [
            'gt_tarif'=>$gt_tarif,
            'gt_uang_jalan'=>$gt_uang_jalan,
            'gt_bbm'=>$gt_bbm,
        ];

        $update_truckingnon = Truckingnon::where('no_truckingnon', $request->no_truckingnon)->update($update_tarif);

        $update_truckingnon = Truckingnon::where('no_truckingnon', $request->no_truckingnon)->first();
        $update_truckingnon->total_item = $leng;
        $update_truckingnon->save();

        $cekspb2 = TruckingnonDetail::where('no_truckingnon',$request->no_truckingnon)->where('tanggal_kembali',null)->first();
        if($cekspb2 == null){
            $status_job = Truckingnon::where('no_truckingnon',$request->no_truckingnon)->first();
            $status_job->status_kembali = "TRUE";
            $status_job->save();
        }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function edit_spbnon()
    {
        $no_spbnon = request()->no_spbnon;
        $kode_item = request()->kode_item;
        
        $spbnon = SpbnonDetail::where('no_spbnon',$no_spbnon)->where('kode_item',$kode_item)->first();

        $update_spb = [
            'qty'=>request()->qty,
            'berat_satuan'=>request()->berat,
            'total_berat'=>request()->total,
            'keterangan'=>request()->keterangan,
        ];

        $update = SpbnonDetail::where('no_spbnon', $no_spbnon)->where('kode_item',$kode_item)->update($update_spb);
        
        $hitung = SpbnonDetail::where('no_spbnon', $no_spbnon)->get();
        $total_berat = 0;

        foreach ($hitung as $row){
            $total_berat += $row->total_berat;
        }

        $total_item = count($hitung);

        $update_berat = [
            'total_berat'=>$total_berat,
        ];

        $berat_update = TruckingnonDetail::where('no_spb', $no_spbnon)->update($update_berat);
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function hapus_spbnon()
    {   
        $no_spbnon = request()->no_spbnon;
        $kode_item = request()->kode_item;
        $data = SpbnonDetail::where('no_spbnon',$no_spbnon)->where('kode_item',$kode_item)->first();
        $data->delete();

        $hitung = SpbnonDetail::where('no_spbnon', $no_spbnon)->get();
        $total_berat = 0;

        foreach ($hitung as $row){
            $total_berat += $row->total_berat;
        }

        $total_item = count($hitung);

        $update_berat = [
            'total_berat'=>$total_berat,
            'total_item'=>$total_item,
        ];

        $berat_update = TruckingnonDetail::where('no_spb', $no_spbnon)->update($update_berat);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$data->kode_item.'] telah dihapus.'
        ];
        return response()->json($message);
    }
}
