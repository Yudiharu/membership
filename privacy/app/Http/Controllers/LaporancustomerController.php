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

class LaporancustomerController extends Controller
{

    public function index()
    {
        $create_url = route('laporancustomer.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        return view('admin.laporancustomer.index',compact('create_url','Container','period','nama_lokasi'));
    }

    public function exportPDF(){
        $jam_awal =' 00:00:00';
        $jam_akhir =' 23:59:59';
        $tglawal = $_GET['tanggal_awal'];
        $tglakhir = $_GET['tanggal_akhir'];
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;
        $date = date("Y-m-d h-m-i");

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

            $fileName = 'customer.csv';
            $tasks = Customer::whereBetween('updated_at',array($tglawal.$jam_awal, $tglakhir.$jam_akhir))->get();

            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('kode_customer','nama_customer', 'nama_customer_faktur', 'tipe_customer', 'alamat1', 'alamat2', 'alamat3', 'alamat4', 'kota', 'kode_pos', 'phone1', 'phone2', 'fax', 'npwp', 'pic', 'contact_pic', 'type_company', 'cost_center', 'no_coa', 'status_aging','created_by', 'date_create', 'time_create', 'updated_by', 'date_update', 'time_update');

            $callback = function() use($tasks, $columns) {
                $file = fopen('php://output', 'w');
                // fputcsv($file, $columns,chr(59));

                foreach ($tasks as $task) {
                    $row['kode_customer']  = $task->kode_customer;
                    $row['nama_customer']  = $task->nama_customer;
                    $row['nama_customer_po']    = $task->nama_customer_po;
                    $row['tipe']  = substr($task->tipe,0,1);
                    $row['alamat']  = $task->alamat;
                    $row['alamat2']  = $task->alamat2;
                    $row['alamat3']  = $task->alamat3;
                    $row['alamat4']  = $task->alamat4;
                    $row['kota']  = $task->kota;
                    $row['kode_pos']  = $task->kode_pos;
                    $row['telp']  = $task->telp;
                    $row['hp']  = $task->hp;
                    $row['fax']  = $task->fax;
                    $row['npwp']  = $task->npwp;
                    $row['nama_kontak']  = $task->nama_kontak;
                    $row['contact_pic']  = $task->contact_pic;
                    $row['type_company']  = $task->type_company;
                    $row['cost_center']  = $task->cost_center;
                    $row['no_coa']  = $task->no_coa;
                    if($task->status_aging == 0){
                        $row['status_aging']  = 'False';
                    }else{
                        $row['status_aging']  = 'True';
                    }
                    $row['created_at']  = $task->created_at;
                    $row['updated_at']  = $task->updated_at;
                    $row['created_by']  = $task->created_by;
                    $row['updated_by']  = $task->updated_by;
                    $tgl_create = Carbon\Carbon::parse($row['created_at'])->format('d/m/Y');
                    $jam_create = Carbon\Carbon::parse($row['created_at'])->format('H:i:s');
                    $tgl_update = Carbon\Carbon::parse($row['updated_at'])->format('d/m/Y');
                    $jam_update = Carbon\Carbon::parse($row['updated_at'])->format('H:i:s');

                    fputcsv($file, array($row['kode_customer'], $row['nama_customer'], $row['nama_customer_po'], $row['tipe'], $row['alamat'], $row['alamat2'], $row['alamat3'], $row['alamat4'], $row['kota'], $row['kode_pos'], $row['telp'], $row['hp'], $row['fax'], $row['npwp'], $row['nama_kontak'], $row['contact_pic'], $row['type_company'], $row['cost_center'], $row['no_coa'], $row['status_aging'], $row['created_by'], $tgl_create, $jam_create, $row['updated_by'], $tgl_update, $jam_update),chr(59));
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
    }
}
