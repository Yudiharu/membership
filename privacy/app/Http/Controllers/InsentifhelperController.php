<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Customer;
use App\Models\JoborderDetail;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Helper;
use App\Models\Alat;
use App\Models\PemakaianAlat;
use App\Models\Insentifhelper;
use App\Models\InsentifhelperDetail;
use App\Models\HistoryPremi;
use App\Models\PemakaianAlatDetail;
use Carbon;
use DB;
use PDF;

class InsentifhelperController extends Controller
{
    public function index()
    {
        $create_url = route('insentifhelper.create');
        $Company = Company::pluck('nama_company','kode_company');
        
        $Customer = Customer::where('status','Aktif')->pluck('nama_customer','id');
        $Helper = Helper::where('status_insentif','1')->pluck('nama_helper','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.insentifhelper.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer','Company','Helper'));
    }


    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Insentifhelper::with('helper')->orderBy('created_at','desc'))->make(true);
    }

    public function exportPDF(){
        $request = $_GET['no_insentif'];

        $detail = InsentifhelperDetail::where('no_insentif', $request)->first();
        $detailexport = InsentifhelperDetail::where('no_insentif', $request)->get();
        $header = Insentifhelper::with('helper')->where('no_insentif',$request)->first();

        $tgl = $detail->tgl_pakai;

        $date=date_create($tgl);
        
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        $company = auth()->user()->kode_company;

        $user = auth()->user()->name;
        
        $pdf = PDF::loadView('/admin/insentifhelper/pdf', compact('user','detail','header','nama','nama2','dt','request','date','tgl','detailexport','user'));
        $pdf->setPaper([0, 0, 684, 792], 'potrait');
        return $pdf->stream('Report Premi Helper '.$request.'.pdf');
    }

    public function detail($joborder)
    {
        $insentif = Insentifhelper::find($joborder);
        $no_insentif = $insentif->no_insentif;
        $type_helper = $insentif->type_helper;
        $helper = $insentif->kode_helper;
        $tgldr = $insentif->tgl_pakai_dari;
        $tglsp = $insentif->tgl_pakai_sampai;

        $tgl = $insentif->tgl_insentif;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $insentifdetail = InsentifhelperDetail::where('no_insentif', $no_insentif)->orderBy('created_at','desc')->get();


        $Helper = Helper::find($helper);

        $list_url= route('insentifhelper.index');

        if($insentif->type_helper == '1')
        {
            $type = 'Helper 1';
        }
        else
        {
            $type = 'Helper2';
        }
                    
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.insentifhelperdetail.index', compact('insentif','insentifdetail','list_url','period','nama_lokasi','nama_company','no_insentif','tgldr','tglsp','Helper','helper','type_helper','type'));
    }

    public function Showdetail()
    {
        $getdetail = InsentifhelperDetail::where('no_insentif', request()->id)->get();
        $output = array();

        foreach($getdetail as $row)
        {
            $output[] = array(
                'no_timesheet'=>$row->no_timesheet,
                'no_pemakaian'=>$row->no_pemakaian,
                'no_joborder'=>$row->no_joborder,
                'tgl_pakai'=>$row->tgl_pakai,
                'hari_libur'=>$row->hari_libur,
                'premi_dalamkota'=>$row->premi_dalamkota,
                'premi_luarkota'=>$row->premi_luarkota,
                'premi_libur'=>$row->premi_libur,
                'total_insentif'=>$row->total_insentif,
            );
        }

        return response()->json($output);
    }


    public function store(Request $request)
    {
        Insentifhelper::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function Post()
    {
        $level = auth()->user()->level;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $getheader = Insentifhelper::find(request()->id);

        $getdetail = InsentifhelperDetail::where('no_insentif', request()->id)->get();

        $gethelper = Helper::where('id',$getheader->kode_helper)->first();
        
        foreach ($getdetail as $row)
        {
            $getalatdetail = PemakaianAlatDetail::where('no_timesheet', $row->no_timesheet)->first();

            if ($getheader->type_helper == '1')
            {
                $getalatdetail->no_insentif_helper1 = request()->id;
            }
            else
            {
                $getalatdetail->no_insentif_helper2 = request()->id;
            }

            $getalatdetail->save();

        }

        $getheader->status = 'POSTED';
        $getheader->save();

        $history = [
            'nik'=>$gethelper->nik,
            'no_rekening'=>$gethelper->no_rekening,
            'nama'=>$gethelper->nama_helper,
            'premi'=>$getheader->gt_insentif,
            'type'=>'Helper',
            'tgl_insentif'=>$getheader->tgl_insentif,
            'no_insentif'=>$getheader->no_insentif,
        ];

        HistoryPremi::create($history);

        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Post Premi Helper: '.request()->id.'.','created_by'=>$nama,'updated_by'=>$nama];
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

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        $getheader = Insentifhelper::find(request()->id);
        $getdetail = InsentifhelperDetail::where('no_insentif', request()->id)->get();

        foreach ($getdetail as $row)
        {
            $getalatdetail = PemakaianAlatDetail::where('no_timesheet', $row->no_timesheet)->first();

            if ($getheader->type_helper == '1')
            {
                $getalatdetail->no_insentif_helper1 = null;
            }
            else
            {
                $getalatdetail->no_insentif_helper2 = null;
            }

            $getalatdetail->save();

        }

        HistoryPremi::where('no_insentif',$getheader->no_insentif)->delete();

        $getheader->status = 'OPEN';
        $getheader->save();

        $nama = auth()->user()->name;
        $tmp = ['nama' => $nama,'aksi' => 'Unpost Premi Helper: '.request()->id.'.','created_by'=>$nama,'updated_by'=>$nama];
        user_history::create($tmp);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data berhasil di UNPOST.'
        ];
        return response()->json($message);
        
    }

    public function edit_insentif()
    {
        $no_insentif = request()->no_insentif;
        $data = Insentifhelper::find($no_insentif);
        $output = array(
            'no_insentif'=>$data->no_insentif,
            'tgl_insentif'=>$data->tgl_insentif,
            'type_helper'=>$data->type_helper,
            'kode_helper'=>$data->kode_helper,
            'tgl_pakai_dari'=>$data->tgl_pakai_dari,
            'tgl_pakai_sampai'=>$data->tgl_pakai_sampai,
            'keterangan'=>$data->keterangan,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        Insentifhelper::find($request->no_insentif)->update($request->all());
       
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
        $insentifhelper = Insentifhelper::find(request()->id);
        $cek_jor = InsentifhelperDetail::where('no_insentif', $no_insentif)->first();
        if($cek_jor == null){
            $insentifhelper->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$insentifhelper->no_insentif.'] telah dihapus.'
            ];
            return response()->json($message);
        }
        else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Hapus terlebih dahulu detail ['.$insentifhelper->no_insentif.'].'
            ];
            return response()->json($message);
        }
    }
}
