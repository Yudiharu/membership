<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Trucking;
use App\Models\Truckingnon;
use App\Models\Customer;
use App\Models\TruckingnonDetail;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Joborder;
use App\Models\JoborderDetail;
use App\Models\Pemilik;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Port;
use App\Models\Spbnon;
use App\Models\SpbnonDetail;
use App\Models\Vendor;
use App\Models\PembayaranDetail;
use App\Models\HasilbagiDetail;
use Carbon;
use DB;
use PDF;

class TruckingnonController extends Controller
{
    public function index()
    {
        $create_url = route('truckingnon.create');
        $Company = Company::pluck('nama_company','kode_company');
        $no_jo = JoborderDetail::pluck('no_joborder','no_joborder');
        // $Joborder = Joborder::whereNotIn('no_joborder',$no_truckingnon)->pluck('no_joborder','no_joborder');
        
        $Joborder = Joborder::where('job_order.type_jo','4')->where('job_order.status','<>', '1')->pluck('no_joborder','no_joborder');

        $Customer = Customer::pluck('nama_customer','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.truckingnon.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer','Company','Joborder'));
    }

    public function getkode(){
        $get_inv = Truckingnon::get();
        $leng = count($get_inv);

        $data = array();

        // foreach ($get_inv as $rowdata){
        //     $kode_customer = $rowdata->kode_customer;

        //     $data[] = array(
        //         'kode_customer'=>$kode_customer,
        //     );
        // }

        // for ($i = 0; $i < $leng; $i++) { 
        //     $cek_cust = Customer::where('kode_customer', $data[$i]['kode_customer'])->first();

        //     if($cek_cust != null){
        //         $id_cust = $cek_cust->id;

        //         $tabel_baru_cust = [
        //             'kode_customer'=>$id_cust,
        //         ];
        //         $update_cust = Truckingnon::where('kode_customer', $data[$i]['kode_customer'])->update($tabel_baru_cust);   
        //     }
        // }


        $get_inv = TruckingnonDetail::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            // $kode_sopir = $rowdata->kode_sopir;
            $kode_pemilik = $rowdata->kode_pemilik;

            $data[] = array(
                // 'kode_sopir'=>$kode_sopir,
                'kode_pemilik'=>$kode_pemilik,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            // $cek_sopir = Sopir::where('kode_sopir', $data[$i]['kode_sopir'])->first();

            // if($cek_sopir != null){
            //     $id_sopir = $cek_sopir->id;

            //     $tabel_baru_sopir = [
            //         'kode_sopir'=>$id_sopir,
            //     ];
            //     $update_sopir = TruckingnonDetail::where('kode_sopir', $data[$i]['kode_sopir'])->update($tabel_baru_sopir);   
            // }


            $cek_pemilik = Vendor::where('kode_vendor', $data[$i]['kode_pemilik'])->first();

            if($cek_pemilik != null){
                $id_pemilik = $cek_pemilik->id;

                $tabel_baru_pemilik = [
                    'kode_pemilik'=>$id_pemilik,
                ];
                $update_sopir = TruckingnonDetail::where('kode_pemilik', $data[$i]['kode_pemilik'])->update($tabel_baru_pemilik);   
            }
        }


        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Kode Customer Berhasil di Update.'
        ];
        
        return response()->json($message);
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Truckingnon::with('customer')->orderBy('created_at','desc'))->make(true);
    }

    public function bigprint()
    {
        $no_truckingnon = request()->id;
        $truckingnon = Truckingnon::find($no_truckingnon);
        $part = array();
        $a = 0;

        if ($truckingnon->total_item > 80) {
            $jumlah = $truckingnon->total_item / 80;
            $parted = floor($jumlah);

            for ($i = 0; $i <= $parted; $i++) { 
                $a += 1;
                $part[] = 'Part '.$a;
            }
            return response()->json(['options'=>$part]);
        }
    }

    public function export3(){
        $request = $_GET['no_truckingnon'];
        $part = $_GET['part'];

        $truckingnon = Truckingnon::with('customer')->find($request);
        $nama_customer = $truckingnon->customer->nama_customer;
        $truckingnondetail = TruckingnonDetail::where('no_truckingnon',$request)->get();

        $spbnondetail = TruckingnonDetail::with('sopir')->Join('spbnon_detail', 'spbnon_detail.no_spbnon', '=', 'truckingnoncontainer_detail.no_spb')->where('spbnon_detail.no_joborder',$truckingnon->no_joborder)->get();
        
        // dd($spbnondetail);
        $leng = count($truckingnondetail);
        $leng_spbnc = count($spbnondetail);

        $bagian = 0;
        if ($truckingnon->total_item > 80) {
            $jumlah = $truckingnon->total_item / 80;
            $parted = floor($jumlah);

            for ($i = 0; $i <= $parted; $i++) { 
                $bagian += 1;
            }
        }

        $parts = $part + 1;
        $urutan = $bagian - $parts;
        $angka = 0;

        if ($urutan == 0) {
            $lewat = ($parts - 1) * 80;
            $sisa = $leng_spbnc - $lewat;
        }else {
            $lewat = ($parts - 1) * 80;
            $sisa = 80;
        }

        $spbnondetail = TruckingnonDetail::with('sopir')->Join('spbnon_detail', 'spbnon_detail.no_spbnon', '=', 'truckingnoncontainer_detail.no_spb')->where('spbnon_detail.no_joborder',$truckingnon->no_joborder)->skip($lewat)->take($sisa)->get();

        $get_jo = $truckingnon->no_joborder;
        $jo = Joborder::find($get_jo);

        $dt = Carbon\Carbon::now();
        $date_now = Carbon\Carbon::parse($dt)->format('d/m/Y');

        $tgl = $truckingnon->tanggal_truckingnon;
        $date=date_create($tgl);

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        
        $pengirim = Signature::where('jabatan','DIREKTUR')->first();

        $pdf = PDF::loadView('/admin/truckingnon/pdf', compact('pengirim','truckingnondetail','truckingnon', 'date_now','date','jo','request','nama','nama2','dt','get_jo','leng','spbnondetail','leng_spbnc','nama_customer'));
        $pdf->setPaper([15, -40, 684, 458], 'portrait');

        return $pdf->stream('Cetak SPBNC dengan No JO '.$get_jo.'.pdf');
    }

    public function export2(){
        $request = $_GET['no_truckingnon'];
        $truckingnon = Truckingnon::with('customer')->find($request);
        $nama_customer = $truckingnon->customer->nama_customer;

        $truckingnondetail = TruckingnonDetail::where('no_truckingnon',$request)->get();
        $spbnondetail = TruckingnonDetail::with('sopir')->Join('spbnon_detail', 'spbnon_detail.no_spbnon', '=', 'truckingnoncontainer_detail.no_spb')->where('spbnon_detail.no_joborder',$truckingnon->no_joborder)->get();
        // dd($spbnondetail);
        $leng = count($truckingnondetail);
        $leng_spbnc = count($spbnondetail);

        $get_jo = $truckingnon->no_joborder;
        $jo = Joborder::find($get_jo);

        $dt = Carbon\Carbon::now();
        $date_now = Carbon\Carbon::parse($dt)->format('d/m/Y');

        $tgl = $truckingnon->tanggal_truckingnon;
        $date=date_create($tgl);

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;

        $pdf = PDF::loadView('/admin/truckingnon/pdf', compact('truckingnondetail','truckingnon', 'date_now','date','jo','request','nama','nama2','dt','get_jo','leng','spbnondetail','leng_spbnc','nama_customer'));
        $pdf->setPaper([15, -40, 684, 458], 'portrait');

        return $pdf->stream('Cetak SPBNC dengan No JO '.$get_jo.'.pdf');
    }

    public function detail($truckingnon)
    {
        $truckingnon = Truckingnon::find($truckingnon);
        $no_truckingnon = $truckingnon->no_truckingnon;

        $tgl = $truckingnon->tanggal_truckingnon;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
        //     alert()->success('Status POSTED / Periode Telah CLOSED: '.$tgl,'GAGAL!')->persistent('Close');
        //     return redirect()->back();
        // }

        $data = Truckingnon::find($no_truckingnon);
        
        $Spbnon = Spbnon::pluck('no_spbnon','no_spbnon');  
        $truckingnondetail = TruckingnonDetail::with('mobil','sopir','port','pemilik')->where('no_truckingnon', $truckingnon->no_truckingnon)->orderBy('created_at','desc')->get();

        $list_url= route('truckingnon.index');
                    
        $Pemilik = Pemilik::join('u5611458_db_pusat.vendor','pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->pluck('nama_vendor','id');    
        $Mobil = Mobil::where('status_mobil','Aktif')->pluck('nopol','kode_mobil');
        $Asmobil = Mobil::whereNotNull('no_asset_mobil')->pluck('no_asset_mobil','no_asset_mobil');

        $Sopir = Sopir::pluck('nama_sopir','id');


        $Port = Port::pluck('nama_port','id');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.truckingnondetail.index', compact('truckingnon','truckingnondetail','list_url','Mobil','Sopir','Port','period','nama_lokasi','nama_company','Spbnon','Pemilik','no_truckingnon','Asmobil'));
    }

    public function getdata()
    {
         $joborder = Joborder::find(request()->id);
            $output = array(
                'kode_customer'=>$joborder->kode_customer,
            );
        return response()->json($output);
    }

    public function getdata2()
    {
         $joborder = Joborder::find(request()->id);

            $output = array(
                'kode_customer'=>$joborder->kode_customer,
            );
       
        return response()->json($output);
    }

    public function Showdetail()
    {
        $total_harga = 0;
        $total_harga2 = 0;

        $truckingnondetail = TruckingnonDetail::with('mobil','sopir','port','port1','pemilik')->where('no_truckingnon',request()->id)->orderBy('created_at', 'desc')->get();

        $output = array();

        foreach ($truckingnondetail as $row){
            $total_harga += $row->tarif_gajisopir;
            $total_harga2 += $row->uang_jalan;
        }
        
            foreach($truckingnondetail as $row)
            {
                
                $no_spb = $row->no_spb;
                $no_spb_manual = $row->no_spb_manual;
                $tanggal_spb = $row->tanggal_spb;
                $tanggal_kembali = $row->tanggal_kembali;
                $total_berat = $row->total_berat;

                $cek_mobil = Mobil::find($row->kode_mobil);
                if($cek_mobil != null){
                    $kode_mobil = $row->mobil->nopol;
                }else{
                    $kode_mobil = $row->kode_mobil;
                }

                $cek_sopir = Sopir::find($row->kode_sopir);
                if($cek_sopir != null){
                    $kode_sopir = $row->sopir->nama_sopir;
                }else{
                    $kode_sopir = $row->kode_sopir;
                }
                
                $kode_pemilik = '-';
                $tarif_gajisopir = $row->tarif_gajisopir;
                $uang_jalan = $row->uang_jalan;
                $bbm = $row->bbm;
                $dari = $row->dari;
                $tujuan = $row->tujuan;

                $output[] = array(
                    'no_spb'=>$no_spb,
                    'no_spb_manual'=>$no_spb_manual,
                    'tanggal_spb'=>$tanggal_spb,
                    'tanggal_kembali'=>$tanggal_kembali,
                    'total_berat'=>$total_berat,
                    'kode_pemilik'=>$kode_pemilik,
                    'kode_mobil'=>$kode_mobil,
                    'kode_sopir'=>$kode_sopir,
                    'tarif_gajisopir'=>$tarif_gajisopir,
                    'uang_jalan'=>$uang_jalan,
                    'bbm'=>$bbm,
                    'dari'=>$dari,
                    'tujuan'=>$tujuan,
                );
            }
        // dd($output);
        return response()->json($output);
    }

    public function Showdetailspbnc()
    {
        $truckingnondetail = TruckingnonDetail::with('mobil','sopir','port','port1','pemilik')->where('no_truckingnon',request()->id)->orderBy('created_at', 'desc')->get();

        $output = array();
        
            foreach($truckingnondetail as $row)
            {
                
                $no_spb = $row->no_spb;
                $no_spb_manual = $row->no_spb_manual;
                $tanggal_spb = $row->tanggal_spb;
                $total_berat = $row->total_berat;
                $dari = $row->dari;
                $tujuan = $row->tujuan;

                $output[] = array(
                    'no_spb'=>$no_spb,
                    'no_spb_manual'=>$no_spb_manual,
                    'tanggal_spb'=>$tanggal_spb,
                    'total_berat'=>$total_berat,
                    'dari'=>$dari,
                    'tujuan'=>$tujuan,
                );
            }
        // dd($output);
        return response()->json($output);
    }

    public function Post()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

        $getspbnc = TruckingnonDetail::where('no_truckingnon', request()->id)->get();
        foreach($getspbnc as $row)
        {
            if($row->tanggal_kembali == null){
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Data gagal di POST, masih ada SPB yang belum kembali.'
                ];
                return response()->json($message);
            }
        }

            $truckingnon = Truckingnon::find(request()->id);

            $tgl = $truckingnon->tanggal_truckingnon;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

            $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
            $tgl2 = $cek_bulan2->periode;
            $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
            $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

            // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
            //     $message = [
            //             'success' => false,
            //             'title' => 'Update',
            //             'message' => 'Data gagal di POSTING, re-open [Bulan '.$bulan.'; Tahun '.$tahun.'].'
            //         ];
            //     return response()->json($message);
            // }

            $get_job = Joborder::where('no_joborder',$truckingnon->no_joborder)->first();
            if($get_job->status != '2'){
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'No Job Order ['.$truckingnon->no_joborder.'] belum di POSTING.'
                ];
                return response()->json($message);
            }
            
            $get_nojo = $truckingnon->no_joborder;

            $getdetail = TruckingnonDetail::where('no_truckingnon', $truckingnon->no_truckingnon)->get();
            $data = array();
            foreach ($getdetail as $rowdata)
            {
                $no_spb = $rowdata->no_spb;

                $data[] = array(
                    'no_spb'=>$no_spb,
                );
            }

            $leng = count($data);

            $i = 0;
            for($i = 0; $i < $leng; $i++){
                $cek_spb = SpbnonDetail::where('no_spbnon',$data[$i]['no_spb'])->first();
                if($cek_spb == null){
                    $message = [
                        'success' => false,
                        'title' => 'Update',
                        'message' => 'Data gagal di POST, detail SPB tidak boleh kosong.'
                        ];
                    return response()->json($message);
                }
            }


            $spbnondetail = SpbnonDetail::where('no_spbnon',request()->id)->orderBy('created_at', 'desc')->get();

            $output = array();
        
            foreach($spbnondetail as $row)
            {
                $kode_item = $row->kode_item;   
            }

                $truckingnon->status = "POSTED";
                $truckingnon->save();

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Post Trucking Non: '.$truckingnon->no_truckingnon.'.','created_by'=>$nama,'updated_by'=>$nama];
                user_history::create($tmp);

                $joborder = Joborder::find($get_nojo);

                // $joborder->status = "3";
                // $joborder->save();

                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data berhasil di POST.'
                    ];
                return response()->json($message);
    }

    public function Unpost()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

            $truckingnon = Truckingnon::find(request()->id);

            $tgl = $truckingnon->tanggal_truckingnon;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

            $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
            $tgl2 = $cek_bulan2->periode;
            $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
            $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

            // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
            //     $message = [
            //             'success' => false,
            //             'title' => 'Update',
            //             'message' => 'Data gagal di UNPOSTING, re-open [Bulan '.$bulan.'; Tahun '.$tahun.'].'
            //         ];
            //     return response()->json($message);
            // }
            
            $cek_nospb = TruckingnonDetail::where('no_truckingnon', request()->id)->pluck('no_spb','no_spb');

            // $cek_detail_byr = PembayaranDetail::where('no_spb',$cek_nospb)->first();
            // if($cek_detail_byr != null){
            //     $message = [
            //         'success' => false,
            //         'title' => 'Update',
            //         'message' => 'Data gagal di UNPOSTING, SPB '.$cek_detail_byr->no_spb.' ada dalam detail no BYR '.$cek_detail_byr->no_pembayaran.' Silahakan hapus terlebih dulu dari detail BYR.'
            //     ];
            //     return response()->json($message);
            // }

            $cek_detail_hbu = HasilbagiDetail::where('no_spb',$cek_nospb)->first();

            // if($cek_detail_hbu != null){
            //     $message = [
            //         'success' => false,
            //         'title' => 'Update',
            //         'message' => 'Data gagal di UNPOSTING, SPB '.$cek_detail_hbu->no_spb.' ada dalam detail no HBU '.$cek_detail_hbu->no_hasilbagi.' Silahakan hapus terlebih dulu dari detail HBU.'
            //     ];
            //     return response()->json($message);
            // }

            $getspb = TruckingnonDetail::where('no_truckingnon', request()->id)->get();

            // foreach($getspb as $row)
            // {
            //     if($row->status_spbnon == 2){
            //         $message = [
            //             'success' => false,
            //             'title' => 'Update',
            //             'message' => 'Data gagal di UNPOST, SPB sudah dilakukan pembayaran pemilik mobil.'
            //         ];
            //         return response()->json($message);
            //     }

            //     if($row->status_hasilbagi == 2){
            //         $message = [
            //             'success' => false,
            //             'title' => 'Update',
            //             'message' => 'Data gagal di UNPOST, SPB sudah dilakukan pembayaran hasil bagi usaha.'
            //         ];
            //         return response()->json($message);
            //     }
            // }
            
            $get_nojo = $truckingnon->no_joborder;

            $joborder = Joborder::find($get_nojo);

            // $joborder->status = "2";
            // $joborder->save();

                $truckingnon->status = "OPEN";
                $truckingnon->save();    

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Trucking Non: '.$truckingnon->no_truckingnon.'.','created_by'=>$nama,'updated_by'=>$nama];

                user_history::create($tmp);

                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data berhasil di UNPOST.'
                    ];
                return response()->json($message);
    }

    public function store(Request $request)
    {
        $truckingnon = Truckingnon::where('no_joborder', $request->no_joborder)->first();
        
        $tgl = $request->tanggal_truckingnon;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
        //     $message = [
        //             'success' => false,
        //             'title' => 'Update',
        //             'message' => '<b>Periode</b> ['.$tgl.'] <b>Telah Ditutup / Belum Dibuka</b>'
        //         ];
        //     return response()->json($message);
        // }

        $cek_transaksi = Truckingnon::whereMonth('tanggal_truckingnon',$bulan2)->whereYear('tanggal_truckingnon',$tahun2)->where('status','OPEN')->where('status_kembali','TRUE')->first();
        if ($cek_transaksi != null){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Masih ada trucking-non yang OPEN. Silahkan POST terlebih dahulu trucking-non yang semua SPB-nya telah kembali.'
            ];
            return response()->json($message);
        }

        if($truckingnon != null){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Nomor JO ['.$request->no_joborder.'] Sudah Ada'
            ];
            return response()->json($message);
        }
        else{
            Truckingnon::create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.'
            ];
            return response()->json($message);
        }
    }

    public function edit_truckingnon()
    {
        $no_truckingnon = request()->id;
        $data = Truckingnon::find($no_truckingnon);
        $output = array(
            'no_truckingnon'=>$data->no_truckingnon,
            'no_joborder'=>$data->no_joborder,
            'tanggal_truckingnon'=>$data->tanggal_truckingnon,
            'kode_customer'=>$data->kode_customer,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_truckingnon = $request->no_truckingnon;

        $tgl = $request->tanggal_truckingnon;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
        //     $message = [
        //             'success' => false,
        //             'title' => 'Update',
        //             'message' => '<b>Periode</b> ['.$tgl.'] <b>Telah Ditutup / Belum Dibuka</b>'
        //         ];
        //     return response()->json($message);
        // }

        $request->validate([
            'no_truckingnon'=>'required',
            'no_joborder'=> 'required',
            'tanggal_truckingnon'=> 'required',
            'kode_customer'=> 'required',
        ]);

          Truckingnon::find($request->no_truckingnon)->update($request->all());
       
          $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
    }

    public function hapus_truckingnon()
    {   
        $no_truckingnon = request()->id;
        $truckingnon = Truckingnon::find(request()->id);

            $truckingnon->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$truckingnon->no_truckingnon.'] telah dihapus.'
            ];
            return response()->json($message);
    }

}
