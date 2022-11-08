<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Sizecontainer;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\JobrequestDetail;
use App\Models\TruckingDetail;
use Carbon;
use PDF;
use Excel;
use DB;

class SizecontainerController extends Controller
{
    public function index()
    {
        $create_url = route('sizecontainer.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');

        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.sizecontainer.index',compact('create_url','period', 'nama_lokasi','nama_company'));

    }

    public function getkode(){
        $get = JobrequestDetail::get();
        $leng = count($get);

        $data = array();

        foreach ($get as $rowdata){
            $kode_size = $rowdata->kode_size;

            $data[] = array(
                'kode_size'=>$kode_size,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek = Sizecontainer::where('kode_size', $data[$i]['kode_size'])->first();

            if($cek != null){
                $id = $cek->id;

                $tabel_baru = [
                    'kode_size'=>$id,
                ];
                $update_ship = JobrequestDetail::where('kode_size', $data[$i]['kode_size'])->update($tabel_baru);
            }
        }



        $get = TruckingDetail::get();
        $leng = count($get);

        $data = array();

        foreach ($get as $rowdata){
            $kode_size = $rowdata->kode_size;

            $data[] = array(
                'kode_size'=>$kode_size,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek = Sizecontainer::where('kode_size', $data[$i]['kode_size'])->first();

            if($cek != null){
                $id = $cek->id;

                $tabel_baru = [
                    'kode_size'=>$id,
                ];
                $update_ship = TruckingDetail::where('kode_size', $data[$i]['kode_size'])->update($tabel_baru);
            }
        }

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Kode Berhasil di Update.'
        ];
        
        return response()->json($message);
    }

    public function anyData()
    {
        return Datatables::of(Sizecontainer::query())->make(true);
    }
    
    public function store(Request $request)
    {
        $size = Sizecontainer::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_sizecontainer()
    {
        $kode_size = request()->id;
        $data = Sizecontainer::find($kode_size);
        $output = array(
            'kode_size'=> $data->id,
            'nama_size'=> $data->nama_size,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        Sizecontainer::find($request->kode_size)->update($request->all());
           
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_sizecontainer()
    {   
        $size = Sizecontainer::find(request()->id);

        $size->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$size->nama_sizecontainer.'] telah dihapus.'
        ];
        return response()->json($message);
    }
}
