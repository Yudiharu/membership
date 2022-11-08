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
use App\Exports\RekaptglExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;


class Laporanrekap_pertglController extends Controller
{

    public function index()
    {
        $create_url = route('laporanrekap_pertgl.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporanrekap_pertgl.index',compact('create_url','period','nama_lokasi'));
    }

    public function exportPDF(){
        $tglawal = $_GET['tanggal_awal'];
        $tglakhir = $_GET['tanggal_akhir'];
        $report = $_GET['jenis_report'];
        $type = $_GET['type'];
        if(isset($_GET['ttd'])){
            $format_ttd = $_GET['ttd']; 
        }else{
            $format_ttd = 0;
        }
        $ttd = auth()->user()->name;

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;
        $date = date("Y-m-d");

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        if ($report=='PDF'){
            if($type == 'Container'){
                $cetakspb = Spb::select('spb.*','trucking_detail.*','trucking.*','vendor.nama_vendor')->with('mobil','sopir','gudangdetail','customer')->join('trucking_detail','trucking_detail.no_spb','=','spb.no_spb')->join('trucking','trucking.no_trucking','=','trucking_detail.no_trucking')->join('u5611458_db_pusat.vendor','spb.kode_pemilik','=','u5611458_db_pusat.vendor.id')->whereBetween('spb.tgl_kembali',array($tglawal,$tglakhir))->get();

                $grandtotal1 = $cetakspb->sum('uang_jalan');
                $grandtotal2 = $cetakspb->sum('bpa');
                $grandtotal3 = $cetakspb->sum('honor');
                $grandtotal4 = $cetakspb->sum('biaya_lain');
                $grandtotal5 = $cetakspb->sum('trucking');

                $pdf = PDF::loadView('/admin/laporanrekap_pertgl/pdf', compact('nojo','cetakspb','type','date','grandtotal1','grandtotal2','grandtotal3','grandtotal4','grandtotal5','nama','nama2','dt','tglawal','tglakhir','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Rekap SPB Container/Tgl Kembali SPB Periode '.$tglawal.' sd '.$tglakhir.'.pdf');
            }
            else{
                $cetakspbnon = TruckingnonDetail::select('truckingnoncontainer_detail.*','trucking_noncontainer.*','vendor.nama_vendor')->with('mobil','sopir','customer')->join('trucking_noncontainer','trucking_noncontainer.no_truckingnon','=','truckingnoncontainer_detail.no_truckingnon')->join('u5611458_db_pusat.vendor','truckingnoncontainer_detail.kode_pemilik','=','u5611458_db_pusat.vendor.id')->whereBetween('truckingnoncontainer_detail.tanggal_kembali',array($tglawal,$tglakhir))->get();

                $grandtotal1 = $cetakspbnon->sum('uang_jalan');
                $grandtotal5 = $cetakspbnon->sum('tarif_gajisopir');

                $pdf = PDF::loadView('/admin/laporanrekap_pertgl/pdf', compact('nojo','cetakspbnon','date','type','grandtotal1','grandtotal5','nama','nama2','dt','tglawal','tglakhir','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Rekap SPB Non-Container/Tgl Kembali SPB Periode '.$tglawal.' s/d '.$tglakhir.'.pdf');
            }
        }else if ($report == 'excel'){
            if($type == 'Container'){
                return Excel::download(new RekaptglExport($tglawal, $tglakhir, $type), 'Rekap SPB Container Per Tgl Kembali SPB Periode '.$tglawal.' sd '.$tglakhir.'.xlsx');
            }
            else{
                return Excel::download(new RekaptglExport($tglawal, $tglakhir, $type), 'Rekap SPB Non-Container Per Tgl Kembali SPB Periode '.$tglawal.' sd '.$tglakhir.'.xlsx');
            }
        }
    }
}
