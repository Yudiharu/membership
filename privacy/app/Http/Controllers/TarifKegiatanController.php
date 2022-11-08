<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Customer;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Joborder;
use App\Models\Kegiatan;
use App\Models\JenisHarga;
use App\Models\TarifKegiatan;
use App\Models\TarifKegiatanCfs;
use App\Models\TarifKegiatanContainer;
use App\Models\TarifCfsAlat;
use App\Models\TarifContainerSize;
use App\Models\Alat;
use App\Models\Sizecontainer;
use Carbon;
use DB;

class TarifKegiatanController extends Controller
{
    public function index()
    {
        $create_url = route('tarifkegiatan.create');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $Kegiatan = Kegiatan::pluck('description','id');
        $Harga = JenisHarga::pluck('description','id');

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.tarifkegiatan.index',compact('create_url','period','nama_lokasi','nama_company','Kegiatan','Harga'));
    }

    public function anyData()
    {
        return Datatables::of(TarifKegiatan::with('kegiatan'))->make(true);
    }

    public function GetDataPallet(){
        $data = TarifKegiatanCfs::where('id_tarif',request()->id)->where('tgl_berlaku', request()->tgl_berlaku)->where('jenis_tarif', request()->jenis_tarif)->orderBy('created_at','asc')->get();
        return response()->json($data);
    }

    public function getDatabyID(){
        $data = TarifKegiatanCfs::where('id_tarif',request()->id)->orderBy('created_at','asc')->get();
        return response()->json($data);
    }

    public function getDatabyID2(){
        return Datatables::of(TarifKegiatanContainer::where('id_tarif',request()->id)->orderBy('created_at','asc'))->make(true);
    }

    public function GetDataAlat(){
        $data = TarifCfsAlat::with('alat')->where('id_tarif',request()->id)->where('tgl_berlaku', request()->tgl_berlaku)->where('jenis_tarif', request()->jenis_tarif)->orderBy('created_at','asc')->get();
        return response()->json($data);
    }

    public function getDataSize(){
        $data = TarifContainerSize::with('size')->where('id_tarif_container',request()->id)->orderBy('created_at','asc')->get();
        return response()->json($data);
    }

    public function tipe()
    {
        $kegiatan = Kegiatan::find(request()->id);
        if ($kegiatan->container == 1) {
            $data = 'CONTAINER';
        }else if ($kegiatan->cfs == 1) {
            $data = 'CFS';
        }else if ($kegiatan->lainnya == 1) {
            $data = 'LAINNYA';
        }else {
            $data = '';
        }
        $output = array(
            'type_kegiatan'=>$data,
        );
        return response()->json($output);
    }

