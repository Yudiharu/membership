<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\JobrequestDetail;
use App\Models\JoborderDetail;
use App\Models\Joborder;
use App\Models\tb_akhir_bulan;
use App\Models\tb_item_bulanan;
use App\Models\Sizecontainer;
use App\Models\Trucking;
use App\Models\TruckingDetail;
use DB;
use Carbon;

class JoborderDetailController extends Controller
{
    public function index()
    {
        $create_url = route('joborderdetail.create');

        return view('admin.joborderdetail.index',compact('create_url'));
    }

    public function getDatabyID(){
        return Datatables::of(JoborderDetail::where('no_joborder',request()->id)->orderBy('created_at','desc'))
           ->addColumn('action', function ($query){
                return '<a href="javascript:;" data-toggle="tooltip" title="Edit" onclick="edit(\''.$query->id.'\',\''.$query->edit_url.'\')" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>'.'&nbsp'.
                    '<a href="javascript:;" data-toggle="tooltip" title="Hapus" onclick="del(\''.$query->id.'\',\''.$query->destroy_url.'\')" id="hapus" class="btn btn-danger btn-xs"> <i class="fa fa-times-circle"></i></a>'.'&nbsp';
           })->make(true);
    }

    public function getDatajor(){
        $data = JobrequestDetail::with('sizecontainer')->where('no_req_jo',request()->id)->orderBy('created_at','desc')->get();
        return response()->json($data);
    }

    public function Showdetailjobreq()
    {
        $jobrequestdetail = JobrequestDetail::where('no_req_jo',request()->id)->orderBy('created_at', 'desc')->get();

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

    public function numberFormatPrecision($number, $precision = 2, $separator = '.')
    {
        $numberParts = explode($separator, $number);
        $response = $numberParts[0];
        if (count($numberParts)>1 && $precision > 0) {
            $response .= $separator;
            $response .= substr($numberParts[1], 0, $precision);
        }
        return $response;
    }

    public function store(Request $request)
    {
        
        $simpan = [
            'no_joborder'=>$request->no_joborder,
            'deskripsi'=>$request->deskripsi,
            'qty'=>$request->qty,
            'satuan'=>$request->satuan,
            'harga'=>$request->harga,
            'mob_demob'=>$request->mob_demob,
            'total_harga'=>($request->qty*$request->harga)+$request->mob_demob,
        ];
        
        JoborderDetail::create($simpan);

        $joborderdetail = JoborderDetail::where('no_joborder', $request->no_joborder)->get();
        $leng = count($joborderdetail);
        $mob = 0;
        $grand = 0;
        foreach ($joborderdetail as $row) {
            $mob += $row->mob_demob;
            $grand += ($row->qty*$row->harga) + $row->mob_demob;
        }

        $jobor = Joborder::find($request->no_joborder);
        $jobor->total_item = $leng;
        $jobor->mob_demob = $mob;
        $jobor->grand_total = $grand;
        $jobor->save();
                    
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function edit_joborderdetail()
    {
        $id = request()->id;

        $data = JoborderDetail::find($id);
        $output = array(
            'no_joborder'=>$data->no_joborder,
            'deskripsi'=>$data->deskripsi,
            'qty'=>$data->qty,
            'harga'=>$data->harga,
            'satuan'=>$data->satuan,
            'mob_demob'=>$data->mob_demob,
            'id'=>$data->id,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $update_table = [
            'no_joborder'=>$request->no_joborder,
            'deskripsi'=>$request->deskripsi,
            'qty'=>$request->qty,
            'satuan'=>$request->satuan,
            'harga'=>$request->harga,
            'mob_demob'=>$request->mob_demob,
            'total_harga'=>($request->qty*$request->harga)+$request->mob_demob,
        ];

        $save = JoborderDetail::find($request->id)->update($update_table);

        $joborderdetail = JoborderDetail::where('no_joborder', $request->no_joborder)->get();
        $mob = 0;
        $grand = 0;
        foreach ($joborderdetail as $row) {
            $mob += $row->mob_demob;
            $grand += ($row->qty*$row->harga) + $row->mob_demob;
        }

        $jobor = Joborder::find($request->no_joborder);
        $jobor->mob_demob = $mob;
        $jobor->grand_total = $grand;
        $jobor->save();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah disimpan'
        ];
        return response()->json($message);
    }

    public function hapus_joborderdetail()
    {   
        $id = request()->id;
        $data = JoborderDetail::find($id);
        $data->delete();

        $joborderdetail = JoborderDetail::where('no_joborder', $data->no_joborder)->get();
        $leng = count($joborderdetail);
        $mob = 0;
        $grand = 0;
        foreach ($joborderdetail as $row) {
            $mob += $row->mob_demob;
            $grand += ($row->qty*$row->harga) + $row->mob_demob;
        }

        $jobor = Joborder::find($data->no_joborder);
        $jobor->total_item = $leng;
        $jobor->mob_demob = $mob;
        $jobor->grand_total = $grand;
        $jobor->save();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah dihapus.'
        ];
        return response()->json($message);
    }

    public function hapus_noreqjo()
    {   
        $no_joborder = request()->no_joborder;
        $no_req_jo = request()->no_req_jo;
        $kode_container = request()->kode_container;
        $cektruk = Trucking::where('no_joborder',$no_joborder)->first();

        $cek_container = TruckingDetail::where('no_joborder',$no_joborder)->where('kode_container',$kode_container)->first();
        if($cek_container != null){
            $message = [
                'success' => false,
                'title' => 'Gagal',
                'message' => 'Container ['.$kode_container.'] digunakan di No. Trucking : ['.$cektruk->no_trucking.']'
            ];
            return response()->json($message);
        }
        
        $data = JobrequestDetail::where('no_req_jo',$no_req_jo)->where('kode_container',$kode_container)->first();

        $data->delete();

        $hitung = JobrequestDetail::where('no_req_jo', $no_req_jo)->get();

        $total_item = count($hitung);

        $update_item = [
            'total_item'=>$total_item,
        ];

        $item_update = JoborderDetail::where('no_req_jo', $no_req_jo)->update($update_item);
        $item_update2 = Joborder::where('no_joborder', $no_joborder)->update($update_item);

        $cekjob = JoborderDetail::where('no_joborder', $no_joborder)->first();
        if ($cektruk != null) {
            if ($cektruk->total_item == $cekjob->total_item) {
                $cektruk->status_kembali = "TRUE";
                $cektruk->save();
            }
        }

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$data->kode_container.'] telah dihapus.'
        ];
        return response()->json($message);
    }
}
