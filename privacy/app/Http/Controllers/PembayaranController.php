<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Pemilik;
use App\Models\Vendor;
use App\Models\Spb;
use App\Models\TruckingnonDetail;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Signature;
use Carbon;
use DB;
use PDF;

class PembayaranController extends Controller
{
    public function index()
    {
        $create_url = route('pembayaran.create');
        $Pemilik = Pemilik::join('u5611458_db_pusat.vendor','pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->where('u5611458_db_pusat.vendor.nama_vendor','<>','GEMILANG UTAMA INTERNASIONAL, PT')->pluck('nama_vendor','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.pembayaran.index',compact('create_url','period', 'nama_lokasi','nama_company','Pemilik'));
    }

    public function getkode(){
        $get = Pembayaran::get();
        $leng = count($get);

        $data = array();

        foreach ($get as $rowdata){
            $kode_pemilik = $rowdata->kode_pemilik;

            $data[] = array(
                'kode_pemilik'=>$kode_pemilik,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek = Vendor::where('kode_vendor', $data[$i]['kode_pemilik'])->first();

            if($cek != null){
                $id = $cek->id;

                $tabel_baru = [
                    'kode_pemilik'=>$id,
                ];
                $update = Pembayaran::where('kode_pemilik', $data[$i]['kode_pemilik'])->update($tabel_baru);   
            }
        }


        $get = Spb::get();
        $leng = count($get);

        $data = array();

        foreach ($get as $rowdata){
            $kode_pemilik = $rowdata->kode_pemilik;

            $data[] = array(
                'kode_pemilik'=>$kode_pemilik,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek = Vendor::where('kode_vendor', $data[$i]['kode_pemilik'])->first();

            if($cek != null){
                $id = $cek->id;

                $tabel_baru = [
                    'kode_pemilik'=>$id,
                ];
                $update = Spb::where('kode_pemilik', $data[$i]['kode_pemilik'])->update($tabel_baru);   
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
            return Datatables::of(Pembayaran::select('pembayaran_pemilik.*','u5611458_db_pusat.vendor.nama_vendor')->join('u5611458_db_pusat.vendor','pembayaran_pemilik.kode_pemilik','=','u5611458_db_pusat.vendor.id')->orderBy('created_at','desc'))->make(true);
    }

    public function export2(){
        $request = $_GET['no_pembayaran'];
        if(isset($_GET['ttd'])){
            $format_ttd = $_GET['ttd']; 
        }else{
            $format_ttd = 0;
        }
        // $getspb = Spb::join('pembayaranpemilik_detail','pembayaranpemilik_detail.no_spb','=','spb.no_spb')->where('spb.status_spb',1)->where('pembayaranpemilik_detail.no_pembayaran',$request)->get();

        // foreach($getspb as $row)
        // {
        //     $row->status_spb = '2';
        //     $row->save();
        // }

        // $getspbnon = TruckingnonDetail::select('truckingnoncontainer_detail.*')->join('pembayaranpemilik_detail','pembayaranpemilik_detail.no_spb','=','truckingnoncontainer_detail.no_spb')->where('truckingnoncontainer_detail.status_spbnon',1)->where('pembayaranpemilik_detail.no_pembayaran',$request)->get();

        // foreach($getspbnon as $row2)
        // {
        //     $row2->status_spbnon = '2';
        //     $row2->save();
        // }

        
        $pembayaran = Pembayaran::find($request);
        $pembayarandetail = PembayaranDetail::with('mobil','sopir','gudangdetail')->where('no_pembayaran',$request)->get();

        $get_nama = $pembayaran->kode_pemilik;
        $pemilik = Vendor::find($get_nama);

        $dt = Carbon\Carbon::now();
        $date_now = Carbon\Carbon::parse($dt)->format('d/m/Y');

        $tgl = $pembayaran->tanggal_pembayaran;
        $date=date_create($tgl);

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;

        $ttd = $pembayaran->created_by;
        $diperiksa = Signature::where('jabatan','DIREKTUR')->first();
        $disetujui = Signature::where('jabatan','MANAGER OPERASIONAL')->first();
        $diketahui = Signature::where('jabatan','DIREKTUR UTAMA')->first();

        $pdf = PDF::loadView('/admin/pembayaran/pdf', compact('pembayarandetail','pembayaran', 'date_now','date','pemilik','request','nama','nama2','dt','ttd','diperiksa','disetujui','diketahui','format_ttd'));
        $pdf->setPaper([0, 0, 684, 792], 'landscape');

        return $pdf->stream('Pembayaran Pemilik Mobil '.$request.'.pdf');
    }

    public function detail($pembayaran)
    {
        $pembayaran = Pembayaran::find($pembayaran);
        $no_pembayaran = $pembayaran->no_pembayaran;

        $tgl = $pembayaran->tanggal_pembayaran;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
        //     alert()->success('Status POSTED / Periode Telah CLOSED: '.$tgl,'GAGAL!')->persistent('Close');
        //     return redirect()->back();
        // }

        $get_nama = Vendor::find($pembayaran->kode_pemilik);
        $nama_pemilik = $get_nama->nama_pemilik;

        $list_url= route('pembayaran.index');
                    
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.pembayarandetail.index', compact('pembayaran','list_url','period','nama_lokasi','nama_company','nama_pemilik'));
    }

    public function Showdetail()
    {
        $pembayarandetail = PembayaranDetail::with('mobil','sopir','gudangdetail')->where('no_pembayaran',request()->id)->orderBy('tgl_kembali', 'desc')->get();

        $output = array();
        
            foreach($pembayarandetail as $row)
            {
                $no_joborder = $row->no_joborder;
                $no_spb = $row->no_spb;
                $tgl_spb = $row->tgl_spb;
                $tgl_kembali = $row->tgl_kembali;
                $kode_mobil = $row->mobil->nopol;

                $kode_sopir = $row->kode_sopir;
                $cek = Sopir::find($kode_sopir);
                if($cek != null){
                    $kode_sopir = $row->sopir->nama_sopir;
                }

                $kode_container = $row->kode_container;
                if($row->kode_gudang != '-'){
                    $kode_gudang = $row->gudangdetail->nama_gudang;
                }else{
                    $kode_gudang = $row->kode_gudang;
                }
                $tarif = $row->tarif;
                $uang_jalan = $row->uang_jalan;
                $sisa = $row->sisa;
                $dari = $row->dari;
                $tujuan = $row->tujuan;

                $output[] = array(
                    'no_joborder'=>$no_joborder,
                    'no_spb'=>$no_spb,
                    'tgl_spb'=>$tgl_spb,
                    'tgl_kembali'=>$tgl_kembali,
                    'kode_mobil'=>$kode_mobil,
                    'kode_sopir'=>$kode_sopir,
                    'kode_container'=>$kode_container,
                    'kode_gudang'=>$kode_gudang,
                    'tarif'=>$tarif,
                    'uang_jalan'=>$uang_jalan,
                    'sisa'=>$sisa,
                    'dari'=>$dari,
                    'tujuan'=>$tujuan,
                );
            }

        return response()->json($output);
    }

    public function Post()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

            $permintaan = Pembayaran::find(request()->id);

            $tgl = $permintaan->tanggal_pembayaran;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

            $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
            $tgl2 = $cek_bulan2->periode;
            $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
            $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

            // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
            //     $message = [
            //             'success' => false,
            //             'title' => 'Update',
            //             'message' => 'Data gagal di POSTING, re-open [Bulan '.$bulan.'; Tahun '.$tahun.'].'
            //         ];
            //     return response()->json($message);
            // }

            $getspb = Spb::join('pembayaranpemilik_detail','pembayaranpemilik_detail.no_spb','=','spb.no_spb')->where('pembayaranpemilik_detail.no_pembayaran',request()->id)->get();

            foreach($getspb as $row)
            {
                $row->status_spb = '2';
                $row->save();
            }

            $getspbnon = TruckingnonDetail::select('truckingnoncontainer_detail.*')->join('pembayaranpemilik_detail','pembayaranpemilik_detail.no_spb','=','truckingnoncontainer_detail.no_spb')->where('pembayaranpemilik_detail.no_pembayaran',request()->id)->get();

            foreach($getspbnon as $row2)
            {
                $row2->status_spbnon = '2';
                $row2->save();
            }

                $permintaan->status = "POSTED";
                $permintaan->save();

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Post Pembayaran: '.$permintaan->no_pembayaran.'.','created_by'=>$nama,'updated_by'=>$nama];
                user_history::create($tmp);

                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data berhasil di POST.'
                    ];
                return response()->json($message);
    }

    public function Unpost()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

            $permintaan = Pembayaran::find(request()->id);
            $tgl = $permintaan->tanggal_pembayaran;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

            $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
            $tgl2 = $cek_bulan2->periode;
            $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
            $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

            // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
            //     $message = [
            //             'success' => false,
            //             'title' => 'Update',
            //             'message' => 'Data gagal di UNPOSTING, re-open [Bulan '.$bulan.'; Tahun '.$tahun.'].'
            //         ];
            //     return response()->json($message);
            // }

            $getspb = Spb::join('pembayaranpemilik_detail','pembayaranpemilik_detail.no_spb','=','spb.no_spb')->where('pembayaranpemilik_detail.no_pembayaran',request()->id)->get();

            foreach($getspb as $row)
            {
                $row->status_spb = '1';
                $row->save();
            }

            $getspbnon = TruckingnonDetail::select('truckingnoncontainer_detail.*')->join('pembayaranpemilik_detail','pembayaranpemilik_detail.no_spb','=','truckingnoncontainer_detail.no_spb')->where('pembayaranpemilik_detail.no_pembayaran',request()->id)->get();

            foreach($getspbnon as $row2)
            {
                $row2->status_spbnon = '1';
                $row2->save();
            }

            $getall = PembayaranDetail::where('no_pembayaran',request()->id)->delete();

            $total = [
                'total_item'=>0,
            ];

            $update_total = Pembayaran::where('no_pembayaran', request()->id)->update($total);

                $permintaan->status = "OPEN";
                $permintaan->save();    

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Pembayaran: '.$permintaan->no_pembayaran.'.','created_by'=>$nama,'updated_by'=>$nama];

                user_history::create($tmp);

                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data berhasil di UNPOST.'
                    ];
                return response()->json($message);
    }

    public function store(Request $request)
    {
        $kode_pemilik = $request->kode_pemilik;
        $get_nama = Vendor::find($kode_pemilik);
        $cek = Pembayaran::where('kode_pemilik',$kode_pemilik)->where('status','OPEN')->first();
        if($cek != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Masih ada transaksi dengan pemilik ['.$get_nama->nama_pemilik.'] yang OPEN.'
            ];
            return response()->json($message);
        } 

        $tgl = $request->tanggal_pembayaran;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
        //     $message = [
        //             'success' => false,
        //             'title' => 'Update',
        //             'message' => '<b>Periode</b> ['.$tgl.'] <b>Telah Ditutup / Belum Dibuka</b>'
        //         ];
        //     return response()->json($message);
        // }

        Pembayaran::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_pembayaran()
    {
        $no_pembayaran = request()->id;
        $data = Pembayaran::find($no_pembayaran);
        $output = array(
            'no_pembayaran'=>$data->no_pembayaran,
            'tanggal_pembayaran'=>$data->tanggal_pembayaran,
            'tanggalkembali_dari'=>$data->tanggalkembali_dari,
            'tanggalkembali_sampai'=>$data->tanggalkembali_sampai,
            'kode_pemilik'=>$data->kode_pemilik,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_pembayaran = $request->no_pembayaran;
        $request->validate([
            'no_pembayaran'=>'required',
            'tanggal_pembayaran'=> 'required',
            'kode_pemilik'=> 'required',
        ]);

        $tgl = $request->tanggal_pembayaran;
        $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
        $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

        $cek_bulan2 = tb_akhir_bulan::where('status_periode','Open')->orwhere('reopen_status','true')->first();
        $tgl2 = $cek_bulan2->periode;
        $tahun2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->year;
        $bulan2 = Carbon\Carbon::createFromFormat('Y-m-d',$tgl2)->month;

        // if (($tahun != $tahun2) || ($bulan != $bulan2)) {
        //     $message = [
        //             'success' => false,
        //             'title' => 'Update',
        //             'message' => '<b>Periode</b> ['.$tgl.'] <b>Telah Ditutup / Belum Dibuka</b>'
        //         ];
        //     return response()->json($message);
        // }

          Pembayaran::find($request->no_pembayaran)->update($request->all());
       
          $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
    }

    public function hapus_pembayaran()
    {   
        $no_pembayaran = request()->id;
        $pembayaran = Pembayaran::find(request()->id);

            $pembayaran->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$pembayaran->no_pembayaran.'] telah dihapus.'
            ];
            return response()->json($message);
    }

}
