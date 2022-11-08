<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Joborder;
use App\Models\TypeCargo;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\JoborderDetail;
use App\Models\Sizecontainer;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Kapal;
use App\Models\Alat;
use App\Models\Operator;
use App\Models\Helper;
use App\Models\PemakaianAlat;
use App\Models\PemakaianAlatDetail;
use App\Models\InsentifoperatorDetail;
use App\Models\InsentifhelperDetail;
use Carbon;
use DB;
use PDF;

class PemakaianAlatController extends Controller
{
    public function index()
    {
        $create_url = route('pemakaianalat.create');
        $Company = Company::pluck('nama_company','kode_company');
        
        $Customer = Customer::where('status','Aktif')->pluck('nama_customer','id');
        $Joborder = Joborder::where('status','2')->whereIn('type_jo', array('1','3','5'))->pluck('no_joborder','no_joborder');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.pemakaianalat.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer','Company','Joborder'));
    }

    public function getkode(){
        $get_inv = Joborder::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kode_customer = $rowdata->kode_customer;
            $kode_shipper = $rowdata->kode_shipper;
            $kode_consignee = $rowdata->kode_consignee;
            $port_loading = $rowdata->port_loading;
            $port_transite = $rowdata->port_transite;
            $port_destination = $rowdata->port_destination;
            $kode_kapal = $rowdata->kode_kapal;

            $data[] = array(
                'kode_customer'=>$kode_customer,
                'kode_shipper'=>$kode_shipper,
                'kode_consignee'=>$kode_consignee,
                'port_loading'=>$port_loading,
                'port_transite'=>$port_transite,
                'port_destination'=>$port_destination,
                'kode_kapal'=>$kode_kapal,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_cust = Customer::where('kode_customer', $data[$i]['kode_customer'])->first();
            $cek_ship = Customer::where('kode_customer', $data[$i]['kode_shipper'])->first();
            $cek_cons = Customer::where('kode_customer', $data[$i]['kode_consignee'])->first();
            $cek_kapal = Kapal::where('kode_kapal', $data[$i]['kode_kapal'])->first();

            if($cek_cust != null){
                $id_cust = $cek_cust->id;

                $tabel_baru_cust = [
                    'kode_customer'=>$id_cust,
                ];
                $update_cust = Joborder::where('kode_customer', $data[$i]['kode_customer'])->update($tabel_baru_cust);   
            }
            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kode_shipper'=>$id_ship,
                ];
                $update_ship = Joborder::where('kode_shipper', $data[$i]['kode_shipper'])->update($tabel_baru_ship);
            }
            if($cek_cons != null){
                $id_cons = $cek_cons->id;

                $tabel_baru_cons = [
                    'kode_consignee'=>$id_cons,
                ];
                $update_cons = Joborder::where('kode_consignee', $data[$i]['kode_consignee'])->update($tabel_baru_cons);
            }  
            if($cek_kapal != null){
                $id_kapal = $cek_kapal->id;

                $tabel_baru_kapal = [
                    'kode_kapal'=>$id_kapal,
                ];
                $update_cons = Joborder::where('kode_kapal', $data[$i]['kode_kapal'])->update($tabel_baru_kapal);
            }    



            $cek_port1 = Port::where('kode_port', $data[$i]['port_loading'])->first();
            $cek_port2 = Port::where('kode_port', $data[$i]['port_transite'])->first();
            $cek_port3 = Port::where('kode_port', $data[$i]['port_destination'])->first();

            if($cek_port1 != null){
                $id_cust = $cek_port1->id;

                $tabel_baru_port1 = [
                    'port_loading'=>$id_cust,
                ];
                $update_port1 = Joborder::where('port_loading', $data[$i]['port_loading'])->update($tabel_baru_port1);   
            }
            if($cek_port2 != null){
                $id_ship = $cek_port2->id;

                $tabel_baru_port2 = [
                    'port_transite'=>$id_ship,
                ];
                $update_port2 = Joborder::where('port_transite', $data[$i]['port_transite'])->update($tabel_baru_port2);
            }
            if($cek_port3 != null){
                $id_cons = $cek_port3->id;

                $tabel_baru_port3 = [
                    'port_destination'=>$id_cons,
                ];
                $update_port3 = Joborder::where('port_destination', $data[$i]['port_destination'])->update($tabel_baru_port3);
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
            return Datatables::of(PemakaianAlat::with('customer')->orderBy('created_at','desc'))->make(true);
    }

