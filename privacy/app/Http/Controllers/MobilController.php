<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Mobil;
use App\Models\LokasiMobil;
use App\Models\Sopir;
use App\Models\MasterLokasi;
use App\Models\JenisMobil;
use App\Models\PemilikDetail;
use App\Models\tb_akhir_bulan;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Pemilik;
use App\Models\Spb;
use App\Models\TruckingnonDetail;
use Carbon;


class MobilController extends Controller
{
    public function konek()
    {
        $compa2 = auth()->user()->kode_company;
        $compa = substr($compa2,0,2);
        if ($compa == '01'){
            $koneksi = 'mysqlinvdepo';
        }else if ($compa == '02'){
            $koneksi = 'mysqlinvpbm';
        }else if ($compa == '03'){
            $koneksi = 'mysqlemkl';
        }else if ($compa == '22'){
            $koneksi = 'mysqlskt';
        }else if ($compa == '04'){
            $koneksi = 'mysqlgut';
        }else if ($compa == '05'){
            $koneksi = 'mysql';
        }
        return $koneksi;
    }
    
    public function index()
    {
        $create_url = route('mobil.create');
        $JenisMobil= JenisMobil::pluck('nama_jenis_mobil','id');
        $Vendor = Vendor::where('id', '56')->pluck('nama_vendor','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.mobil.index',compact('create_url','JenisMobil','period', 'nama_lokasi','nama_company','Vendor'));

    }

public function getDatabyID()
    {
        $konek = self::konek();
        return Datatables::of(LokasiMobil::on($konek)->where('kode_mobil',request()->kode_customer)->orderBy('created_at','desc'))->make(true);
    }


