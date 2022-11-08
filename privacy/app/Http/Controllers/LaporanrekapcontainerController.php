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
use App\Exports\RekapcontainerExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class LaporanrekapcontainerController extends Controller
{

    public function index()
    {
        $create_url = route('laporanrekapcontainer.create');
        $Joborder1 = Trucking::pluck('no_joborder','no_joborder');
        $Joborder2 = Truckingnon::pluck('no_joborder','no_joborder');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporanrekapcontainer.index',compact('create_url','Joborder1','Joborder2','period','nama_lokasi'));
    }

    public function getdata(){
        $job = JoborderDetail::where('no_joborder',Request()->id)->first();
        if ($job != null){
            $result = [
                'truktipe'=>'Container'
            ];
        }else{
            $result = [
                'truktipe'=>'Non Container'
            ];
        }
        return response()->json($result);
    }

    public function exportPDF(){
       
        $nojo = $_GET['no_joborder'];
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
        $cekdetail = JoborderDetail::where('no_joborder',$nojo)->first();

        if ($report=='PDF'){
            if ($cekdetail != null){
                $truck = Trucking::where('no_joborder',$nojo)->first();
                $cek_shipper = Customer::where('id',$truck->kode_shipper)->first();
                $cetak_job = TruckingDetail::select('trucking_detail.*','spb.*','trucking.*','vendor.nama_vendor')->with('mobil','gudangdetail','sopir')->join('spb','spb.no_spb','=','trucking_detail.no_spb')->join('trucking','trucking.no_trucking','=','trucking_detail.no_trucking')->join('u5611458_db_pusat.vendor','spb.kode_pemilik','=','u5611458_db_pusat.vendor.id')->where('trucking_detail.no_trucking',$truck->no_trucking)->get();
                
                $grandtotal1 = $cetak_job->sum('uang_jalan');
                $grandtotal2 = $cetak_job->sum('bpa');
                $grandtotal3 = $cetak_job->sum('honor');
                $grandtotal4 = $cetak_job->sum('biaya_lain');
                $grandtotal5 = $cetak_job->sum('trucking');

                $pdf = PDF::loadView('/admin/laporanrekapcontainer/pdf', compact('nojo','cetak_job','date','truck','cekdetail','grandtotal1','grandtotal2','grandtotal3','grandtotal4','grandtotal5','nama','nama2','dt','cek_shipper','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Laporan Rekap SPB Container No JO '.$nojo.'.pdf');
            }else {
                $trucknon = Truckingnon::where('no_joborder',$nojo)->first();
                $cetak_job = TruckingnonDetail::select('truckingnoncontainer_detail.*','trucking_noncontainer.*','vendor.nama_vendor')->with('mobil','sopir')->join('trucking_noncontainer','trucking_noncontainer.no_truckingnon','=','truckingnoncontainer_detail.no_truckingnon')->join('u5611458_db_pusat.vendor','truckingnoncontainer_detail.kode_pemilik','=','u5611458_db_pusat.vendor.id')->where('truckingnoncontainer_detail.no_truckingnon',$trucknon->no_truckingnon)->get();

                $grandtotal1 = $cetak_job->sum('tarif_gajisopir');
                $grandtotal2 = $cetak_job->sum('uang_jalan');
                $grandtotal3 = $cetak_job->sum('bbm');

                $pdf = PDF::loadView('/admin/laporanrekapcontainer/pdf', compact('nojo','cetak_job','date','trucknon','cekdetail','grandtotal1','grandtotal2','grandtotal3','nama','nama2','dt','cek_shipper','ttd','format_ttd'));
                $pdf->setPaper('a4','landscape');

                return $pdf->stream('Laporan Rekap SPB Non-Container No JO '.$nojo.'.pdf');
            }
        }else if ($report == 'excel'){
            if ($cekdetail != null){
                return Excel::download(new RekapcontainerExport($nojo, $cekdetail), 'Laporan Rekap SPB Container No JO '.$nojo.'.xlsx');
            }else {
                $cekdetail = 'null';
                return Excel::download(new RekapcontainerExport($nojo, $cekdetail), 'Laporan Rekap SPB Non Container No JO '.$nojo.'.xlsx');
            }
        }else if ($report == 'txt') {
            if ($cekdetail != null){
                $truck = Trucking::where('no_joborder',$nojo)->first();
                $cetak_job = TruckingDetail::select('trucking_detail.*','spb.*','trucking.*')->with('mobil','gudangdetail','sopir')->join('spb','spb.no_spb','=','trucking_detail.no_spb')->join('trucking','trucking.no_trucking','=','trucking_detail.no_trucking')->where('trucking_detail.no_trucking',$truck->no_trucking)->get();
                $file= "Rekap Container JO ".$date.".txt";
                Storage::disk('c_path')->put('Rekap SPB Container JO'.$date.'.txt', $cetak_job);
                // File::put($file,$cetak_job);
            }else {
                $trucknon = Truckingnon::where('no_joborder',$nojo)->first();
                $cetak_job = TruckingnonDetail::select('truckingnoncontainer_detail.*','trucking_noncontainer.*')->with('mobil','sopir')->join('trucking_noncontainer','trucking_noncontainer.no_truckingnon','=','truckingnoncontainer_detail.no_truckingnon')->where('truckingnoncontainer_detail.no_truckingnon',$trucknon->no_truckingnon)->get();
                $file= "Rekap SPB Non Container JO ".$date.".txt";
                File::put($file,$cetak_job);
            }
        }
    }
}
