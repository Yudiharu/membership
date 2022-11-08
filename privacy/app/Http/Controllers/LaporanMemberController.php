<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\MasterLokasi;
use App\Models\tb_akhir_bulan;
use App\Models\Company;
use App\Models\Member;
use App\Exports\RekapMemberExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class LaporanMemberController extends Controller
{

    public function index()
    {
        $create_url = route('laporanmember.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        DB::update("UPDATE member SET umur=DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tanggal_lahir)), '%Y')+0");

        $Member = Member::select('nik',DB::raw("concat(nik,' - ',nama) as namas"))->where('jabatan','like','%mandor%')->pluck('namas','nik');

        return view('admin.laporanmember.index',compact('Member','create_url','period','nama_lokasi'));
    }

    public function exportPDF()
    {

        $report = $_GET['jenis_report'];
        $mandor = $_GET['mandor'];
        
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;
        $date = date("Y-m-d h-m-i");
        if(isset($_GET['ttd'])){
            $format_ttd = $_GET['ttd']; 
        }else{
            $format_ttd = 0;
        }

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();
        
        if ($report=='PDF'){
            $member = Member::where(DB::raw("LEFT(nik,1)"), 'like', substr($mandor, 0,1).'%')->where('status','Aktif')->get();
            $pdf = PDF::loadView('/admin/laporanmember/pdf', compact('member','nama','nama2','dt','date','mandor','format_ttd'));
            $pdf->setPaper('a4','landscape');

            return $pdf->stream('Laporan Rekap Tenaga Kerja Mandor '.$mandor.'.pdf');
        }else if ($report == 'excel'){
            return Excel::download(new RekapMemberExport($mandor), 'Laporan Rekap Tenaga Kerja '.$mandor.'.xlsx');
        }
    }
}