    public function getkode(){
        $get = Mobil::get();
        $leng = count($get);

        $data = array();

        foreach ($get as $rowdata){
            $kode_jenis_mobil = $rowdata->kode_jenis_mobil;

            $data[] = array(
                'kode_jenis_mobil'=>$kode_jenis_mobil,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek = JenisMobil::where('kode_jenis_mobil', $data[$i]['kode_jenis_mobil'])->first();

            if($cek != null){
                $id = $cek->id;

                $tabel_baru = [
                    'kode_jenis_mobil'=>$id,
                ];
                $update = Mobil::where('kode_jenis_mobil', $data[$i]['kode_jenis_mobil'])->update($tabel_baru);   
            }
        }


        $get = PemilikDetail::get();
        $leng = count($get);

        $data = array();

        foreach ($get as $rowdata){
            $kode_jenis_mobil = $rowdata->kode_jenis_mobil;

            $data[] = array(
                'kode_jenis_mobil'=>$kode_jenis_mobil,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek = JenisMobil::where('kode_jenis_mobil', $data[$i]['kode_jenis_mobil'])->first();

            if($cek != null){
                $id = $cek->id;

                $tabel_baru = [
                    'kode_jenis_mobil'=>$id,
                ];
                $update = PemilikDetail::where('kode_jenis_mobil', $data[$i]['kode_jenis_mobil'])->update($tabel_baru);   
            }
        }

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Kode Berhasil di Update.'
        ];
        
        return response()->json($message);
    }


    public function anyData()
    {
        $level = auth()->user()->level;
        return Datatables::of(Mobil::orderBy('created_at','desc'))->make(true);
    }

    public function detaillokasi($kode)
    {
        $konek = self::konek();
        $list_url= route('mobil.index');
        $cust = Mobil::on($konek)->find($kode);
        $Lokasi = MasterLokasi::pluck('nama_lokasi','kode_lokasi');

        $tgl_jalan = tb_akhir_bulan::on($konek)->where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.mobil.indexlokasi', compact('list_url','period','nama_lokasi','nama_company','cust','Lokasi'));
    }

    public function store_lokasi(Request $request)
    {
        $konek = self::konek();
        $kode_customer = $request->kode_mobil;

        $cek_cust = Mobil::on($konek)->find($kode_customer);
        
        if ($request->kode_lokasi != $cek_cust->kode_lokasi) {
            LokasiMobil::on($konek)->create($request->all());
            
            $ceknpwp = LokasiMobil::on($konek)->where('kode_mobil', $kode_customer)->orderBy('created_at','desc')->first();
            $update_info = [
                'kode_lokasi'=>$ceknpwp->kode_lokasi,
            ];
            $cek_cust->update($update_info);
        }

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function store(Request $request)
    {  
        $nopol = $request->nopol;
        $asset = $request->no_asset_mobil;
        $ceknopol = Mobil::where('nopol',$nopol)->first();
        
            $cek_tipe = $request->pilih;
            if($cek_tipe == 'Vendor'){
                if ($ceknopol == null){
                    Mobil::create($request->all());

                    // $get_mobil = Mobil::where('nopol', $nopol)->first();
                    // $get_mobil->kepemilikan = $request->kode_vendor;
                    // $get_mobil->save();

                    // $tabel_baru = [
                    //     'kode_pemilik'=>$get_mobil->kepemilikan,
                    //     'kode_mobil'=>$get_mobil->kode_mobil,
                    //     'kode_jenis_mobil'=>$get_mobil->kode_jenis_mobil,
                    //     'kir'=>$get_mobil->kir,
                    //     'masa_stnk'=>$get_mobil->masa_stnk,
                    // ];

                    // $createdetail = PemilikDetail::create($tabel_baru);

                    // $get_pemilik = Pemilik::find($get_mobil->kepemilikan);

                    // $get_pemilik->total_mobil = $get_pemilik->total_mobil + 1;
                    // $get_pemilik->save();
                   
                    $message = [
                        'success' => true,
                        'title' => 'Simpan',
                        'message' => 'Data telah di Disimpan.'
                    ];
                    return response()->json($message);
                }else{
                    $message = [
                            'success' => false,
                            'title' => 'Simpan',
                            'message' => 'Nopol sudah ada.',
                            ];
                    return response()->json($message);
                }
            }else{
                $cekasset = Mobil::where('no_asset_mobil',$asset)->where('status_mobil','Aktif')->first();
                if ($ceknopol == null && $cekasset == null) {
                    Mobil::create($request->all());

                    // $get_mobil = Mobil::where('nopol', $nopol)->first();
                    // $get_mobil->kepemilikan = '207';
                    // $get_mobil->save();

                    // $tabel_baru = [
                    //     'kode_pemilik'=>'207',
                    //     'kode_mobil'=>$get_mobil->kode_mobil,
                    //     'kode_jenis_mobil'=>$get_mobil->kode_jenis_mobil,
                    //     'kir'=>$get_mobil->kir,
                    //     'masa_stnk'=>$get_mobil->masa_stnk,
                    // ];

                    // $createdetail = PemilikDetail::create($tabel_baru);

                    // $get_pemilik = Pemilik::find('207');

                    // $get_pemilik->total_mobil = $get_pemilik->total_mobil + 1;
                    // $get_pemilik->save();
                   
                    $message = [
                        'success' => true,
                        'title' => 'Simpan',
                        'message' => 'Data telah di Disimpan.'
                    ];
                    return response()->json($message);
                }else{
                    $message = [
                            'success' => false,
                            'title' => 'Simpan',
                            'message' => 'Nopol / No Asset sudah ada.',
                            ];
                    return response()->json($message);
                }
            }
        
    }

    public function edit_mobil()
    {
        $kode_mobil = request()->id;
        $data = Mobil::find($kode_mobil);
        $output = array(
            'kode_mobil'=>$data->kode_mobil,
            'nopol'=>$data->nopol,
            'kode_jenis_mobil'=>$data->kode_jenis_mobil,
            'tahun'=>$data->tahun,
            'no_asset_mobil'=>$data->no_asset_mobil,
            'kir'=>$data->kir,
            'masa_stnk'=>$data->masa_stnk,
            'status_mobil'=>$data->status_mobil,
            'kepemilikan'=>$data->kepemilikan,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $cek_tipe = $request->tipe;
        if($cek_tipe == 'Vendor'){
            Mobil::find($request->kode_mobil)->update($request->all());
            $get_mobil = Mobil::find($request->kode_mobil);
            $get_mobil->kepemilikan = $request->kode_vendor;
            $get_mobil->save();
        }else{
            Mobil::find($request->kode_mobil)->update($request->all());
        }
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
            ];
        return response()->json($message);
    }

    public function hapus_mobil()
    {   
        $mobil = Mobil::find(request()->id);
        $kode_mobil = request()->id;
        // $cek_spb = Spb::where('kode_mobil',$kode_mobil)->first();
        $cek_spbnc = TruckingnonDetail::where('kode_mobil',$kode_mobil)->first();

        if ($cek_spbnc == null){
            // $cek_pemilik = PemilikDetail::where('kode_pemilik',$mobil->kepemilikan)->where('kode_mobil',request()->id)->first();
            // $id_pemilik = $cek_pemilik->id;
            // $pemilik_detail = PemilikDetail::find($id_pemilik);
            // $pemilik_detail->delete();
            $mobil->delete();

            // $pemilik = Pemilik::find($mobil->kepemilikan);
            // $pemilik->total_mobil = $pemilik->total_mobil - 1;
            // $pemilik->save();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$mobil->nopol.'] telah dihapus.'
            ];
            return response()->json($message);
        } else {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$mobil->nopol.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
        
    }
}