    public function getjo()
    {
        
        $jo = Joborder::find(request()->no_jo);
        $cust = Customer::find($jo->kode_customer);

        if ($jo->type_jo == '1') {
            $tipe = 'Bongkar Muat Curah';
        }else if ($jo->type_jo == '2') {
            $tipe = 'Bongkar Muat Non Curah';
        }else if ($jo->type_jo == '3') {
            $tipe = 'Rental Alat';
        }else if ($jo->type_jo == '4') {
            $tipe = 'Trucking';
        }else if ($jo->type_jo == '5') {
            $tipe = 'Lain-lain';
        }

        if ($jo->type_kegiatan == '1') {
            $kegiatan = 'Non Transhipment';
        }else if ($jo->type_kegiatan == '2') {
            $kegiatan = 'Transhipment';
        }

        if ($jo->status_lokasi == '1') {
            $lokasi = 'Dalam Kota';
        }else if ($jo->status_lokasi == '2') {
            $lokasi = 'Luar Kota';
        }

        $output = array(
            'kode_customer'=>$jo->kode_customer,
            'type_jo'=>$jo->type_jo,
            'type_kegiatan'=>$jo->type_kegiatan,
            'status_lokasi'=>$jo->status_lokasi,
            'nama_customer'=>$cust->nama_customer,
            'tipe_jo'=>$tipe,
            'tipe_kegiatan'=>$kegiatan,
            'nama_lokasi'=>$lokasi,
        );

        return response()->json($output);
    }

    public function getjo2()
    {
        
        $jo = Joborder::find(request()->no_jo);
        $cust = Customer::find($jo->kode_customer);

        if ($jo->type_jo == '1') {
            $tipe = 'Bongkar Muat Curah';
        }else if ($jo->type_jo == '2') {
            $tipe = 'Bongkar Muat Non Curah';
        }else if ($jo->type_jo == '3') {
            $tipe = 'Rental Alat';
        }else if ($jo->type_jo == '4') {
            $tipe = 'Trucking';
        }else if ($jo->type_jo == '5') {
            $tipe = 'Lain-lain';
        }

        if ($jo->type_kegiatan == '1') {
            $kegiatan = 'Non Transhipment';
        }else if ($jo->type_kegiatan == '2') {
            $kegiatan = 'Transhipment';
        }

        if ($jo->status_lokasi == '1') {
            $lokasi = 'Dalam Kota';
        }else if ($jo->status_lokasi == '2') {
            $lokasi = 'Luar Kota';
        }

        $output = array(
            'kode_customer'=>$jo->kode_customer,
            'type_jo'=>$jo->type_jo,
            'type_kegiatan'=>$jo->type_kegiatan,
            'status_lokasi'=>$jo->status_lokasi,
            'nama_customer'=>$cust->nama_customer,
            'tipe_jo'=>$tipe,
            'tipe_kegiatan'=>$kegiatan,
            'nama_lokasi'=>$lokasi,
        );

        return response()->json($output);
    }

