<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Trucking;
use App\Models\Truckingnon;
use App\Models\Customer;
use App\Models\Gudang;
use App\Models\GudangDetail;
use App\Models\Sizecontainer;
use App\Models\TruckingDetail;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Kapal;
use App\Models\Joborder;
use App\Models\JoborderDetail;
use App\Models\Spb;
use App\Models\SpbDetail;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Port;
use App\Models\Pemilik;
use App\Models\Vendor;
use App\Models\JobrequestDetail;
use App\Models\PembayaranDetail;
use App\Models\HasilbagiDetail;
use Carbon;
use DB;
use PDF;

class TruckingController extends Controller
{
    public function index()
    {
        $create_url = route('trucking.create');
        $Company = Company::pluck('nama_company','kode_company');
        $no_trucking = Trucking::pluck('no_joborder','no_joborder');
        // $Joborder = Joborder::whereNotIn('no_joborder',$no_truckingnon)->pluck('no_joborder','no_joborder');
        $Joborder = Joborder::where('job_order.status','<>', '1')->where('job_order.jenis_jo','Container')->pluck('no_joborder','no_joborder');

        $Kapal = Kapal::pluck('nama_kapal','id');
        $Customer = Customer::pluck('nama_customer','id');
        $Gudang = Gudang::Join('customer', 'customer.id', '=', 'gudang.kode_customer')->pluck('customer.nama_customer','gudang.kode_customer');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.trucking.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer','Gudang','Company','Kapal','Joborder'));
    }

