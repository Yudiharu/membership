<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Joborder;
use App\Models\TypeCargo;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Gudang;
use App\Models\JoborderDetail;
use App\Models\Jobrequest;
use App\Models\JobrequestDetail;
use App\Models\Sizecontainer;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Port;
use App\Models\Kapal;
use App\Models\Trucking;
use App\Models\Truckingnon;
use App\Models\Satuan;
use App\Models\Alat;
use App\Models\TypeInv;
use Carbon;
use DB;
use PDF;

class JoborderController extends Controller
{
    public function index()
    {
        $create_url = route('joborder.create');
        $Company = Company::pluck('nama_company','kode_company');
        $Kapal = Kapal::on('mysqlinvpbm')->where('type','Tug Boat')->orwhere('type','MV')->pluck('nama_kapal','kode_kapal');
        $Tongkang = Kapal::on('mysqlinvpbm')->where('type','Tongkang')->pluck('nama_kapal','kode_kapal');
        $Customer = Customer::where('status','Aktif')->pluck('nama_customer','id');
        $CustomerInf = Customer::where('status','Aktif')->where('jenis_cust','INF')->pluck('nama_customer','id');
        $Vendor = Vendor::pluck('nama_vendor','id');
        $Cargo = TypeCargo::pluck('type_cargo','id');
        $TypeJo = TypeInv::groupBy('type_inv')->groupBy('type_desc')->pluck('type_desc','type_inv');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.joborder.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer','CustomerInf','Company','Kapal','Vendor','Tongkang','Cargo','TypeJo'));
    }

