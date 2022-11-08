<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\PembayaranDetail;
use App\Models\Pembayaran;
use App\Models\Spb;
use App\Models\SpbDetail;
use App\Models\JobrequestDetail;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Pemilik;
use App\Models\TruckingDetail;
use App\Models\TruckingnonDetail;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use DB;
use Carbon;

class PembayarandetailController extends Controller
{
    public function index()
    {
        $create_url = route('pembayarandetail.create');

        return view('admin.pembayarandetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(PembayaranDetail::with('mobil','sopir','gudangdetail','customer')->where('no_pembayaran',request()->id)->orderBy('tgl_kembali','desc'))
           ->make(true);
    }

    public function getspb()
    {   
        $no_pembayaran = request()->no_pembayaran;
        $get_pembayaran = Pembayaran::find($no_pembayaran);
        $total_item = $get_pembayaran->total_item;
        if($total_item == null){
            $total_item = 0;
        }

        // $cekpembayaran = PembayaranDetail::where('no_pembayaran', $no_pembayaran)->first();
        // if($cekpembayaran != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Update',
        //         'message' => 'Data sudah ada.'
        //     ];
        //     return response()->json($message);
        // }

        $cek_nospb = PembayaranDetail::where('no_pembayaran', $no_pembayaran)->pluck('no_spb','no_spb');

        $kode_pemilik = request()->kode_pemilik;
        $dari = request()->dari;
        $sampai = request()->sampai;

        $getspb = Spb::where('kode_pemilik', $kode_pemilik)->where('status_spb', 1)->whereBetween('tgl_kembali', array($dari, $sampai))->get();

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
                    ->where('spb.status_spb',1)
                    ->whereNotIn('spb.no_spb',$cek_nospb)
                    ->first();

                if($gettrucking != null){
                    $cek_jobreq = $gettrucking->no_joborder;
                    $cek_container = $gettrucking->kode_container;
                    $get_jobreq = JobrequestDetail::where('no_joborder',$cek_jobreq)->where('kode_container',$cek_container)->first();

                    if($gettrucking != null){  
                        $tabel_baru = [
                            'no_pembayaran'=>$no_pembayaran,
                            'no_joborder'=>$gettrucking->no_joborder,
                            'no_spb'=>$gettrucking->no_spb,
                            'tgl_spb'=>$gettrucking->tgl_spb,
                            'tgl_kembali'=>$gettrucking->tgl_kembali,
                            'kode_mobil'=>$gettrucking->kode_mobil,
                            'kode_sopir'=>$gettrucking->kode_sopir,
                            'kode_container'=>$gettrucking->kode_container,
                            'kode_gudang'=>$gettrucking->kode_gudang,
                            'tarif'=>$gettrucking->tarif_trucking,
                            'uang_jalan'=>$gettrucking->uang_jalan,
                            'sisa'=>($gettrucking->tarif_trucking - $gettrucking->uang_jalan),
                            'dari'=>$get_jobreq->dari,
                            'tujuan'=>$get_jobreq->tujuan,
                        ];

                        $createdetail = PembayaranDetail::create($tabel_baru);
                    }
                }
            }
        }


        $getspbnon = TruckingnonDetail::where('kode_pemilik', $kode_pemilik)->where('status_spbnon', 1)->whereBetween('tanggal_kembali', array($dari, $sampai))->get();

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
                    ->where('truckingnoncontainer_detail.status_spbnon',1)
                    ->whereNotIn('truckingnoncontainer_detail.no_spb',$cek_nospb)
                    ->first();

                if($gettruckingnon != null){
                    $tabel_baru2 = [
                        'no_pembayaran'=>$no_pembayaran,
                        'no_joborder'=>$gettruckingnon->no_joborder,
                        'no_spb'=>$gettruckingnon->no_spb,
                        'tgl_spb'=>$gettruckingnon->tanggal_spb,
                        'tgl_kembali'=>$gettruckingnon->tanggal_kembali,
                        'kode_mobil'=>$gettruckingnon->kode_mobil,
                        'kode_sopir'=>$gettruckingnon->kode_sopir,
                        'kode_container'=>'-',
                        'kode_gudang'=>'-',
                        'tarif'=>$gettruckingnon->tarif_gajisopir,
                        'uang_jalan'=>$gettruckingnon->uang_jalan,
                        'sisa'=>($gettruckingnon->tarif_gajisopir - $gettruckingnon->uang_jalan),
                        'dari'=>$gettruckingnon->dari,
                        'tujuan'=>$gettruckingnon->tujuan,
                    ];

                    $createdetail = PembayaranDetail::create($tabel_baru2);
                }
            }
        }

        $getdata = PembayaranDetail::where('no_pembayaran', $no_pembayaran)->first();

        if ($getdata == null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data tidak ada.'
            ];
            return response()->json($message);
        }

        $gettotal = PembayaranDetail::where('no_pembayaran', $no_pembayaran)->get();
        $totalitem = count($gettotal);

        $total = [
            'total_item'=>$totalitem,
        ];

        $update_total = Pembayaran::where('no_pembayaran', $no_pembayaran)->update($total);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data berhasil di simpan.'
        ];
        return response()->json($message);
    }

    public function hapusall()
    {
      $cek_pembayaran = Pembayaran::find(request()->id);
      $cek_status = $cek_pembayaran->status;
      if($cek_status == 'POSTED'){  
          $message = [
              'success' => false,
              'title' => 'Simpan',
              'message' => 'Status No. Hasil Bagi: '.$cek_pembayaran->no_pembayaran.' sudah POSTED! Pastikan Anda tidak membuka menu PEMBAYARAN lebih dari 1',
          ];
          return response()->json($message);
      }
          $no_pembayaran = request()->id;
          $data_detail = PembayaranDetail::where('no_pembayaran',$no_pembayaran)->delete();
          
            $total = [
                'total_item'=>0,
            ];

            $update_total = Pembayaran::where('no_pembayaran', $no_pembayaran)->update($total);

          $message = [
              'success' => true,
              'title' => 'Simpan',
              'message' => 'Data telah di hapus.',
          ];

          return response()->json($message);
    }

    public function hapus_pembayaran()
    {   
        $no_pembayaran = request()->no_pembayaran;
        $no_spb = request()->no_spb;

        $data = PembayaranDetail::where('no_pembayaran',$no_pembayaran)->where('no_spb',$no_spb)->first();
        $data->delete();

        $cek_pembayarandetail = PembayaranDetail::where('no_pembayaran', $no_pembayaran)->get();

        $total_item = count($cek_pembayarandetail);

        $update_item = [
            'total_item'=>$total_item,
        ];

        $item_update = Pembayaran::where('no_pembayaran', $no_pembayaran)->update($update_item);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$no_pembayaran.'] telah dihapus.'
        ];
        return response()->json($message);
    }
}
