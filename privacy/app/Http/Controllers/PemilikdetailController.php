<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\PemilikDetail;
use App\Models\Pemilik;
use App\Models\Mobil;
use App\Models\JenisMobil;
use App\Models\Sopir;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use DB;
use Carbon;

class PemilikdetailController extends Controller
{
    public function index()
    {
        $create_url = route('pemilikdetail.create');

        return view('admin.pemilikdetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(PemilikDetail::with('mobil','sopir','jenismobil')->where('kode_pemilik',request()->id)->orderBy('created_at','desc'))->make(true);
    }

    public function getjenis()
    {   
        $mobil = Mobil::find(request()->id);
        $jenis_mobil = $mobil->kode_jenis_mobil;
        $kir = $mobil->kir;
        $masa_stnk = $mobil->masa_stnk;

        $output = array(
            'kode_jenis_mobil'=>$jenis_mobil,
            'masa_stnk'=>$masa_stnk,
            'kir'=>$kir,
        );

        return response()->json($output);
    }

    public function getjenis2()
    {   
        $mobil = Mobil::find(request()->id);
        $jenis_mobil = $mobil->kode_jenis_mobil;
        $kir = $mobil->kir;
        $masa_stnk = $mobil->masa_stnk;

        $output = array(
            'kode_jenis_mobil'=>$jenis_mobil,
            'masa_stnk'=>$masa_stnk,
            'kir'=>$kir,
        );

        return response()->json($output);
    }

    public function store(Request $request)
    {
        $pemilikdetail = PemilikDetail::where('kode_pemilik', $request->kode_pemilik)->where('kode_mobil', $request->kode_mobil)->get();

        $leng = count($pemilikdetail);

                if($leng > 0){
                    $message = [
                        'success' => false,
                        'title' => 'Gagal',
                        'message' => 'Mobil Sudah Ada'
                    ];
                    return response()->json($message);
                }
                else{
                    $pemilikdetail = PemilikDetail::create($request->all());
                    $pemilik = Pemilik::find($request->kode_pemilik);
                    
                    $hitung = PemilikDetail::where('kode_pemilik', $request->kode_pemilik)->get();
                    $total_mobil = count($hitung);

                    $pemilik->total_mobil = $total_mobil;
                    $pemilik->save();

                    $message = [
                        'success' => true,
                        'title' => 'Update',
                        'message' => 'Data telah disimpan'
                    ];
                    return response()->json($message);
                }
    }

    public function edit($pemilikdetail)
    {
        $id = $pemilikdetail;
        $data = PemilikDetail::with('mobil')->find($id);
        $output = array(
            'kode_pemilik'=>$data->kode_pemilik,
            'kode_mobil'=>$data->kode_mobil,
            'kode_jenis_mobil'=>$data->kode_jenis_mobil,
            'kir'=>$data->mobil->kir,
            'masa_stnk'=>$data->mobil->masa_stnk,
            'id'=>$data->id,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $cek_pemilik = Pemilik::find($request->kode_pemilik);

        $request->validate([
            'kode_pemilik'=> 'required',
            'kode_mobil'=> 'required',
            'kir'=> 'required',
            'masa_stnk'=> 'required',
        ]);

        $cek_mobil = PemilikDetail::where('kode_pemilik',$request->kode_pemilik)->where('kode_mobil',$request->kode_mobil)->first();
        if($cek_mobil == null){
            $satuan = PemilikDetail::find($request->id)->update($request->all());
        
            $hitung = PemilikDetail::where('kode_pemilik', $request->kode_pemilik)->get();
            $leng = count($hitung);

            $update_pemilik = Pemilik::where('kode_pemilik', $request->kode_pemilik)->first();
            $update_pemilik->total_mobil = $leng;
            $update_pemilik->save();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah disimpan'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Mobil sudah ada'
            ];
            return response()->json($message);
        }
    }

    public function destroy($pemilikdetail)
    {
        $pemilikdetail = PemilikDetail::find($pemilikdetail);

        $kode_pemilik = $pemilikdetail->kode_pemilik;
        $pemilikdetail->delete();

        $hitung = PemilikDetail::where('kode_pemilik', $pemilikdetail->kode_pemilik)->get();
        $leng = count($hitung);

        $update_pemilik = Pemilik::where('kode_pemilik', $pemilikdetail->kode_pemilik)->first();
        $update_pemilik->total_mobil = $leng;
        $update_pemilik->save();

        $message = [
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Data telah dihapus.'
        ];
        return response()->json($message);
    }

}