    public function store_detail(Request $request)
    {
        $cektarif = TarifKegiatan::where('id_kegiatan', $request->id_kegiatan)->where('type_kegiatan', $request->type_kegiatan)->first();
        if ($cektarif != null) {
            $message = [
                'success' => false,
                'title' => '➕ <b>GAGAL SIMPAN</b>',
                'message' => 'Data sudah ada.'
            ];
            return response()->json($message);
        }else {
            TarifKegiatan::create($request->all());
        }
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function store_detail_cfs(Request $request)
    {
        if ($request->tgl_berlaku == null || $request->jenis_tarif == null) {
            $message = [
                'success' => false,
                'title' => '➕ <b>GAGAL SIMPAN</b>',
                'message' => 'Tanggal berlaku dan jenis tarif tak boleh dikosongkan !!'
            ];
            return response()->json($message);
        }

        $cekcfs = TarifKegiatanCfs::where('type_pallet', $request->type_pallet)->where('tgl_berlaku', $request->tgl_berlaku)->where('jenis_tarif', $request->jenis_tarif)->first();
        if ($cekcfs != null) {
            $message = [
                'success' => false,
                'title' => '➕ <b>GAGAL SIMPAN</b>',
                'message' => 'Tidak dapat mendaftarkan type pallet yg sudah terdaftar di tanggal yg sama.'
            ];
            return response()->json($message);
        }else {
            TarifKegiatanCfs::create($request->all());
        }
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function store_detail_alat(Request $request)
    {
        if ($request->tgl_berlaku == null || $request->jenis_tarif == null) {
            $message = [
                'success' => false,
                'title' => '➕ <b>GAGAL SIMPAN</b>',
                'message' => 'Tanggal berlaku dan jenis tarif tak boleh dikosongkan !!'
            ];
            return response()->json($message);
        }

        $cek = TarifCfsAlat::where('kode_alat', $request->kode_alat)->where('tgl_berlaku', $request->tgl_berlaku)->where('jenis_tarif', $request->jenis_tarif)->first();
        if ($cek != null) {
            $message = [
                'success' => false,
                'title' => '➕ <b>GAGAL</b>',
                'message' => 'Kode Alat sudah ada.'
            ];
            return response()->json($message);
        }else {
            TarifCfsAlat::create($request->all());
        }
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function store_size(Request $request)
    {
        $cek = TarifContainerSize::where('id_tarif_container', $request->id_tarif_container)->where('kode_size', $request->kode_size)->first();
        if ($cek != null) {
            $message = [
                'success' => false,
                'title' => '➕ <b>GAGAL</b>',
                'message' => 'Kode Size sudah ada.'
            ];
            return response()->json($message);
        }else {
            TarifContainerSize::create($request->all());
        }
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function edit_pallet()
    {
        $cek = TarifKegiatanCfs::find(request()->id);
        $cekcfs = TarifKegiatanCfs::where('id_tarif', $cek->id_tarif)->where('type_pallet', request()->type_pallet)->where('jenis_tarif', $cek->jenis_tarif)->first();
        if ($cekcfs != null) {
            if ($cekcfs->type_pallet != request()->type_pallet) {
                $message = [
                    'success' => false,
                    'title' => '💢',
                    'message' => '<b>Gagal Edit, Pallet sudah ada.</b>'
                ];
                return response()->json($message);
            }else {
                $update_tabel = [
                    'biaya_storage'=>request()->biaya_storage,
                    'biaya_receiving'=>request()->biaya_receiving,
                    'biaya_delivery'=>request()->biaya_delivery,
                ];
            }
        }else {
            $update_tabel = [
                'type_pallet'=>request()->type_pallet,
                'biaya_storage'=>request()->biaya_storage,
                'biaya_receiving'=>request()->biaya_receiving,
                'biaya_delivery'=>request()->biaya_delivery,
            ];
        }

        $tabelcfs = TarifKegiatanCfs::where('id', request()->id)->update($update_tabel);
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function edit_alat()
    {
        $cekalat = TarifCfsAlat::where('id_tarif', request()->id_tarif)->where('kode_alat', request()->kode_alat)->first();
        if ($cekalat != null) {
            $cekalat2 = TarifCfsAlat::find(request()->id);
            if ($cekalat2->kode_alat != request()->kode_alat) {
                $message = [
                    'success' => false,
                    'title' => '💢',
                    'message' => '<b>Gagal Edit, Kode Alat sudah ada.</b>'
                ];
                return response()->json($message);
            }else {
                $update_tabel = [
                    'per_jam'=>request()->perjam,
                    'per_ton'=>request()->perton,
                ];
            }
        }else {
            $update_tabel = [
                'kode_alat'=>request()->kode_alat,
                'per_jam'=>request()->perjam,
                'per_ton'=>request()->perton,
            ];
        }

        $tabelalat = TarifCfsAlat::where('id', request()->id)->update($update_tabel);
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function hapus_alat()
    {
        $tabelalat = TarifCfsAlat::where('id', request()->id)->delete();
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function edit_size()
    {
        $cekcont = TarifContainerSize::where('id_tarif_container', request()->id_tarif_container)->where('kode_size', request()->kode_size)->first();
        if ($cekcont != null) {
            $cekcont2 = TarifContainerSize::find(request()->id);
            if ($cekcont2->kode_size != request()->kode_size) {
                $message = [
                    'success' => false,
                    'title' => '💢',
                    'message' => '<b>Gagal Edit, Kode Size sudah ada.</b>'
                ];
                return response()->json($message);
            }else {
                $update_tabel = [
                    'harga_empty'=>request()->harga_empty,
                    'harga_loaded'=>request()->harga_loaded,
                ];
            }
        }else {
            $update_tabel = [
                'kode_size'=>request()->kode_size,
                'harga_empty'=>request()->harga_empty,
                'harga_loaded'=>request()->harga_loaded,
            ];
        }

        $tabelalat = TarifContainerSize::where('id', request()->id)->update($update_tabel);
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function hapus_size()
    {
        $tabelsize = TarifContainerSize::where('id', request()->id)->delete();
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function edit_detail_cfs()
    {
        $cektarif = TarifKegiatan::where('id', request()->id_tarif)->first();
        if ($cektarif->type_kegiatan == 'CFS') {
            $data = TarifKegiatanCfs::find(request()->id);
            $output = array(
                'id'=>$data->id,
                'id_tarif'=>$data->id_tarif,
                'type_pallet'=>$data->type_pallet,
                'tgl_berlaku'=>$data->tgl_berlaku,
                'biaya_storage'=>$data->biaya_storage,
                'biaya_receiving'=>$data->biaya_receiving,
                'biaya_delivery'=>$data->biaya_delivery,
            );
            return response()->json($output);
        }
    }

    public function detail($kode)
    {
        $tarif = TarifKegiatan::with('kegiatan')->where('id',$kode)->first();

        // if ($tarif->type_kegiatan == 'CFS') {
        //     $tarifkegiatan = TarifKegiatan::where('id_kegiatan', $tarif->id)->orderBy('created_at','desc')->get();
        // }
        
        $Alat = Alat::pluck('nama_alat','kode_alat');
        $Size = Sizecontainer::pluck('nama_size','kode_size');
        $Jenis = JenisHarga::pluck('description','id');

        $list_url= route('tarifkegiatan.index');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.tarifkegiatan.index_detail', compact('tarif','tarifkegiatan','list_url','Mobil','period','nama_lokasi','nama_company','vendor','jenis','Alat','Size','Jenis'));
    }

    public function edit_detail()
    {
        $kode_customer = request()->id;
        $data = TarifKegiatan::find($kode_customer);
        $output = array(
            'id'=>$data->id,
            'id_kegiatan'=>$data->id_kegiatan,
            'jenis_harga'=>$data->jenis_harga,
            'type_kegiatan'=>$data->type_kegiatan,
        );
        return response()->json($output);
    }

    public function hapus_detail()
    {
        $hapus = TarifKegiatan::find(request()->id);
        $hapus->delete();

        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function edit_customer()
    {
        $kode_customer = request()->id;
        $data = Customer::find($kode_customer);
        $output = array(
            'kode_customer'=>$data->id,
        );
        return response()->json($output);
    }

    public function updateAjax_detail(Request $request)
    {
        $cektarif = TarifKegiatan::where('id', request()->id_tarif)->first();
        
        if ($cektarif->type_kegiatan == 'CFS') {
            $update = TarifKegiatanCfs::find(request()->id)->update($request->all());
        }

        $message = [
            'success' => true,
        ];
        return response()->json($message);

    }

    public function hapus_detail_tarif()
    {
        $cektarif = TarifKegiatan::where('id', request()->id_tarif)->first();
        if ($cektarif->type_kegiatan == 'CFS') {
            $hapus = TarifKegiatanCfs::find(request()->id);
            $cekalat = TarifCfsAlat::where('id_tarif_cfs', $hapus->id)->first();
            if ($cekalat != null) {
                $message = [
                    'success' => false,
                    'title' => 'Hapus',
                    'message' => 'Masih ada tipe alat yg terdaftar pada detail tarif ini.'
                ];
                return response()->json($message);
            }else {
                $hapus->delete();
            }
        }else if ($cektarif->type_kegiatan == 'CONTAINER') {
            $hapus = TarifKegiatanContainer::find(request()->id);
            $cekalat = TarifContainerSize::where('id_tarif_container', $hapus->id)->first();
            if ($cekalat != null) {
                $message = [
                    'success' => false,
                    'title' => 'Hapus',
                    'message' => 'Masih ada kode size yg terdaftar pada detail tarif ini.'
                ];
                return response()->json($message);
            }else {
                $hapus->delete();
            }
        }
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

}
