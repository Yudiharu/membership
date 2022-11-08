<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\JobrequestDetail;
use App\Models\Jobrequest;
use App\Models\Joborder;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use App\Models\Sizecontainer;
use App\Models\Trucking;
use App\Models\TruckingDetail;
use App\Models\TarifAlat;
use DB;
use Carbon;

class JobrequestdetailController extends Controller
{
    public function index()
    {
        $create_url = route('jobrequestdetail.create');

        return view('admin.jobrequestdetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(Jobrequest::where('no_joborder',request()->id)->orderBy('created_at','desc'))
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" data-toggle="tooltip" title="Edit" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" data-toggle="tooltip" title="Hapus" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
           })->make(true);
    }

    public function getDatajor(){
        $data = JobrequestDetail::with('alat')->where('no_jobrequest',request()->id)->orderBy('created_at','desc')->get();
        return response()->json($data);
    }

    public function Showdetailjobreq()
    {
        $jobrequestdetail = JobrequestDetail::with('sizecontainer')->where('no_jobrequest',request()->id)->orderBy('created_at', 'desc')->get();

        $output = array();
        
            foreach($jobrequestdetail as $row)
            {

                $kode_container = $row->kode_container;
                $kode_size = $row->sizecontainer->nama_size;
                $status_muatan = $row->status_muatan;
                $dari = $row->dari;
                $tujuan = $row->tujuan;

                $output[] = array(
                    'kode_container'=>$kode_container,
                    'kode_size'=>$kode_size,
                    'status_muatan'=>$status_muatan,
                    'dari'=>$dari,
                    'tujuan'=>$tujuan,
                );
            }

        return response()->json($output);
    }

    public function tarif()
    {
        $kode_alat = request()->kode;
        $jr = Jobrequest::where('no_joborder', request()->no_joborder)->first();
        $tarif = TarifAlat::where('kode_alat', $kode_alat)->where('tgl_berlaku', '<=', $jr->tanggal_req)->first();
        if ($tarif != null) {
            $output = array(
                'harga'=>$tarif->tarif,
            );
            return response()->json($output);
        }else {
            $output = array(
                'harga'=>0,
            );
            return response()->json($output);
        }
        
    }

    public function Post()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

        if($level == 'superadministrator' && $cek_bulan == null){
            $permintaan = Jobrequest::find(request()->id);

            $tgl = $permintaan->tanggal_req;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

                $permintaan->status = "POSTED";
                $permintaan->save();

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Post Job Order: '.$permintaan->no_jobrequest.'.','created_by'=>$nama,'updated_by'=>$nama];
                user_history::create($tmp);

                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data berhasil di POST.'
                    ];
                return response()->json($message);
        }else{
            $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'Anda tidak mempunyai akses posting data',
                        ];
            return response()->json($message);
        }
        
    }

    public function Unpost()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

        if($level == 'superadministrator' && $cek_bulan == null){
            $permintaan = Jobrequest::find(request()->id);
            
            $tgl = $permintaan->tanggal_req;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

                $permintaan->status = "OPEN";
                $permintaan->save();    

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Job Order: '.$permintaan->no_jobrequest.'.','created_by'=>$nama,'updated_by'=>$nama];

                user_history::create($tmp);

                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data berhasil di UNPOST.'
                    ];
                return response()->json($message);
        }else{
            $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'Anda tidak mempunyai akses unposting data',
                        ];
            return response()->json($message);
        }
        
    }

    public function store(Request $request)
    {
        $Jobrequest = Jobrequest::where('no_joborder', $request->no_joborder)->get();
        $leng = count($Jobrequest);

        if($leng > 0){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Job Request tidak boleh lebih dari satu'
            ];
            return response()->json($message);
        }
        else{
            $Jobrequest = Jobrequest::create($request->all());
                    
            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah disimpan'
            ];
            return response()->json($message);
        }
    }

    public function store2(Request $request)
    {
        $jobrequestdetail = JobrequestDetail::where('no_jobrequest', $request->no_jobrequest)->where('kode_alat', $request->kode_alat)->get();
        $leng = count($jobrequestdetail);

        if($leng > 0){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Alat Sudah Ada'
            ];
            return response()->json($message);
        }
        else{
            $jr = Jobrequest::where('no_joborder', $request->no_joborder)->first();
            $simpan = [
                'no_joborder'=>$request->no_joborder,
                'no_jobrequest'=>$request->no_jobrequest,
                'tgl_request'=>$jr->tanggal_req,
                'kode_alat'=>$request->kode_alat,
                'qty'=>$request->qty,
                'harga'=>$request->harga,
                'subtotal'=>$request->subtotal,
            ];
            $jobrequestdetail = JobrequestDetail::create($simpan);
                    
            $hitung = JobrequestDetail::where('no_jobrequest', $request->no_jobrequest)->get();

            $total_item = count($hitung);

            $update_item = [
                'total_item'=>$total_item,
            ];

            $item_update = Jobrequest::where('no_jobrequest', $request->no_jobrequest)->update($update_item);
            $job_update = Joborder::where('no_joborder', $request->no_joborder)->update($update_item);
            // $cektruk = Trucking::where('no_joborder',$request->no_joborder)->first();
            // $cekjob = Jobrequest::where('no_joborder', $request->no_joborder)->first();
            // if ($cektruk != null) {
            //     if ($cektruk->total_item != $cekjob->total_item) {
            //         $cektruk->status_kembali = "FALSE";
            //         $cektruk->save();
            //     }
            // }

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah disimpan'
            ];
            return response()->json($message);
        }
    }

    public function edit_jobrequest()
    {
        $no_jobrequest = request()->no_jobrequest;

        $data = Jobrequest::where('no_jobrequest',$no_jobrequest)->first();
        $output = array(
            'no_joborder'=>$data->no_joborder,
            'no_jobrequest'=>$data->no_jobrequest,
            'tanggal_req'=>$data->tanggal_req,
            'id'=>$data->id,
        );
        return response()->json($output);
    }

    public function edit_noreqjo()
    {
        $no_joborder = request()->no_jo;
        $no_jobrequest = request()->no_jobrequest;
        $kode_lama = request()->kode_lama;
        $qty = request()->qty;
        $harga = request()->harga;
        $subtotal = request()->subtotal;

        $jor = JobrequestDetail::where('no_jobrequest',$no_jobrequest)->where('no_joborder',$no_joborder)->where('kode_alat',$kode_lama)->first();

        $jor->kode_alat = request()->kode_alat;
        $jor->qty = $qty;
        $jor->harga = $harga;
        $jor->subtotal = $subtotal;
        $jor->save();
        
        $hitung = JobrequestDetail::where('no_jobrequest',$no_jobrequest)->get();

        $total_item = count($hitung);

        $update_total = [
            'total_item'=>$total_item,
        ];

        $total_update = Jobrequest::where('no_joborder',$no_joborder)->update($update_total);
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    // public function edit_noreqjo()
    // {
    //     $no_jobrequest = request()->no_jobrequest;
    //     $kode_container = request()->kode_container;
    //     $data = JobrequestDetail::where('no_jobrequest',$no_jobrequest)->where('kode_container',$kode_container)->first();
    //     $output = array(
    //         'id'=>$data->id,
    //         'no_joborder'=>$data->no_joborder,
    //         'no_jobrequest'=>$data->no_jobrequest,
    //         'kode_container'=>$data->kode_container,
    //         'kode_size'=>$data->kode_size,
    //         'status_muatan'=>$data->status_muatan,
    //         'dari'=>$data->dari,
    //         'tujuan'=>$data->tujuan,
    //     );
    //     return response()->json($output);
    // }

    public function updateAjax(Request $request)
    {
        $save = Jobrequest::find($request->id)->update($request->all());

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function hapus_jobrequest()
    {   
        $no_jobrequest = request()->no_jobrequest;
        $data = Jobrequest::where('no_jobrequest',$no_jobrequest)->first();
        if ($data->total_item > 0) {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'No: ['.$no_jobrequest.'] total alat tidak kosong.'
            ];
            return response()->json($message);
        }

        $data->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$no_jobrequest.'] telah dihapus.'
        ];
        return response()->json($message);
    }

    public function hapus_noreqjo()
    {   
        $no_joborder = request()->no_joborder;
        $no_jobrequest = request()->no_jobrequest;
        $kode_container = request()->kode_alat;
        
        $data = JobrequestDetail::where('no_jobrequest',$no_jobrequest)->where('kode_alat',$kode_container)->first();

        $data->delete();

        $hitung = JobrequestDetail::where('no_jobrequest', $no_jobrequest)->get();

        $total_item = count($hitung);

        $update_item = [
            'total_item'=>$total_item,
        ];

        $item_update = Jobrequest::where('no_jobrequest', $no_jobrequest)->update($update_item);
        $job_update = Joborder::where('no_joborder', $no_joborder)->update($update_item);
        // $cekjob = Jobrequest::where('no_joborder', $no_joborder)->first();
        // if ($cektruk != null) {
        //     if ($cektruk->total_item == $cekjob->total_item) {
        //         $cektruk->status_kembali = "TRUE";
        //         $cektruk->save();
        //     }
        // }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$data->kode_alat.'] telah dihapus.'
        ];
        return response()->json($message);
    }
}
