<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Vendor;
use App\Models\Mobil;
use App\Models\Spb;
use App\Models\Joborder;
use App\Models\JobrequestDetail;
use App\Models\MasterLokasi;
use App\Models\JoborderDetail;
use App\Models\tb_akhir_bulan;
use App\Models\Trucking;
use App\Models\TruckingDetail;
use App\Models\Truckingnon;
use App\Models\TruckingnonDetail;
use App\Models\Signature;
use App\Models\Company;
use App\Models\Customer;
use App\Exports\Rekap_percontainerExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class Laporanrekap_percontainerController extends Controller
{

    public function index()
    {
        $create_url = route('laporanrekap_percontainer.create');
        $Container = TruckingDetail::pluck('kode_container','kode_container');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporanrekap_percontainer.index',compact('create_url','Container','period','nama_lokasi'));
    }

    public function exportPDF(){
        $kode_container = $_GET['kode_container'];
        $report = $_GET['jenis_report'];
        if(isset($_GET['ttd'])){
            $format_ttd = $_GET['ttd']; 
        }else{
            $format_ttd = 0;
        }
        $ttd = auth()->user()->name;
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;
        $date = date("Y-m-d h-m-i");

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        if ($report=='PDF'){
            $truck = TruckingDetail::where('kode_container',$kode_container)->first();
            $cetak_info = TruckingDetail::select('trucking_detail.*','spb.tgl_spb','spb.kode_mobil','spb.kode_sopir','jobrequest_detail.status_muatan','jobrequest_detail.tujuan')->with('mobil','sopir')->join('spb','spb.no_spb','=','trucking_detail.no_spb')->join('jobrequest_detail','jobrequest_detail.kode_container','=','trucking_detail.kode_container')->where('trucking_detail.kode_container',$kode_container)->get();

            $pdf = PDF::loadView('/admin/laporanrekap_percontainer/pdf', compact('kode_container','cetak_info','date','truck','nama','nama2','dt','ttd','format_ttd'));
            $pdf->setPaper('a4','landscape');

            return $pdf->stream('Laporan Rekap SPB/Container '.$kode_container.'.pdf');
        }else if ($report == 'excel'){
            return Excel::download(new Rekap_percontainerExport($kode_container), 'Laporan Rekap SPB Per Container '.$kode_container.'.xlsx');
        }
    }
}
