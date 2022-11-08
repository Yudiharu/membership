<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\TarifTrucking;
use App\Models\GudangDetail;
use App\Models\Gudang;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use DB;
use Carbon;

class GudangdetailController extends Controller
{
    public function index()
    {
        $create_url = route('gudangdetail.create');

        return view('admin.gudangdetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(GudangDetail::where('kode_shipper',request()->id)->orderBy('created_at','desc'))
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" data-toggle="tooltip" title="Edit" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" data-toggle="tooltip" title="Hapus" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs"> <i class="fa fa-times-circle"></i></a>'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.'&nbsp'.
                    '<a href="javascript:;" data-toggle="tooltip" title="Add Tarif" onclick="tarif(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-info btn-xs"><i class="fa fa-plus"> Tarif</i></a>'.'&nbsp';
           })->make(true);
    }

    public function getDatatarif(){
        $data = TarifTrucking::where('kode_gudang',request()->id)->orderBy('created_at','desc')->get();
        return response()->json($data);
    }

    public function Showdetailtarif()
    {
        $tariftrucking = TarifTrucking::where('kode_gudang',request()->id)->orderBy('created_at', 'desc')->get();

        $output = array();
        
            foreach($tariftrucking as $row)
            {
                $tarif_trucking = $row->tarif_trucking;
                $tanggal_berlaku = $row->tanggal_berlaku;

                $output[] = array(
                    'tarif_trucking'=>$tarif_trucking,
                    'tanggal_berlaku'=>$tanggal_berlaku,
                );
            }
        return response()->json($output);
    }

    public function store(Request $request)
    {
        $gudangdetail = GudangDetail::where('id', $request->kode_shipper)->where('nama_gudang', $request->nama_gudang)->get();

        $leng = count($gudangdetail);

                if($leng > 0){
                    $message = [
                        'success' => false,
                        'title' => 'Gagal',
                        'message' => 'Gudang Sudah Ada'
                    ];
                    return response()->json($message);
                }
                else{
                    $gudangdetail = GudangDetail::create($request->all());
                    $gudang = Gudang::find($request->kode_shipper);
                    
                    $hitung = GudangDetail::where('kode_shipper', $request->kode_shipper)->get();
                    $total_gudang = count($hitung);

                    $gudang->total_gudang = $total_gudang;
                    $gudang->save();

                    $message = [
                        'success' => true,
                        'title' => 'Update',
                        'message' => 'Data telah disimpan'
                    ];
                    return response()->json($message);
                }
    }

    public function edit_gudangdetail()
    {
        $kode_gudang = request()->kode_gudang;
        $kode_shipper = request()->kode_shipper;
        $data = GudangDetail::where('id',$kode_gudang)->where('kode_shipper',$kode_shipper)->first();

        $output = array(
            'kode_shipper'=>$data->kode_shipper,
            'nama_gudang'=>$data->nama_gudang,
            'tarif_trucking'=>0,
            'tanggal_berlaku'=>0,
            'kode_gudang'=>$data->kode_gudang,
            'id'=>$data->id,
        );
        // dd($output);
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $request->validate([
            'kode_shipper'=> 'required',
            'nama_gudang'=> 'required',
        ]);

        $update = GudangDetail::find($request->id)->update($request->all());
        
        $hitung = GudangDetail::where('kode_shipper', $request->kode_shipper)->get();
        $leng = count($hitung);

        $update_gudang = Gudang::where('kode_shipper', $request->kode_shipper)->first();
        $update_gudang->total_gudang = $leng;
        $update_gudang->save();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function store2(Request $request)
    {
        $cek_tgl = TarifTrucking::where('kode_gudang', $request->kode_gudang)->where('tanggal_berlaku', $request->tanggal_berlaku)->first();
        if($cek_tgl != null){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Sudah ada tarif yang berlaku di tanggal ['.$request->tanggal_berlaku.'].'
            ];
            return response()->json($message);
        }
        if($request->tarif_trucking == 0){
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Tarif tidak boleh 0'
            ];
            return response()->json($message);
        }
        
        $tarif = TarifTrucking::create($request->all());

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function hapus_gudangdetail()
    {
        $kode_shipper = request()->kode_shipper;
        $kode_gudang = request()->kode_gudang;

        $gudangdetail = GudangDetail::where('id',$kode_gudang)->first();
        $gudangdetail->delete();
        
        $hitung = GudangDetail::where('kode_shipper', $kode_shipper)->get();
        $leng = count($hitung);

        $update_gudang = Gudang::where('kode_shipper', $kode_shipper)->first();
        $update_gudang->total_gudang = $leng;
        $update_gudang->save();
    
        $message = [
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Data telah dihapus.'
        ];
        return response()->json($message);
    }

    public function hapus_tarifdetail()
    {
        $kode_gudang = request()->kode_gudang;
        $kode_shipper = request()->kode_shipper;
        $tanggal_berlaku = request()->tanggal_berlaku;

        if($kode_gudang != null){
            $data_detail = TarifTrucking::where('kode_gudang',$kode_gudang)->where('kode_shipper',$kode_shipper)->where('tanggal_berlaku',$tanggal_berlaku)->delete();
            // dd($tarif_trucking);

            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah dihapus.',
            ];

            return response()->json($message);
        }
        else{
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Pilih item terlebih dahulu.',
            ];
            return response()->json($message);
        }
    }
}
