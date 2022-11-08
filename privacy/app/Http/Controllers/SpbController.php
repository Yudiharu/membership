<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Pemilik;
use App\Models\Signature;
use App\Models\Spb;
use App\Models\Trucking;
use App\Models\TruckingDetail;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use Carbon;
use DB;

class SpbController extends Controller
{
    public function index()
    {
        $create_url = route('spb.create');
        $Company = Company::pluck('nama_company','kode_company');
        $Mobil = Mobil::pluck('nopol','kode_mobil');
        $Sopir = Sopir::pluck('nama_sopir','kode_sopir');
        $Pemilik = Pemilik::pluck('nama_pemilik','kode_pemilik');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.spb.index',compact('create_url','period', 'nama_lokasi','nama_company','Mobil','Sopir','Company','Pemilik'));
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Spb::with('mobil','sopir','pemilik')->orderBy('created_at','desc'))->make(true);
    }

    public function Showdetail()
    {
        $spb= Spb::with('mobil','sopir','pemilik')->where('no_spb',request()->id)->first();
    
        $output = array();
        
            $kode_kapal = $spb->kapal->nama_kapal;
            $voyage = $spb->voyage;
            $port_loading = $spb->port->nama_port;
            $etd = $spb->etd;
            $port_transite = $spb->port2->nama_port;
            $port_destination = $spb->port3->nama_port;
            $eta = $spb->eta;
            $no_do = $spb->no_do;
            
            $output[] = array(
                'kode_kapal'=>$kode_kapal,
                'voyage'=>$voyage,
                'port_loading'=>$port_loading,
                'etd'=>$etd,
                'port_transite'=>$port_transite,
                'port_destination'=>$port_destination,
                'eta'=>$eta,
                'no_do'=>$no_do,
            );

        return response()->json($output);
    }

    public function Post()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

        if($level == 'superadministrator' && $cek_bulan == null){
            $permintaan = Spb::find(request()->id);

            $tgl = $permintaan->tgl_spb;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

                $permintaan->status = "POSTED";
                $permintaan->save();

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Post SPB: '.$permintaan->no_spb.'.','created_by'=>$nama,'updated_by'=>$nama];
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
            $permintaan = Spb::find(request()->id);
            
            $tgl = $permintaan->tgl_spb;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

                $permintaan->status = "OPEN";
                $permintaan->save();    

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Unpost No. SPB: '.$permintaan->no_spb.'.','created_by'=>$nama,'updated_by'=>$nama];

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
        $cek_spb = Spb::find($request->no_spb);
        if($cek_spb == null){
            Spb::create($request->all());

            $update_tarif = [
                'tarif_trucking'=>$request->trucking,
                'uang_jalan'=>$request->uang_jalan,
            ];

            $tarif_update = TruckingDetail::where('no_spb', $request->no_spb)->update($update_tarif);

            $cek_truckingdetail = TruckingDetail::where('no_trucking', $request->no_trucking)->get();

            $total_uang_jalan = 0;
            foreach ($cek_truckingdetail as $row){
                $total_uang_jalan += $row->uang_jalan;
            }

            $update_trucking = Trucking::find($request->no_trucking);
            $update_trucking->gt_uang_jalan = $total_uang_jalan;
            $update_trucking->save();

            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.'
            ];
            return response()->json($message);
        }
        else{
            Spb::find($request->no_spb)->update($request->all());

            $update_tarif = [
                'tarif_trucking'=>$request->trucking,
                'uang_jalan'=>$request->uang_jalan,
            ];

            $tarif_update = TruckingDetail::where('no_spb', $request->no_spb)->update($update_tarif);

            $cek_truckingdetail = TruckingDetail::where('no_trucking', $request->no_trucking)->get();

            $total_uang_jalan = 0;
            foreach ($cek_truckingdetail as $row){
                $total_uang_jalan += $row->uang_jalan;
            }

            $update_trucking = Trucking::find($request->no_trucking);
            $update_trucking->gt_uang_jalan = $total_uang_jalan;
            $update_trucking->save();

            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.'
            ];
            return response()->json($message);
        }
    }

    public function edit_spb()
    {
        $no_spb = request()->id;
        $data = Spb::find($no_spb);
        $output = array(
            'no_spb'=>$data->no_spb,
            'tgl_spb'=>$data->tgl_spb,
            'tgl_kembali'=>$data->tgl_kembali,
            'kode_mobil'=>$data->kode_mobil,
            'kode_sopir'=>$data->kode_sopir,
            'kode_pemilik'=>$data->kode_pemilik,
            'uang_jalan'=>$data->uang_jalan,
            'bbm'=>$data->bbm,
            'bpa'=>$data->bpa,
            'honor'=>$data->honor,
            'biaya_lain'=>$data->biaya_lain,
            'trucking'=>$data->trucking,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_spb = $request->no_spb;
        $request->validate([
            'no_spb'=>'required',
            'tgl_spb'=> 'required',
            'tgl_kembali'=> 'required',
            'kode_mobil'=> 'required',
            'kode_sopir'=> 'required',
            'kode_pemilik'=> 'required',
            'uang_jalan'=> 'required',
            'bbm'=> 'required',
        ]);

          Spb::find($request->no_spb)->update($request->all());
       
          $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
    }

    public function hapus_spb()
    {   
        $no_spb = request()->id;
        $spb = Spb::find(request()->id);

            $spb->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$spb->no_spb.'] telah dihapus.'
            ];
            return response()->json($message);
    }

}
