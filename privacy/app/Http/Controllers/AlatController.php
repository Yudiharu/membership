<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Alat;
use App\Models\LokasiAlat;
use App\Models\PremiOperator;
use App\Models\PremiHelper;
use App\Models\TarifAlat;
use App\Models\PemakaianAlatDetail;
use App\Models\InvoicearitiDetail;
use App\Models\MasterLokasi;
use App\Models\Pemakaian;
use App\Models\Pemakaianban;
use App\Models\tb_akhir_bulan;
use App\Models\Company;
use Carbon;

class AlatController extends Controller
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
        $konek = self::konek();
        $create_url = route('alat.create');
        $lokasi= MasterLokasi::pluck('nama_lokasi','kode_lokasi');

        // $jenis = JenisAlat::pluck('kode_jenis','kode_jenis');

        $tgl_jalan = tb_akhir_bulan::on($konek)->where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');

        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        
        return view('admin.alat.index',compact('create_url','period','lokasi', 'nama_lokasi','nama_company'));
    }

    public function anyData()
    {
        $konek = self::konek();
        $level = auth()->user()->level;
        $lokasi = auth()->user()->kode_lokasi;
        if($lokasi == 'HO'){
            return Datatables::of(Alat::with('masterlokasi')->orderBy('nama_alat','asc'))->make(true);
        }
        else{
            return Datatables::of(Alat::with('masterlokasi')->orderBy('nama_alat','asc'))->make(true);
        }
    }

    public function getDatapremi(){
        $konek = static::konek();
        $data = PremiOperator::with('alat')->where('kode_alat',request()->id)->orderBy('created_at','desc')->get();
        return response()->json($data);
    }

    public function getDatapremi2(){
        $konek = static::konek();
        $data = PremiHelper::with('alat')->where('kode_alat',request()->id)->orderBy('created_at','desc')->get();
        return response()->json($data);
    }

    public function getDatapremi3(){
        $konek = static::konek();
        $data = TarifAlat::with('alat')->where('kode_alat',request()->id)->orderBy('created_at','desc')->get();
        return response()->json($data);
    }

    public function getDatabyID()
    {
        $konek = self::konek();
        return Datatables::of(LokasiAlat::on($konek)->where('kode_alat',request()->kode_customer)->orderBy('created_at','desc'))->make(true);
    }

    public function detaillokasi($kode)
    {
        $konek = self::konek();
        $list_url= route('alat.index');
        $cust = Alat::on($konek)->find($kode);
        $Lokasi = MasterLokasi::pluck('nama_lokasi','kode_lokasi');

        $tgl_jalan = tb_akhir_bulan::on($konek)->where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.alat.indexlokasi', compact('list_url','period','nama_lokasi','nama_company','cust','Lokasi'));
    }

    public function store_lokasi(Request $request)
    {
        $konek = self::konek();
        $kode_customer = $request->kode_alat;
        
        $cek_cust = Alat::on($konek)->find($kode_customer);
        
        if ($request->kode_lokasi != $cek_cust->kode_lokasi) {
            LokasiAlat::on($konek)->create($request->all());
        
            $ceknpwp = LokasiAlat::on($konek)->where('kode_alat', $kode_customer)->orderBy('created_at','desc')->first();
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
        $konek = self::konek();
        $nama_alat = $request->nama_alat;
        $no_asset_alat = $request->no_asset_alat;

        $cek_nama = Alat::on($konek)->where('nama_alat',$nama_alat)->where('no_asset_alat',$no_asset_alat)->first();
        if ($cek_nama==null){
            $Alat = Alat::on($konek)->create($request->all());
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah di Disimpan.',
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Nama Alat Sudah Ada',
            ];
            return response()->json($message);
        }
    }

    public function storepremi(Request $request)
    {   
        $cektgl = PremiOperator::where('kode_alat', $request->kode_alat)->where('tgl_berlaku', $request->tgl_berlaku)->first();
        if ($cektgl != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Premi untuk tanggal '.$request->tgl_berlaku.' sudah ada, silakan gunakan tombol EDIT untuk mengubah.',
            ];
            return response()->json($message);
        }

        $Premi = PremiOperator::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.',
        ];
        return response()->json($message);
    }

    public function storepremi2(Request $request)
    {   
        $cektgl = PremiHelper::where('kode_alat', $request->kode_alat)->where('tgl_berlaku', $request->tgl_berlaku)->first();
        if ($cektgl != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Premi untuk tanggal '.$request->tgl_berlaku.' sudah ada, silakan gunakan tombol EDIT untuk mengubah.',
            ];
            return response()->json($message);
        }

        $Premi = PremiHelper::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.',
        ];
        return response()->json($message);
    }

    public function storepremi3(Request $request)
    {   
        $cektgl = TarifAlat::where('kode_alat', $request->kode_alat)->where('tgl_berlaku', $request->tgl_berlaku)->first();
        if ($cektgl != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Premi untuk tanggal '.$request->tgl_berlaku.' sudah ada, silakan gunakan tombol EDIT untuk mengubah.',
            ];
            return response()->json($message);
        }

        $Premi = TarifAlat::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.',
        ];
        return response()->json($message);
    }

    public function editpremi()
    {   
        $cek = PemakaianAlatDetail::where('kode_alat', request()->kode_alat)->where('tgl_pakai', '>=', request()->tgl_berlaku)->first();
        if ($cek != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Gagal edit !! Premi sudah dipakai dalam Pemakaian Alat.',
            ];
            return response()->json($message);
        }

        $premi = PremiOperator::find(request()->id);
        $update_data = [
            'tgl_berlaku'=>request()->tgl_berlaku,
            'premi_jam_transhipment'=>request()->premi_jam_transhipment,
            'premi_jam_nontranshipment'=>request()->premi_jam_nontranshipment,
            'premi_opr_tembak'=>request()->premi_opr_tembak,
            'hari_libur'=>request()->hari_libur,
        ];

        $premi->update($update_data);

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.',
        ];
        return response()->json($message);
    }

    public function editpremi2()
    {   
        $cek = PemakaianAlatDetail::where('kode_alat', request()->kode_alat)->where('tgl_pakai', '>=', request()->tgl_berlaku)->first();
        if ($cek != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Gagal edit !! Premi sudah dipakai dalam Pemakaian Alat.',
            ];
            return response()->json($message);
        }

        $premi = PremiHelper::find(request()->id);
        $update_data = [
            'tgl_berlaku'=>request()->tgl_berlaku,
            'premi_harian_dk'=>request()->premi_harian_dk,
            'premi_harian_lk'=>request()->premi_harian_lk,
            'hari_libur'=>request()->hari_libur,
        ];

        $premi->update($update_data);

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.',
        ];
        return response()->json($message);
    }

    public function editpremi3()
    {   
        $cek = InvoicearitiDetail::on('mysqlpbm')->where('kode_alat', request()->kode_alat)->first();
        if ($cek != null) {
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Gagal edit !! Premi sudah dipakai dalam Invoice AR Internal.',
            ];
            return response()->json($message);
        }

        $premi = TarifAlat::find(request()->id);
        $update_data = [
            'tgl_berlaku'=>request()->tgl_berlaku,
            'tarif'=>request()->tarif,
        ];

        $premi->update($update_data);

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah di Disimpan.',
        ];
        return response()->json($message);
    }

    public function edit_alat()
    {
        $konek = self::konek();
        $kode_alat = request()->id;
        $data = Alat::on($konek)->find($kode_alat);
        $output = array(
            'kode_alat'=>$data->kode_alat,
            'nama_alat'=>$data->nama_alat,
            'merk'=>$data->merk,
            'type'=>$data->type,
            'kapasitas'=>$data->kapasitas,
            'tahun'=>$data->tahun,
            'no_asset_alat'=>$data->no_asset_alat,
            'kode_lokasi'=>$data->kode_lokasi,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $konek = self::konek();
        $kode = $request->kode_alat;
        $asset_alat = $request->no_asset_alat;
        $cek_kapa = Alat::on($konek)->where('kode_alat',$kode)->first();
        $kode_alat = Pemakaian::on($konek)->where('kode_alat',$kode)->first();
        $asset_alat = Pemakaian::on($konek)->where('no_asset_alat',$asset_alat)->first();
        $kode_alat_ban = Pemakaianban::on($konek)->where('kode_alat',$kode)->first();

        if ($kode_alat == null && $asset_alat == null && $kode_alat_ban == null){
            Alat::on($konek)->find($request->kode_alat)->update($request->all());
            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
        } else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Alat ['.$request->nama_alat. ' / ' .$request->no_asset_alat.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }

    public function hapuspremi()
    {
        $premi = PremiOperator::find(request()->id);
        $premi->delete();
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Premi telah dihapus.'
        ];
        return response()->json($message);
    }

    public function hapuspremi2()
    {
        $premi = PremiHelper::find(request()->id);
        $premi->delete();
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Premi telah dihapus.'
        ];
        return response()->json($message);
    }

    public function hapuspremi3()
    {
        $premi = TarifAlat::find(request()->id);
        $premi->delete();
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Premi telah dihapus.'
        ];
        return response()->json($message);
    }

    public function hapus_alat()
    {   
        $konek = self::konek();
        $kode = request()->id;
        $alat = Alat::on($konek)->find(request()->id);
        $asset_alat = $alat->no_asset_alat;
        $kode_alat = Pemakaian::on($konek)->where('kode_alat',$kode)->first();
        $asset_alat = Pemakaian::on($konek)->where('no_asset_alat',$asset_alat)->first();
        $kode_alat_ban = Pemakaianban::on($konek)->where('kode_alat',$kode)->first();
        $cek_premi = PremiOperator::where('kode_alat', $kode)->first();

        if ($cek_premi != null) {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Masih ada premi operator terdaftar pada alat: '.$alat->nama_alat. ' / ' .$alat->no_asset_alat.'.'
            ];
            return response()->json($message);
        }

        if ($kode_alat == null && $asset_alat == null && $kode_alat_ban == null){
            $alat->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Alat ['.$alat->nama_alat.'] telah dihapus.'
            ];
            return response()->json($message);
        } else {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Alat ['.$alat->nama_alat. ' / ' .$request->no_asset_alat.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
        
    }
}