    public function exportPDF(){
        $request = $_GET['no_pemakaian'];

        $joborder = PemakaianAlat::with('customer')->where('no_pemakaian', $request)->first();
        $jobrequest = PemakaianAlatDetail::where('no_pemakaian',$request)->get();
        $user = $joborder->created_by;

        $nojo = $joborder->no_joborder;

        $tgl = $joborder->tgl_pemakaian;
        $date=date_create($tgl);
        
        $total_qty = 0;
        
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        $company = auth()->user()->kode_company;
        
        $pdf = PDF::loadView('/admin/pemakaianalat/pdf', compact('user','nama','nama2','dt','request', 'jobrequest','date','joborder','tgl','nojo'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan Job Request '.$request.'.pdf');
    }

    public function detail($joborder)
    {
        $pemakaian = PemakaianAlat::find($joborder);
        $no_pemakaian = $pemakaian->no_pemakaian;

        $tgl = $pemakaian->tgl_pemakaian;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $pemakaiandetail = PemakaianAlatDetail::where('no_pemakaian', $no_pemakaian)->orderBy('created_at','desc')->get();

        // $Alat = Alat::select('kode_alat','status', DB::raw("concat(nama_alat,' - ',merk) as alats"))->where('status', 'Aktif')->pluck('alats','kode_alat');

        $Alat = Alat::where('status', 'Aktif')->pluck('nama_alat','kode_alat');

        $Operator = Operator::pluck('nama_operator','id');
        $Helper = Helper::pluck('nama_helper','id');

        $list_url= route('pemakaianalat.index');
                    
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.pemakaianalatdetail.index', compact('pemakaian','pemakaiandetail','list_url','period','nama_lokasi','nama_company','no_pemakaian','Alat','Operator','Helper'));
    }

    public function Showdetail()
    {
        $detail= PemakaianAlatDetail::where('no_pemakaian',request()->id)->get();
    
        $output = array();
        foreach ($detail as $row) {
            $alat = Alat::find($row->kode_alat);
            $opera = Operator::find($row->operator);

            $helper1 = Helper::find($row->helper1);
            if ($helper1 == null) {
                $helper1 = '-';
            }else {
                $helper1 = $helper1->nama_helper;
            }

            $helper2 = Helper::find($row->helper2);
            if ($helper2 == null) {
                $helper2 = '-';
            }else {
                $helper2 = $helper2->nama_helper;
            }

            $output[] = array(
                'kode_alat'=>$alat->no_asset_alat,
                'hitungan_pemakaian'=>$row->hitungan_pemakaian,
                'no_timesheet'=>$row->no_timesheet,
                'operator'=>$opera->nama_operator,
                'helper1'=>$helper1,
                'helper2'=>$helper2,
                'pekerjaan'=>$row->pekerjaan,
                'tgl_pakai'=>$row->tgl_pakai,
                'hari_libur'=>$row->hari_libur,
                'jam_dr'=>$row->jam_dr,
                'jam_sp'=>$row->jam_sp,
                'istirahat'=>$row->istirahat,
                'stand_by'=>$row->stand_by,
                'hm_dr'=>$row->hm_dr,
                'hm_sp'=>$row->hm_sp,
                'total_jam'=>$row->total_jam,
                'total_hm'=>$row->total_hm,
                'no_insentif'=>$row->no_insentif,
                'no_insentif_helper1'=>$row->no_insentif_helper1,
                'no_insentif_helper2'=>$row->no_insentif_helper2,
            );
        }
            
        return response()->json($output);
    }

    public function Showdetailjor()
    {
        $jobrequest= JobrequestDetail::with('sizecontainer')->where('no_joborder',request()->id)->orderBy('created_at', 'desc')->get();
    
        $output = array();

            foreach($jobrequest as $row)
            {
                $no_req_jo = $row->no_req_jo;
                $kode_container = $row->kode_container;
                $kode_size = $row->sizecontainer->nama_size;
                $status_muatan = $row->status_muatan;
                $dari = $row->dari;
                $tujuan = $row->tujuan;
                
                $output[] = array(
                    'no_req_jo'=>$no_req_jo,
                    'kode_container'=>$kode_container,
                    'kode_size'=>$kode_size,
                    'status_muatan'=>$status_muatan,
                    'dari'=>$dari,
                    'tujuan'=>$tujuan,
                );
            }

        return response()->json($output);
    }

    public function Post()
    {
        $level = auth()->user()->level;
        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $pemakaian = PemakaianAlat::find(request()->id);
        $pemakaian->status = 'POSTED';
        $pemakaian->save();

        $stat = [
            'status'=>'POSTED',
        ];
        $pemakaiandetail = PemakaianAlatDetail::where('no_pemakaian', $pemakaian->no_pemakaian)->update($stat);

        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Post Pemakaian Alat Berat: '.$pemakaian->no_pemakaian.'.','created_by'=>$nama,'updated_by'=>$nama];
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
    
        $insoperator = InsentifoperatorDetail::where('no_pemakaian', request()->id)->first();
        $inshelper = InsentifhelperDetail::where('no_pemakaian', request()->id)->first();
        if ($insoperator != null || $inshelper != null) {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Gagal UNPOST, data sudah ditarik ke transaksi premi Operator/Helper.'
            ];
            return response()->json($message);
        }else {
            $pemakaian = PemakaianAlat::find(request()->id);
            $pemakaian->status = "OPEN";
            $pemakaian->save();

            $stat = [
            'status'=>'OPEN',
            ];
            $pemakaiandetail = PemakaianAlatDetail::where('no_pemakaian', $pemakaian->no_pemakaian)->update($stat);
        }
        
        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Job Order: '.$pemakaian->no_pemakaian.'.','created_by'=>$nama,'updated_by'=>$nama];
    
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
        $tgl = $request->tgl_pemakaian;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek = PemakaianAlat::where('no_joborder', $request->no_joborder)->first();
        if ($cek != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'No Job sudah ada.'
            ];
            return response()->json($message);
        }

        $cekopen = PemakaianAlat::where('status','OPEN')->first();
        if ($cekopen != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Masih ada Nomor PSA yang OPEN.'
            ];
            return response()->json($message);
        }
        
        PemakaianAlat::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_pemakaian()
    {
        $no_pemakaian = request()->no_pemakaian;
        $data = PemakaianAlat::find($no_pemakaian);
        $output = array(
            'no_pemakaian'=>$data->no_pemakaian,
            'tgl_pemakaian'=>$data->tgl_pemakaian,
            'no_joborder'=>$data->no_joborder,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $tgl = $request->tgl_pemakaian;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        PemakaianAlat::find($request->no_pemakaian)->update($request->all());
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_pemakaian()
    {   
        $no_pemakaian = request()->id;
        $joborder = PemakaianAlat::find(request()->id);
        $cek_jor = PemakaianAlatDetail::where('no_pemakaian', $no_pemakaian)->first();
        if($cek_jor == null){
            $joborder->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$joborder->no_pemakaian.'] telah dihapus.'
            ];
            return response()->json($message);
        }
        else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Hapus terlebih dahulu detail ['.$joborder->no_pemakaian.'].'
            ];
            return response()->json($message);
        }
    }
}
