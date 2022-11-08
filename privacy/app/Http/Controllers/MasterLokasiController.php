<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\MasterLokasi;
use App\Models\Mobil;
use App\Models\Alat;
use App\Models\tb_akhir_bulan;
use App\Models\Company;
use App\User;
use Carbon;

class MasterLokasiController extends Controller
{
    public function index()
    {
        
        $create_url = route('masterlokasi.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.masterlokasi.index',compact('create_url','period', 'nama_lokasi','nama_company'));
        
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(MasterLokasi::query())
            ->editColumn('alamat', function ($query)
            {
                return str_limit($query->alamat,20,'...');
            })
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Hapus"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
                           })
            ->make(true);
        
    }

    public function store(Request $request)
    {
        $nama_lokasi = $request->nama_lokasi;
        $ceklokasi = MasterLokasi::where('nama_lokasi',$nama_lokasi)->first();
        if ($ceklokasi == null) {
            $validator = $request->validate([
                'nama_lokasi'=> 'required',
                'alamat'=> 'required',
                'status'=> 'required',
            ]);

            try {
                MasterLokasi::create($request->all());
                $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah di Disimpan.'
                ];
                return response()->json($message);
            }catch (\Exception $exception){

                return response()->json(['errors' => $validator->errors()]);
            }
        }
        else{
            $message = [
                    'success' => false,
                    'title' => 'Simpan',
                    'message' => 'Lokasi sudah ada.',
                    ];
            return response()->json($message);
        }
    }

    public function edit_lokasi()
    {
        $kode_lokasi = request()->id;
        $data = MasterLokasi::find($kode_lokasi);
        $output = array(
            'id'=>$data->kode_lokasi,
            'nama_lokasi'=>$data->nama_lokasi,
            'alamat'=>$data->alamat,
            'status'=>$data->status,
        );
        return response()->json($output);
    }


    public function updateAjax(Request $request)
    {
        $kode_lokasi = $request->kode_lokasi;
        $cek_lokasi = Mobil::where('kode_lokasi',$kode_lokasi)->first();
        $cek_lokasi2 = Alat::where('kode_lokasi',$kode_lokasi)->first();

        if($cek_lokasi == null && $cek_lokasi2 == null){
            $request->validate([
            'kode_lokasi'=>'required',
            'nama_lokasi'=> 'required',
            'alamat'=> 'required',
            'status'=> 'required',
          ]);

          MasterLokasi::find($request->kode_lokasi)->update($request->all());
       
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
            'message' => '['.$request->kode_lokasi.'] telah terikat dengan mobil / alat.'
            ];
            return response()->json($message);
        }
      
    }

    public function hapus_lokasi()
    {   
        $kode_lokasi = request()->id;
        $masterlokasi = MasterLokasi::find(request()->id);
        $cek_user = User::where('kode_lokasi',$kode_lokasi)->first();

        if ($cek_user == null){
            $masterlokasi->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$masterlokasi->nama_lokasi.'] telah dihapus.'
            ];
            return response()->json($message);
        }else {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$masterlokasi->nama_lokasi.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
        
    }
}
