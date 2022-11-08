<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\TypeCargo;
use App\Models\MasterLokasi;
use App\Models\tb_akhir_bulan;
use App\Models\Company;
use App\Models\Joborder;
use Carbon;

class TypeCargoController extends Controller
{
    public function index()
    {
        $create_url = route('typecargo.create');
        $lokasi= MasterLokasi::pluck('nama_lokasi','kode_lokasi');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        
        return view('admin.typecargo.index',compact('create_url','period','lokasi', 'nama_lokasi','nama_company'));
    }

    public function anyData()
    {
        return Datatables::of(TypeCargo::all())->make(true);
    }

    public function store(Request $request)
    {   
        $nama_kapal = $request->type_cargo;
        $cek_nama = TypeCargo::where('type_cargo',$nama_kapal)->first();       
        if ($cek_nama==null){
            $kapal = TypeCargo::create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah di Disimpan.',
            ];
            return response()->json($message);
        }
        else{
            $message = [
                    'success' => false,
                    'title' => 'Simpan',
                    'message' => 'Nama Cargo Sudah Ada',
                    ];
            return response()->json($message);
        }
    }

    public function edit_kapal()
    {
        $kode_kapal = request()->id;
        $data = TypeCargo::find($kode_kapal);
        $output = array(
            'id'=>$data->id,
            'type_cargo'=>$data->type_cargo,
            'kode_inv'=>$data->kode_inv,
            'kode_inv_um'=>$data->kode_inv_um,
        );
        return response()->json($output);
    }


    public function updateAjax(Request $request)
    {
        $id = $request->id;
        $tipe = Joborder::where('type_cargo',$id)->first();
        if ($tipe == null){
            TypeCargo::find($id)->update($request->all());
           
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
                'message' => 'Data dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }

    public function hapus_kapal()
    {   
        $kode_kapal = request()->id;
        $kapal = TypeCargo::find(request()->id);
        $kode_kapal = Joborder::where('type_cargo',$kode_kapal)->first();

        if ($kode_kapal == null){
            $kapal->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$kapal->type_cargo.'] telah dihapus.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
        
    }
}
