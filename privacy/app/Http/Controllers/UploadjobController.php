<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Vendor;
use App\Models\Joborder;
use App\Models\JobrequestDetail;
use App\Models\JoborderDetail;
use App\Models\MasterLokasi;
use App\Models\tb_akhir_bulan;
use App\Models\Signature;
use App\Models\Company;
use App\Models\Customer;
use App\Exports\RekapjoExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use DB;
use Carbon;
use Response;
use Storage;
use File;

class UploadjobController extends Controller
{

    public function index()
    {
        $create_url = route('uploadjob.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.uploadjob.index',compact('create_url','Joborder','period','nama_lokasi'));
    }

    public function exportPDF()
    {
        $jam_awal =' 00:00:00';
        $jam_akhir =' 23:59:59';
        $tanggal_awal = $_GET['tanggal_awal'];
        $tanggal_akhir = $_GET['tanggal_akhir'];

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;
        $date = date("Y-m-d h-m-i");

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        
                $fileName = 'JO.csv';
                $tasks = Joborder::with('customer1')->whereBetween('updated_at', array($tanggal_awal.$jam_awal, $tanggal_akhir.$jam_akhir))->where('status','<>','1')->get();

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );

                $columns = array('company_code','no_transaksi', 'tanggal_transaksi', 'type_jo', 'no_reff', 'tgl_reff', 'customer', 'shipper', 'consignee', 'order_by', 'kode_kapal', 'voyage', 'port_loading', 'etd', 'port_transite', 'port_destination', 'eta', 'shipping_line', 'customs_clearance', 'no_pengajuan', 'no_pib/peb', 'master_b/l', 'no_bc23', 'house_bl', 'no_si/do', 'loading_status', 'total_container', 'status', 'created_by', 'date_create', 'time_create', 'updated_by', 'date_update', 'time_update');

                $callback = function() use($tasks, $columns) {
                    $file = fopen('php://output', 'w');
                    // fputcsv($file, $columns,chr(59));

                    foreach ($tasks as $task) {
                        $row['company_code']  = 1;
                        $row['no_joborder']  = $task->no_joborder;
                        $row['tanggal_jo'] = Carbon\Carbon::parse($task->tanggal_jo)->format('d/m/Y');
                        $row['type']  = $task->type;
                        $row['no_reff']  = $task->no_reff;
                        $row['tgl_reff']  = $task->tgl_reff;
                        $row['kode_customer']  = $task->kode_customer;
                        $row['kode_shipper']  = $task->kode_shipper;
                        $row['kode_consignee']  = $task->kode_consignee;
                        $row['order_by']  = $task->order_by;
                        $row['kode_kapal']  = $task->kode_kapal;
                        $row['voyage']  = $task->voyage;
                        $row['port_loading']  = $task->port_loading;
                        $row['etd']  = Carbon\Carbon::parse($task->etd)->format('d/m/Y');
                        $row['port_transite']  = $task->port_transite;
                        $row['port_destination']  = $task->port_destination;
                        $row['eta']  = Carbon\Carbon::parse($task->eta)->format('d/m/Y');
                        $row['shipping_line']  = $task->shipping_line;
                        $row['customs_clearance']  = $task->customs_clearance;
                        $row['no_pengajuan']  = $task->no_pengajuan;
                        $row['no_pibpeb']  = $task->no_pibpeb;
                        $row['master_bl']  = $task->master_bl;
                        $row['no_bc']  = $task->no_bc;
                        $row['house_bl']  = $task->house_bl;
                        $row['no_do']  = $task->no_do;
                        $row['loading_type']  = $task->loading_type;
                        $row['total_item']  = $task->total_item;
                        $row['status']  = $task->status;
                        $row['created_at']  = $task->created_at;
                        $row['updated_at']  = $task->updated_at;
                        $row['created_by']  = $task->created_by;
                        $row['updated_by']  = $task->updated_by;
                        $tgl_create = Carbon\Carbon::parse($row['created_at'])->format('d/m/Y');
                        $jam_create = Carbon\Carbon::parse($row['created_at'])->format('H:i:s');
                        $tgl_update = Carbon\Carbon::parse($row['updated_at'])->format('d/m/Y');
                        $jam_update = Carbon\Carbon::parse($row['updated_at'])->format('H:i:s');

                        fputcsv($file, array($row['company_code'], $row['no_joborder'], $row['tanggal_jo'], $row['type'], $row['no_reff'], $row['tgl_reff'], $row['kode_customer'], $row['kode_shipper'], $row['kode_consignee'], $row['order_by'], $row['kode_kapal'], $row['voyage'], $row['port_loading'], $row['etd'], $row['port_transite'], $row['port_destination'], $row['eta'], $row['shipping_line'], $row['customs_clearance'], $row['no_pengajuan'], $row['no_pibpeb'], $row['master_bl'], $row['no_bc'], $row['house_bl'], $row['no_do'], $row['loading_type'], $row['total_item'], $row['status'], $row['created_by'], $tgl_create, $jam_create, $row['updated_by'], $tgl_update, $jam_update),chr(59));
                    }

                    fclose($file);
                };
                return response()->stream($callback, 200, $headers);
            
    }
}
