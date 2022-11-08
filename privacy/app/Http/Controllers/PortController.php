<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Port;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Joborder;
use Carbon;
use PDF;
use Excel;
use DB;

class PortController extends Controller
{
    public function index()
    {
        $create_url = route('port.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');

        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.port.index',compact('create_url','period', 'nama_lokasi','nama_company'));

    }

    public function anyData()
    {
        return Datatables::of(Port::query())
           ->addColumn('action', function ($query){
            return '<a href="javascript:;" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit"> <i class="fa fa-edit"></i></a>'.'&nbsp'.          
              '<a href="javascript:;" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Hapus"> <i class="fa fa-times-circle"></i> </a>';
                           })
            ->make(true);

    }
    
    public function store(Request $request)
    {
        $nama_port = $request->nama_port;
        $cek_port = Port::where('nama_port',$nama_port)->first();
        if($cek_port != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Nama port sudah ada.'
            ];
            return response()->json($message);
        }
           
        $port = Port::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_port()
    {
        $kode_port = request()->id;
        $data = Port::find($kode_port);
        $output = array(
            'kode_port'=> $data->kode_port,
            'nama_port'=> $data->nama_port,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_port = $request->kode_port;
        $nama_port = $request->nama_port;
        $cek_port = Port::where('nama_port',$nama_port)->first();
        if($cek_port != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Nama port sudah ada.'
            ];
            return response()->json($message);
        }
        $cek_loading = Joborder::where('port_loading',$request->kode_port)->first();
        $cek_transite = Joborder::where('port_transite',$request->kode_port)->first();
        $cek_destination = Joborder::where('port_destination',$request->kode_port)->first();
        if($cek_loading == null && $cek_transite == null && $cek_destination == null){
            $request->validate([
                'kode_port'=>'required',
                'nama_port'=> 'required',
            ]);

            $nama_port = $request->nama_port;
            $cek_kode= substr($kode_port,0,1);
            $cek_nama = substr($nama_port,0,1);
            if($cek_kode == $cek_nama){
                Port::find($request->kode_port)->update($request->all());
               
                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data telah di Update.'
                ];
                return response()->json($message);
            }else{
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Huruf awal port ['.$cek_kode.'] tidak dapat diubah, karena kode port sudah terbentuk.'
                ];
                return response()->json($message);
            }
        }
        else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$request->kode_port.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }

    public function hapus_port()
    {   
        $kode_port = request()->id;
        $port = Port::find(request()->id);
        $cek_loading = Joborder::where('port_loading',$kode_port)->first();
        $cek_transite = Joborder::where('port_transite',$kode_port)->first();
        $cek_destination = Joborder::where('port_destination',$kode_port)->first();

        if ($cek_loading == null && $cek_transite == null && $cek_destination == null){
            $port = Port::find(request()->id);

            $port->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$port->nama_port.'] telah dihapus.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$port->nama_port.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }
}
