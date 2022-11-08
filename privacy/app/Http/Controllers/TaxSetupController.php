<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\TaxSetup;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use Carbon;
use Alert;

class TaxSetupController extends Controller
{
    public function index()
    {
        
        $create_url = route('taxsetup.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.taxsetup.index',compact('create_url','period', 'nama_lokasi','nama_company'));

    }

    public function anyData()
    {
        return Datatables::of(TaxSetup::query())
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Hapus"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
                           })
            ->make(true);

    }

    public function store(Request $request)
    {
        $kode_pajak = $request->kode_pajak;
        $cek = TaxSetup::where('kode_pajak',$kode_pajak)->first();
        if($cek == null){
            TaxSetup::create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah di Disimpan.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Kode Pajak Sudah Ada',
            ];
            return response()->json($message);
        }
    }

    public function edit_taxsetup()
    {
        $id_pajak = request()->id;
        $data = TaxSetup::find($id_pajak);
        $output = array(
            'id_pajak'=>$data->id_pajak,
            'kode_pajak'=>$data->kode_pajak,
            'nama_pajak'=>$data->nama_pajak,
            'nilai_pajak'=>$data->nilai_pajak,
            'tgl_berlaku'=>$data->tgl_berlaku,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        TaxSetup::find($request->id_pajak)->update($request->all());
   
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_taxsetup()
    {   
        $taxsetup = TaxSetup::find(request()->id);

        $taxsetup->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$taxsetup->nama_pajak.'] telah dihapus.'
        ];
        return response()->json($message);
    }
}
