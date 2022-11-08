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
use App\Models\Satuan;
use App\Models\Insentifoperator;
use App\Models\InsentifoperatorDetail;
use App\Models\PemakaianAlatDetail;
use App\Models\Operator;
use App\Models\HistoryPremi;
use Carbon;
use DB;
use PDF;

class InsentifoperatorController extends Controller
{
    public function index()
    {
        $create_url = route('joborder.create');
        $Company = Company::pluck('nama_company','kode_company');
        $Kapal = Kapal::on('mysqlinvpbm')->where('type','Tug Boat')->orwhere('type','MV')->pluck('nama_kapal','kode_kapal');
        $Tongkang = Kapal::on('mysqlinvpbm')->where('type','Tongkang')->pluck('nama_kapal','kode_kapal');
        $Operator = Operator::where('status_insentif', '1')->pluck('nama_operator','id');
        $Vendor = Vendor::pluck('nama_vendor','id');
        $Cargo = TypeCargo::pluck('type_cargo','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.insentifoperator.index',compact('create_url','period', 'nama_lokasi','nama_company','Operator','Company','Kapal','Vendor','Tongkang','Cargo'));
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
            return Datatables::of(Insentifoperator::with('operator')->orderBy('created_at','desc'))->make(true);
    }

    public function exportPDF(){
        $request = $_GET['no_insentif'];
        $insoperator = Insentifoperator::with('operator')->where('no_insentif', $request)->first();

        $insoperatordetail = InsentifoperatorDetail::where('no_insentif',$request)->get();
        $user = $insoperator->created_by;

        $tgl = $insoperator->tgl_insentif;
        $date=date_create($tgl);
        
        $total_qty = 0;

        $operator = Operator::find($insoperator->kode_operator);
        
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        $company = auth()->user()->kode_company;
        
        $pdf = PDF::loadView('/admin/insentifoperator/pdf', compact('request','insoperator','insoperatordetail','user','date','tgl','total_qty','nama','nama2','dt','company','operator'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Laporan Job Request '.$request.'.pdf');
    }

    public function detail($joborder)
    {
        $insentif = Insentifoperator::find($joborder);
        $no_insentif = $insentif->no_insentif;

        $tgl = $insentif->tgl_insentif;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $Satuan = Satuan::pluck('nama_satuan','kode_satuan');
        $Operator = Operator::find($insentif->kode_operator);

        $insentifdetail = InsentifoperatorDetail::where('no_insentif', $no_insentif)->orderBy('created_at','desc')->get();

        $list_url= route('insentifoperator.index');
                    
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.insentifoperatordetail.index', compact('insentif','insentifdetail','list_url','period','nama_lokasi','nama_company','Satuan','Operator'));
    }

    public function Showdetail()
    {
        $insentifdetail = InsentifoperatorDetail::where('no_insentif',request()->id)->orderBy('tgl_pakai')->get();
        $output = array();
        foreach ($insentifdetail as $row) {
            $output[] = array(
                'no_timesheet'=>$row->no_timesheet,
                'no_pemakaian'=>$row->no_pemakaian,
                'no_joborder'=>$row->no_joborder,
                'tgl_pakai'=>$row->tgl_pakai,
                'hari_libur'=>$row->hari_libur,
                'total_jam'=>$row->total_jam,
                'total_hm'=>$row->total_hm,
                'premi_perjam'=>$row->premi_perjam,
                'premi_libur'=>$row->premi_libur,
                'total_insentif'=>$row->total_insentif,
                'luar_kota'=>$row->luar_kota,
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

        $insdetail = InsentifoperatorDetail::where('no_insentif', request()->id)->get();
        foreach ($insdetail as $row) {
            $cekts = PemakaianAlatDetail::where('no_timesheet', $row->no_timesheet)->first();
            $cekts->no_insentif = request()->id;
            $cekts->save();
        }

        $insentifoperator = Insentifoperator::find(request()->id);
        $insentifoperator->status = "POSTED";
        $insentifoperator->save();

        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Post No Insentif Operator: '.$insentifoperator->no_insentif.'.','created_by'=>$nama,'updated_by'=>$nama];
        user_history::create($tmp);

        $opera = Operator::find($insentifoperator->kode_operator);
        $history = [
            'nik'=>$opera->nik,
            'no_rekening'=>$opera->no_rekening,
            'nama'=>$opera->nama_operator,
            'premi'=>$insentifoperator->gt_insentif,
            'type'=>'Operator',
            'tgl_insentif'=>$insentifoperator->tgl_insentif,
            'no_insentif'=>$insentifoperator->no_insentif,
        ];
        HistoryPremi::create($history);

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

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $insdetail = InsentifoperatorDetail::where('no_insentif', request()->id)->get();
        foreach ($insdetail as $row) {
            $cekts = PemakaianAlatDetail::where('no_timesheet', $row->no_timesheet)->first();
            $cekts->no_insentif = null;
            $cekts->save();
        }
    
        $insentifoperator = Insentifoperator::find(request()->id);
        $insentifoperator->status = "OPEN";
        $insentifoperator->save();
    
        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Insentif Operator: '.$insentifoperator->no_insentif.'.','created_by'=>$nama,'updated_by'=>$nama];
        user_history::create($tmp);

        $historia = HistoryPremi::where('no_insentif', $insentifoperator->no_insentif)->delete();
    
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data berhasil di UNPOST.'
        ];
        return response()->json($message);
    }

    public function store(Request $request)
    {
        Insentifoperator::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_insentif()
    {
        $no_insentif = request()->no_insentif;
        $data = Insentifoperator::find($no_insentif);
        $output = array(
            'no_insentif'=>$data->no_insentif,
            'tgl_insentif'=>$data->tgl_insentif,
            'kode_operator'=>$data->kode_operator,
            'tgl_pakai_dari'=>$data->tgl_pakai_dari,
            'tgl_pakai_sampai'=>$data->tgl_pakai_sampai,
            'keterangan'=>$data->keterangan,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_insentif = $request->no_insentif;

        $tgl = $request->tgl_insentif;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        Insentifoperator::find($request->no_insentif)->update($request->all());
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_insentif()
    {   
        $no_insentif = request()->id;
        $insentif = Insentifoperator::find(request()->id);
        $cek_jor = InsentifoperatorDetail::where('no_insentif',$no_insentif)->first();
        if($cek_jor == null){
            $insentif->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$insentif->no_insentif.'] telah dihapus.'
            ];
            return response()->json($message);
        }
        else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Hapus terlebih dahulu pada detail ['.$insentif->no_insentif.'].'
            ];
            return response()->json($message);
        }
    }
}