    public function ttd_buat()
    {
        $signature = request()->img;
        $signatureFileName = request()->no.'-dibuat'.'.png';
        $signature = str_replace('data:image/png;base64,', '', $signature);
        $signature = str_replace(' ', '+', $signature);
        $data = base64_decode($signature);

        $cekfile = realpath(dirname(getcwd())).'/gui_front_02/digital/joborder/'.$signatureFileName;
        if (file_exists($cekfile)) {
            unlink($cekfile);
        }

        $folder = realpath(dirname(getcwd())).'/gui_front_02/digital/joborder/';
        $file = $folder.$signatureFileName;
        file_put_contents($file, $data);

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'TTD (Dibuat Oleh) telah disimpan.'
        ];
        return response()->json($message);
    }
    
    public function ttd_periksa()
    {
        $signature = request()->img;
        $signatureFileName = request()->no.'-diperiksa'.'.png';
        $signature = str_replace('data:image/png;base64,', '', $signature);
        $signature = str_replace(' ', '+', $signature);
        $data = base64_decode($signature);

        $cekfile = realpath(dirname(getcwd())).'/gui_front_02/digital/joborder/'.$signatureFileName;
        if (file_exists($cekfile)) {
            unlink($cekfile);
        }

        $folder = realpath(dirname(getcwd())).'/gui_front_02/digital/joborder/';
        $file = $folder.$signatureFileName;
        file_put_contents($file, $data);

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'TTD (Diperiksa Oleh) telah disimpan.'
        ];
        return response()->json($message);
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
            return Datatables::of(Joborder::with('customer1','customer2','tongkangs','kapal','vendor')->orderBy('created_at','desc'))->make(true);
    }

    public function exportPDF(){
        $request = $_GET['no_joborder'];

        $joborder = Joborder::with('customer1','customer2','kapal','tongkangs')->where('no_joborder', $request)->first();

        $jobrequest = JoborderDetail::where('no_joborder',$request)->get();
        $user = $joborder->created_by;

        $tgl = $joborder->tgl_joborder;
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
        
        $pdf = PDF::loadView('/admin/joborder/pdf', compact('jobrequest','user','nama','nama2','dt','request','date','joborder','tgl'));
        $pdf->setPaper([0, 0, 684, 792], 'potrait');
        return $pdf->stream('Laporan Job Request '.$request.'.pdf');
    }
    
    public function exportPDF2(){
        $request = $_GET['no_joborder'];

        $joborder = Joborder::with('customer1','customer2','kapal','tongkangs')->where('no_joborder', $request)->first();

        $jobrequest = JobrequestDetail::where('no_joborder',$request)->get();

        $user = $joborder->created_by;

        $tgl = $joborder->tgl_joborder;
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
        
        $pdf = PDF::loadView('/admin/joborder/pdf2', compact('user','nama','nama2','dt','request', 'jobrequest','date','joborder','tgl'));
        $pdf->setPaper([0, 0, 684, 792], 'potrait');
        return $pdf->stream('Laporan Job Request '.$request.'.pdf');
    }

    public function detail($joborder)
    {
        $joborder = Joborder::find($joborder);
        $no_joborder = $joborder->no_joborder;

        $tgl = $joborder->tgl_joborder;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $Satuan = Satuan::pluck('nama_satuan','kode_satuan');

        $joborderdetail = JoborderDetail::where('no_joborder', $joborder->no_joborder)->orderBy('created_at','desc')->get();

        $list_url= route('joborder.index');
                    
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.joborderdetail.index', compact('joborder','joborderdetail','list_url','period','nama_lokasi','nama_company','Satuan'));
    }

    public function detail2($joborder)
    {
        $joborder = Joborder::find($joborder);
        $no_joborder = $joborder->no_joborder;

        $tgl = $joborder->tgl_joborder;
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

        $joborderdetail = JoborderDetail::where('no_joborder', $joborder->no_joborder)->orderBy('created_at','desc')->get();
        $Size = Sizecontainer::select('kode_size', DB::raw("concat(kode_size,' - ',nama_size) as sizes"))->pluck('sizes','kode_size');

        $Alat = Alat::select('kode_alat', DB::raw("concat(nama_alat,' - ',no_asset_alat) as alats"))->pluck('alats','kode_alat');

        $list_url= route('joborder.index');
                    
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.jobrequestdetail.index', compact('joborder','joborderdetail','list_url','period','nama_lokasi','nama_company','Size','Alat'));
    }

    public function Showdetail()
    {
        $joborder= JoborderDetail::where('no_joborder',request()->id)->get();
        $output = array();
            
        foreach ($joborder as $row) {
            $output[] = array(
                'deskripsi'=>$row->deskripsi,
                'qty'=>$row->qty,
                'satuan'=>$row->satuan,
                'harga'=>$row->harga,
                'mob_demob'=>$row->mob_demob,
                'total_harga'=>$row->total_harga,
            );
        }
            
        return response()->json($output);
    }

    public function Showdetailjor()
    {
        $jobrequest= JobrequestDetail::with('alat')->where('no_joborder',request()->id)->orderBy('created_at', 'desc')->get();
        $output = array();

        foreach($jobrequest as $row)
        {
            $no_req_jo = $row->no_jobrequest;
            $kode_alat = $row->kode_alat;
            $tgl_request = $row->tgl_request;

            $alat = Alat::find($kode_alat);
            $output[] = array(
                'no_jobrequest'=>$no_req_jo,
                'kode_alat'=>$alat->no_asset_alat,
                'tgl_request'=>$tgl_request,
            );
        }
        return response()->json($output);
    }

    public function Post()
    {
        $level = auth()->user()->level;

        // $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();
        $permintaan = Joborder::find(request()->id);
        $tgl = $permintaan->tgl_joborder;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $permintaan->status = "2";
        $permintaan->save();

        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Post Job Order: '.$permintaan->no_joborder.'.','created_by'=>$nama,'updated_by'=>$nama];
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

        $permintaan = Joborder::find(request()->id);

        $tgl = $permintaan->tgl_joborder;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $permintaan->status = "1";
        $permintaan->save();

        $signatureFileName1 = request()->id.'-dibuat'.'.png';
        $cekfile1 = realpath(dirname(getcwd())).'/gui_front_02/digital/joborder/'.$signatureFileName1;
        if (file_exists($cekfile1)) {
            unlink($cekfile1);
        }

        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Job Order: '.$permintaan->no_joborder.'.','created_by'=>$nama,'updated_by'=>$nama];

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
        $tgl = $request->tgl_joborder;
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
        
        if ($request->kode_customer == null || $request->kode_customer == '') {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Customer belum di isi.'
            ];
            return response()->json($message);
        }
        
        Joborder::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_joborder()
    {
        $no_joborder = request()->no_joborder;
        $data = Joborder::find($no_joborder);
        $output = array(
            'no_joborder'=>$data->no_joborder,
            'tgl_joborder'=>$data->tgl_joborder,
            'type_jo'=>$data->type_jo,
            'no_spk'=>$data->no_spk,
            'kode_customer'=>$data->kode_customer,
            'kode_consignee'=>$data->kode_consignee,
            'order_by'=>$data->order_by,
            'kode_kapal'=>$data->kode_kapal,
            'kode_vendor'=>$data->kode_vendor,
            'type_kegiatan'=>$data->type_kegiatan,
            'type_cargo'=>$data->type_cargo,
            'tgl_muat'=>$data->tgl_muat,
            'tgl_selesai'=>$data->tgl_selesai,
            'bongkar_muat_via'=>$data->bongkar_muat_via,
            'no_reff'=>$data->no_reff,
            'tgl_reff'=>$data->tgl_reff,
            'kode_kapal'=>$data->kode_kapal,
            'tongkang'=>$data->tongkang,
            'status_lokasi'=>$data->status_lokasi,
            'order_by'=>$data->order_by,
            'lokasi'=>$data->lokasi,
            'mob_demob'=>$data->mob_demob,
            'periode'=>$data->periode,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_joborder = $request->no_joborder;

        $tgl = $request->tgl_joborder;
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

        Joborder::find($request->no_joborder)->update($request->all());
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_joborder()
    {   
        $no_joborder = request()->id;
        $joborder = Joborder::find(request()->id);
        if ($joborder->type_jo == '4') {
            $cek_jor = Jobrequest::where('no_joborder',$no_joborder)->first();
        }else {
            $cek_jor = JoborderDetail::where('no_joborder',$no_joborder)->first();
        }
        
        if($cek_jor == null){
            $joborder->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$joborder->no_joborder.'] telah dihapus.'
            ];
            return response()->json($message);
        }
        else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Harap kosongkan detail ['.$joborder->no_joborder.'].'
            ];
            return response()->json($message);
        }
    }
}