    public function getkode(){
        $get_inv = Trucking::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kode_customer = $rowdata->kode_customer;
            $kode_shipper = $rowdata->kode_shipper;
            $kode_kapal = $rowdata->kode_kapal;

            $data[] = array(
                'kode_customer'=>$kode_customer,
                'kode_shipper'=>$kode_shipper,
                'kode_kapal'=>$kode_kapal,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_cust = Customer::where('kode_customer', $data[$i]['kode_customer'])->first();
            $cek_ship = Customer::where('kode_customer', $data[$i]['kode_shipper'])->first();
            $cek_kapal = Kapal::where('kode_kapal', $data[$i]['kode_kapal'])->first();

            if($cek_cust != null){
                $id_cust = $cek_cust->id;

                $tabel_baru_cust = [
                    'kode_customer'=>$id_cust,
                ];
                $update_cust = Trucking::where('kode_customer', $data[$i]['kode_customer'])->update($tabel_baru_cust);   
            }
            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kode_shipper'=>$id_ship,
                ];
                $update_ship = Trucking::where('kode_shipper', $data[$i]['kode_shipper'])->update($tabel_baru_ship);
            }
            if($cek_kapal != null){
                $id_kapal = $cek_kapal->id;

                $tabel_baru_kapal = [
                    'kode_kapal'=>$id_kapal,
                ];
                $update_sopir = Trucking::where('kode_kapal', $data[$i]['kode_kapal'])->update($tabel_baru_kapal);   
            }
        }


        $get_inv = Spb::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kode_sopir = $rowdata->kode_sopir;

            $data[] = array(
                'kode_sopir'=>$kode_sopir,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_sopir = Sopir::where('kode_sopir', $data[$i]['kode_sopir'])->first();

            if($cek_sopir != null){
                $id_sopir = $cek_sopir->id;

                $tabel_baru_sopir = [
                    'kode_sopir'=>$id_sopir,
                ];
                $update_sopir = Spb::where('kode_sopir', $data[$i]['kode_sopir'])->update($tabel_baru_sopir);   
            }
        }


        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Kode Berhasil di Update.'
        ];
        
        return response()->json($message);
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Trucking::with('customer1','customer2','kapal')->orderBy('created_at','desc'))->make(true);
    }

    public function export2(){
        $request = $_GET['no_trucking'];
        $trucking = Trucking::with('kapal')->find($request);
        $truckingdetail = TruckingDetail::with('gudangdetail','sizecontainer')->where('no_trucking',$request)->get();
        $leng = count($truckingdetail);

        $get_jo = $trucking->no_joborder;
        $jo = Joborder::find($get_jo);

        $dt = Carbon\Carbon::now();
        $date_now = Carbon\Carbon::parse($dt)->format('d/m/Y');

        $tgl = $trucking->tanggal_trucking;
        $date=date_create($tgl);

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;

        $pdf = PDF::loadView('/admin/trucking/pdf', compact('truckingdetail','trucking', 'date_now','date','jo','request','nama','nama2','dt','get_jo','leng'));
        $pdf->setPaper([15, -40, 684, 458], 'portrait');

        return $pdf->stream('Cetak SPB dengan No JO '.$get_jo.'.pdf');
    }
    
    public function export3(){
        $request = $_GET['no_trucking'];
        if(isset($_GET['ttd'])){
            $format_ttd = $_GET['ttd']; 
        }else{
            $format_ttd = 0;
        }
        $ttd = auth()->user()->name;
        
        $trucking = Trucking::with('kapal')->find($request);
        $truckingdetail = TruckingDetail::with('gudangdetail','mobil','sopir')->join('spb','spb.no_spb','=','trucking_detail.no_spb')->where('no_trucking',$request)->get();
        $leng = count($truckingdetail);

        $get_jo = $trucking->no_joborder;
        $get_jor = $trucking->no_req_jo;
        $jo = Joborder::find($get_jo);

        $kode_customer = $trucking->kode_customer;
        $kode_shipper = $trucking->kode_shipper;
        $customer = Customer::find($kode_customer);
        $shipper = Customer::find($kode_shipper);

        $dt = Carbon\Carbon::now();
        $date_now = Carbon\Carbon::parse($dt)->format('d/m/Y');

        $tgl = $trucking->tanggal_trucking;
        $date=date_create($tgl);

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;

        $pdf = PDF::loadView('/admin/trucking/pdfcontainer', compact('truckingdetail','trucking', 'date_now','date','jo','request','nama','nama2','dt','get_jo','leng','get_jor','customer','shipper','tgl','ttd','format_ttd'));
        $pdf->setPaper([0, 0, 684, 792], 'potrait');

        return $pdf->stream('Cetak Container JO '.$get_jo.'.pdf');
    }

    public function detail($trucking)
    {
        $trucking = Trucking::find($trucking);
        $no_trucking = $trucking->no_trucking;
        $kode_shipper = $trucking->kode_shipper;
        $no_joborder = $trucking->no_joborder;

        $tgl = $trucking->tanggal_trucking;
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

        $data = Trucking::find($no_trucking);
                    
        $Spb = Spb::pluck('no_spb','no_spb');
        $Mobil = Mobil::where('status_mobil','Aktif')->pluck('nopol','kode_mobil');
        $Asmobil = Mobil::whereNotNull('no_asset_mobil')->pluck('no_asset_mobil','no_asset_mobil');
        $Sopir = Sopir::pluck('nama_sopir','id');
        $Pemilik = Pemilik::join('u5611458_db_pusat.vendor','pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->pluck('nama_vendor','id');
        $Port = Port::pluck('nama_port','id');
        $Container = JobrequestDetail::where('jobrequest_detail.no_joborder', $no_joborder)->pluck('kode_container','kode_container');

        $truckingdetail = TruckingDetail::with('sizecontainer','gudangdetail')->where('no_trucking', $trucking->no_trucking)->orderBy('created_at','desc')->get();

        $list_url= route('trucking.index');
                    
        // $Gudang = GudangDetail::select('id', DB::raw("concat(kode_gudang,' - ',nama_gudang) as gudangs"))->where('kode_shipper',$kode_shipper)->pluck('gudangs','id');
        $Gudang = GudangDetail::join('tarif', 'tarif.kode_gudang','=','gudang_detail.id')->where('tarif_trucking','>',0)->where('gudang_detail.kode_shipper',$kode_shipper)->pluck('nama_gudang','gudang_detail.id');
        
        $Size = Sizecontainer::pluck('nama_size','id');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.truckingdetail.index', compact('trucking','truckingdetail','list_url','Gudang','Size','period','nama_lokasi','nama_company','Spb','Mobil','Sopir','Port','Pemilik','Container','Asmobil'));
    }

    public function getdata()
    {
         $joborder = Joborder::find(request()->id);
         $getdetail = JoborderDetail::where('no_joborder',request()->id)->first();
         $no_req_jo = $getdetail->no_req_jo;

            $output = array(
                'no_req_jo'=>$no_req_jo,
                'kode_customer'=>$joborder->kode_customer,
                'kode_shipper'=>$joborder->kode_shipper,
                'kode_kapal'=>$joborder->kode_kapal,
                'voyage'=>$joborder->voyage,
                'no_do'=>$joborder->no_do,
            );
       
        return response()->json($output);
    }

    public function getdata2()
    {
         $joborder = Joborder::find(request()->id);
         $getdetail = JoborderDetail::where('no_joborder',request()->id)->first();
         $no_req_jo = $getdetail->no_req_jo;

            $output = array(
                'no_req_jo'=>$no_req_jo,
                'kode_customer'=>$joborder->kode_customer,
                'kode_shipper'=>$joborder->kode_shipper,
                'kode_kapal'=>$joborder->kode_kapal,
                'voyage'=>$joborder->voyage,
                'no_do'=>$joborder->no_do,
            );
       
        return response()->json($output);
    }

    public function Showdetail()
    {
        $total_harga = 0;

        $truckingdetail = TruckingDetail::where('no_trucking',request()->id)->orderBy('created_at', 'desc')->get();

        $output = array();

        foreach ($truckingdetail as $row){
            $total_harga += $row->tarif_trucking;
        }
        
            foreach($truckingdetail as $row)
            {

                $kode_gudang = $row->gudangdetail->nama_gudang;
                $kode_container = $row->kode_container;
                $kode_size = $row->sizecontainer->nama_size;
                $no_seal = $row->no_seal;
                $no_spb = $row->no_spb;
                $muatan = $row->muatan;
                $colie = $row->colie;
                $tarif_trucking = $row->tarif_trucking;

                $output[] = array(
                    'kode_gudang'=>$kode_gudang,
                    'kode_container'=>$kode_container,
                    'kode_size'=>$kode_size,
                    'no_seal'=>$no_seal,
                    'no_spb'=>$no_spb,
                    'muatan'=>$muatan,
                    'colie'=>$colie,
                    'tarif_trucking'=>$tarif_trucking,
                );
            }

        return response()->json($output);
    }

    public function Showdetailspb()
    {
        $getspb = Spb::with('mobil','sopir','pemilik')
                    ->join('trucking_detail', 'spb.no_spb', '=', 'trucking_detail.no_spb')
                    ->where('trucking_detail.no_trucking', request()->id)
                    ->orderBy('spb.no_spb','asc')
                    ->get();
        // dd($getspb);
        $output = array();

        if(! empty($getspb)){
            foreach($getspb as $row)
            {
                $no_spb = $row->no_spb;
                $no_spb_manual = $row->no_spb_manual;
                $tgl_spb = $row->tgl_spb;
                $tgl_kembali = $row->tgl_kembali;

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
                
                $kode_pemilik = $row->pemilik->nama_pemilik;
                $uang_jalan = $row->uang_jalan;
                $bbm = $row->bbm;
                $bpa = $row->bpa;
                $honor = $row->honor;
                $biaya_lain = $row->biaya_lain;
                $trucking = $row->trucking;
                
                $output[] = array(
                    'no_spb'=>$no_spb,
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
            }
        }

        return response()->json($output);
    }

    public function Post()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

            $trucking = Trucking::find(request()->id);

            $tgl = $trucking->tanggal_trucking;
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

            $get_job = Joborder::where('no_joborder',$trucking->no_joborder)->first();
            if($get_job->status != '2'){
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'No Job Order ['.$trucking->no_joborder.'] belum di POSTING.'
                ];
                return response()->json($message);
            }

            $get_nojo = $trucking->no_joborder;

            // $joborder = Joborder::find($get_nojo);

            // $joborder->status = "ON PROGRESS";
            // $joborder->save();
            
            $get_detail = TruckingDetail::where('no_trucking',request()->id)->get();
            $leng1 = count($get_detail);
            $leng2 = $get_job->total_item;
            if($leng1 != $leng2){
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Masih ada container pada No JO ['.request()->id.'] yang belum di masukkan dalam detail transaksi.'
                ];
                return response()->json($message);
            }

            foreach($get_detail as $row)
            {
                if($row->tarif_trucking == null){
                    $message = [
                        'success' => false,
                        'title' => 'Update',
                        'message' => 'Data gagal di POST, data SPB harus dilengkapi.'
                        ];
                    return response()->json($message);
                }
            }

            $getspb = Spb::with('mobil','sopir','pemilik')
                ->join('trucking_detail', 'spb.no_spb', '=', 'trucking_detail.no_spb')
                ->where('trucking_detail.no_trucking', request()->id)
                ->orderBy('spb.no_spb','asc')
                ->get();

            foreach($getspb as $row)
            {
                if($row->tgl_kembali == null){
                    $message = [
                        'success' => false,
                        'title' => 'Update',
                        'message' => 'Data gagal di POST, masih ada SPB yang belum kembali.'
                    ];
                    return response()->json($message);
                }
            }
            
            foreach($getspb as $row)
            {
                if($row->kode_mobil == null){
                    $message = [
                        'success' => false,
                        'title' => 'Update',
                        'message' => 'Data gagal di POST, detail SPB harus dilengkapi.'
                        ];
                    return response()->json($message);
                }
            }

            $trucking->status = "POSTED";
            $trucking->save();

            $joborder = Joborder::find($get_nojo);

            // $joborder->status = "2";
            // $joborder->save();

            $nama = auth()->user()->name;
            $tmp = ['nama' => $nama,'aksi' => 'Post Trucking: '.$trucking->no_trucking.'.','created_by'=>$nama,'updated_by'=>$nama];
            user_history::create($tmp);

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

            $trucking = Trucking::find(request()->id);

            $tgl = $trucking->tanggal_trucking;
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
            
            $cek_nospb = TruckingDetail::where('no_trucking', request()->id)->pluck('no_spb','no_spb');

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

            $getspb = Spb::with('mobil','sopir','pemilik')
                ->join('trucking_detail', 'spb.no_spb', '=', 'trucking_detail.no_spb')
                ->where('trucking_detail.no_trucking', request()->id)
                ->orderBy('spb.no_spb','asc')
                ->get();

            // foreach($getspb as $row)
            // {
            //     if($row->status_spb == 2){
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
            
            $get_nojo = $trucking->no_joborder;

            $joborder = Joborder::find($get_nojo);

            // $joborder->status = "2";
            // $joborder->save();

                $trucking->status = "OPEN";
                $trucking->save();    

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Trucking: '.$trucking->no_trucking.'.','created_by'=>$nama,'updated_by'=>$nama];

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
        $trucking = Trucking::where('no_joborder', $request->no_joborder)->first();
        
        $tgl = $request->tanggal_trucking;
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

        $cek_transaksi = Trucking::whereMonth('tanggal_trucking',$bulan2)->whereYear('tanggal_trucking',$tahun2)->where('status','OPEN')->where('status_kembali','TRUE')->first();
        if ($cek_transaksi != null){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Masih ada trucking yang OPEN. Silahkan POST terlebih dahulu trucking yang semua SPB-nya telah kembali.'
            ];
            return response()->json($message);
        }

        if($trucking != null){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Nomor JO ['.$request->no_joborder.'] Sudah Ada'
            ];
            return response()->json($message);
        }
        else{
            Trucking::create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.'
            ];
            return response()->json($message);
        }
    }

    public function edit_trucking()
    {
        $no_trucking = request()->id;
        $data = Trucking::find($no_trucking);
        $output = array(
            'no_trucking'=>$data->no_trucking,
            'no_joborder'=>$data->no_joborder,
            'no_req_jo'=>$data->no_req_jo,
            'tanggal_trucking'=>$data->tanggal_trucking,
            'kode_customer'=>$data->kode_customer,
            'kode_shipper'=>$data->kode_shipper,
            'kode_kapal'=>$data->kode_kapal,
            'voyage'=>$data->voyage,
            'no_do'=>$data->no_do,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_trucking = $request->no_trucking;
        
        $tgl = $request->tanggal_trucking;
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
            'no_trucking'=>'required',
            'no_joborder'=> 'required',
            'tanggal_trucking'=> 'required',
            'kode_customer'=> 'required',
            'kode_shipper'=> 'required',
            'kode_kapal'=> 'required',
            'voyage'=> 'required',
            'no_do'=> 'required',
        ]);

          Trucking::find($request->no_trucking)->update($request->all());
       
          $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
    }

    public function hapus_trucking()
    {   
        $no_trucking = request()->id;
        $trucking = Trucking::find(request()->id);

            $trucking->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$trucking->no_trucking.'] telah dihapus.'
            ];
            return response()->json($message);
    }

}
