<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\SpbnonDetail;
use App\Models\Spbnon;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use DB;
use Carbon;

class SpbnondetailController extends Controller
{
    public function index()
    {
        $create_url = route('spbnondetail.create');

        return view('admin.spbnondetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(SpbnonDetail::where('no_spbnon',request()->id)->orderBy('created_at','desc'))
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" data-toggle="tooltip" title="Edit" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" data-toggle="tooltip" title="Hapus" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
           })->make(true);
    }

    public function getjenis()
    {   
        $mobil = Mobil::find(request()->id);
        $jenis_mobil = $mobil->kode_jenis_mobil;

        $get_jenis = JenisMobil::find($jenis_mobil);
        $jenis = $get_jenis->nama_jenis_mobil;

        $output = array(
            'kode_jenis_mobil'=>$jenis,
        );

        return response()->json($output);
    }

    public function store(Request $request)
    {
        $spbnondetail = SpbnonDetail::where('no_spbnon', $request->no_spbnon)->where('kode_item', $request->kode_item)->get();

        $leng = count($spbnondetail);

                if($leng > 0){
                    $message = [
                        'success' => false,
                        'title' => 'Gagal',
                        'message' => 'Item Sudah Ada'
                    ];
                    return response()->json($message);
                }
                else{
                    $spbnondetail = SpbnonDetail::create($request->all());
                    $spbnon = Spbnon::find($request->no_spbnon);
                    
                    $hitung = SpbnonDetail::where('no_spbnon', $request->no_spbnon)->get();
                    $total_berat = 0;

                    foreach ($hitung as $row){
                        $total_berat += $row->total_berat;
                    }

                    $total_item = count($hitung);

                    $spbnon->total_item = $total_item;
                    $spbnon->total_berat = $total_berat;
                    $spbnon->save();

                    $message = [
                        'success' => true,
                        'title' => 'Update',
                        'message' => 'Data telah disimpan'
                    ];
                    return response()->json($message);
                }
    }

    public function edit($spbnondetail)
    {
        $id = $spbnondetail;
        $data = SpbnonDetail::find($id);
        $output = array(
            'no_spbnon'=>$data->no_spbnon,
            'kode_item'=>$data->kode_item,
            'qty'=>$data->qty,
            'berat_satuan'=>$data->berat_satuan,
            'total_berat'=>$data->total_berat,
            'keterangan'=>$data->keterangan,
            'id'=>$data->id,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $cek_pemilik = Spbnon::find($request->no_spbnon);

        $request->validate([
            'no_spbnon'=> 'required',
            'kode_item'=> 'required',
            'qty'=> 'required',
            'berat_satuan'=> 'required',
            'total_berat'=> 'required',
        ]);

        $satuan = SpbnonDetail::find($request->id)->update($request->all());
        
        $hitung = SpbnonDetail::where('no_spbnon', $request->no_spbnon)->get();
        $total_berat = 0;

        foreach ($hitung as $row){
            $total_berat += $row->total_berat;
        }

        $leng = count($hitung);

        $update_spbnon = Spbnon::where('no_spbnon', $request->no_spbnon)->first();
        $update_spbnon->total_item = $leng;
        $update_spbnon->total_berat = $total_berat;
        $update_spbnon->save();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function destroy($spbnondetail)
    {
        $spbnondetail = SpbnonDetail::find($spbnondetail);

        $no_spbnon = $spbnondetail->no_spbnon;
        $spbnondetail->delete();

        $hitung = SpbnonDetail::where('no_spbnon', $spbnondetail->no_spbnon)->get();
        $total_berat = 0;

        foreach ($hitung as $row){
            $total_berat += $row->total_berat;
        }

        $leng = count($hitung);

        $update_spbnon = Spbnon::where('no_spbnon', $spbnondetail->no_spbnon)->first();
        $update_spbnon->total_item = $leng;
        $update_spbnon->total_berat = $total_berat;
        $update_spbnon->save();

        $message = [
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Data telah dihapus.'
        ];
        return response()->json($message);
    }

}
