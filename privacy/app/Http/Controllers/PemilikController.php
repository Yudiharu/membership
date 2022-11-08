<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Pemilik;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\PemilikDetail;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Vendor;
use Carbon;
use DB;

class PemilikController extends Controller
{
    public function index()
    {
        $create_url = route('pemilik.create');
        $Vendor = Vendor::pluck('nama_vendor','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.pemilik.index',compact('create_url','period', 'nama_lokasi','nama_company','Vendor'));
        
    }

    public function getkode(){
        $get_inv = Pemilik::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $id = $rowdata->id;

            $data[] = array(
                'id'=>$id,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_ship = Vendor::where('kode_vendor', $data[$i]['id'])->first();

            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kode_pemilik'=>$id_ship,
                ];
                $update_ship = Pemilik::where('id', $data[$i]['id'])->update($tabel_baru_ship);
            }
        }


        $get_inv = PemilikDetail::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kode_pemilik = $rowdata->kode_pemilik;

            $data[] = array(
                'kode_pemilik'=>$kode_pemilik,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_ship = Vendor::where('kode_vendor', $data[$i]['kode_pemilik'])->first();

            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kode_pemilik'=>$id_ship,
                ];
                $update_ship = PemilikDetail::where('kode_pemilik', $data[$i]['kode_pemilik'])->update($tabel_baru_ship);
            }
        }


        $get_inv = Mobil::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kepemilikan = $rowdata->kepemilikan;

            $data[] = array(
                'kepemilikan'=>$kepemilikan,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_ship = Vendor::where('kode_vendor', $data[$i]['kepemilikan'])->first();

            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kepemilikan'=>$id_ship,
                ];
                $update_ship = Mobil::where('kepemilikan', $data[$i]['kepemilikan'])->update($tabel_baru_ship);
            }
        }

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Kode Customer Berhasil di Update.'
        ];
        
        return response()->json($message);
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Pemilik::select('pemilik.*','u5611458_db_pusat.vendor.nama_vendor')->join('u5611458_db_pusat.vendor','pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->orderBy('created_at','desc'))->make(true);
    }

    public function getcom()
    {   
        $company = Company::where('kode_company', request()->kode_vendor)->first();
        $output = array(
            'nama_company'=>$company->nama_company,
            'alamat'=>$company->alamat,
            'telp'=>$company->telp,
        );
        return response()->json($output);
    }

    public function detail($pemilik)
    {
        $vendor = Vendor::find($pemilik);
        $pemilik = Pemilik::find($pemilik);

        $kode_pemilik = $pemilik->kode_pemilik;

        $data = Pemilik::find($kode_pemilik);
                    
        $pemilikdetail = PemilikDetail::with('mobil','sopir','jenismobil')->where('kode_pemilik', $pemilik->kode_pemilik)->orderBy('created_at','desc')->get();

        $list_url= route('pemilik.index');
                    
        $Mobil = Mobil::where('kepemilikan',$data->kode_pemilik)->pluck('nopol','kode_mobil');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.pemilikdetail.index', compact('pemilik','pemilikdetail','list_url','Mobil','period','nama_lokasi','nama_company','vendor'));
    }

    public function Showdetail()
    {
        $total_qty = 0;
        $total_qty_received = 0;
        $total_harga = 0;
        $grand_total = 0;
        $pemilikdetail= PemilikDetail::with('mobil','sopir','jenismobil')->where('kode_pemilik',request()->kode_pemilik)
        ->orderBy('created_at', 'desc')->get();

        $pemilik= Pemilik::where('kode_pemilik',request()->kode_pemilik)->first();

        $output = array();

        foreach($pemilikdetail as $row)
        {
            $kode_pemilik = $row->kode_pemilik;
            $kode_mobil = $row->mobil->nopol;
            $kode_jenis_mobil = $row->kode_jenis_mobil;
            if($kode_jenis_mobil != null){
                $kode_jenis_mobil = $row->jenismobil->nama_jenis_mobil;
            }else{
                $kode_jenis_mobil = '-';
            }
            $kir = $row->mobil->kir;
            if($kir == null){
                $kir = '-';
            }
            $masa_stnk = $row->mobil->masa_stnk;
            if($masa_stnk == null){
                $masa_stnk = '-';
            }
            
            $output[] = array(
                'kode_pemilik'=>$kode_pemilik,
                'kode_mobil'=>$kode_mobil,
                'jenis_mobil'=>$kode_jenis_mobil,
                'kir'=>$kir,
                'masa_stnk'=>$masa_stnk,
            );
        }

        return response()->json($output);
    }

    public function store(Request $request)
    {
        $cek_pemilik = Pemilik::find($request->kode_pemilik);
        if($cek_pemilik == null){
            Pemilik::create($request->all());

            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.'
            ];
            return response()->json($message);
        }
        else{
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Pemilik Sudah Ada',
            ];
            return response()->json($message);
        }  
    }

    public function edit_pemilik()
    {
        $kode_pemilik = request()->kode_pemilik;
        $data = Pemilik::find($kode_pemilik);
        $output = array(
            'kode_pemilik'=>$data->kode_pemilik,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_pemilik = $request->kode_pemilik;

        Pemilik::find($request->kode_pemilik)->update($request->all());
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_pemilik()
    {   
        $kode_pemilik = request()->kode_pemilik;
        $pemilik = Pemilik::find(request()->kode_pemilik);

        $pemilik->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$pemilik->nama_pemilik.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
