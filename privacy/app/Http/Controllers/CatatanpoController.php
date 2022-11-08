<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Catatanpo;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use Carbon;
use DB;

class CatatanpoController extends Controller
{
    public function index()
    {;
        $create_url = route('catatanpo.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;
        
        return view('admin.catatanpo.index',compact('create_url','period', 'nama_lokasi','nama_company'));

    }

    public function anyData()
    {
        return Datatables::of(Catatanpo::query())
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Hapus"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
                           })
            ->make(true);

    }

    public function store(Request $request)
    {
        $level = auth()->user()->level;
        if($level == 'superadministrator'){
                $validator = $request->validate([
                'catatan'=>'required',
              ]);

           
                Catatanpo::create($request->all());
                $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.',
                ];
                return response()->json($message);
            
        }else{
            $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'Anda tidak mempunyai akses tambah data',
                        ];
                return response()->json($message);
        }
        
    }

    public function edit_catatanpo()
    {
        $nomor = request()->id;
        $data = Catatanpo::find($nomor);
        $output = array(
            'nomor'=>$data->nomor,
            'catatan'=>$data->catatan,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $level = auth()->user()->level;
        if($level == 'superadministrator'){
            $request->validate([
            'nomor'=> 'required',
            'catatan'=>'required',
          ]);

          Catatanpo::find($request->nomor)->update($request->all());
       
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
            'message' => 'Anda tidak mempunyai akses edit data'
            ];
            return response()->json($message);
        }
      
    }

    public function hapus_catatanpo()
    {   
        $catatanpo = Catatanpo::find(request()->id);

        $catatanpo->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$catatanpo->catatan.'] telah dihapus.'
        ];
        return response()->json($message);
    }
}
