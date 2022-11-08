<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Hasilbagi;
use App\Models\HasilbagiDetail;
use App\Models\GudangDetail;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Spb;
use App\Models\TruckingnonDetail;
use App\Models\Customer;
use App\Models\SpbnonDetail;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Signature;
use Carbon;
use DB;
use PDF;

class HasilbagiController extends Controller
{
    public function index()
    {
        $create_url = route('hasilbagi.create');
        $Sopir = Sopir::pluck('nama_sopir','id');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.hasilbagi.index',compact('create_url','period', 'nama_lokasi','nama_company','Sopir'));
    }

    public function getkode(){
        $get = Hasilbagi::get();
        $leng = count($get);

        $data = array();

        foreach ($get as $rowdata){
            $kode_sopir = $rowdata->kode_sopir;

            $data[] = array(
                'kode_sopir'=>$kode_sopir,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek = Sopir::where('kode_sopir', $data[$i]['kode_sopir'])->first();

            if($cek != null){
                $id = $cek->id;

                $tabel_baru = [
                    'kode_sopir'=>$id,
                ];
                $update = Hasilbagi::where('kode_sopir', $data[$i]['kode_sopir'])->update($tabel_baru);   
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
            return Datatables::of(Hasilbagi::with('sopir')->orderBy('created_at','desc'))->make(true);
    }

    public function getdata()
    {
        $sopir = Sopir::find(request()->id);

            $output = array(
                'nis'=>$sopir->nis,
                'gaji'=>$sopir->gaji,
                'tabungan'=>$sopir->tabungan,
            );
       
        return response()->json($output);
    }

    public function getdata2()
    {
        $sopir = Sopir::find(request()->id);

            $output = array(
                'nis'=>$sopir->nis,
                'gaji'=>$sopir->gaji,
                'tabungan'=>$sopir->tabungan,
            );
       
        return response()->json($output);
    }

    public function export2(){
        $request = $_GET['no_hasilbagi'];
        if(isset($_GET['ttd'])){
            $format_ttd = $_GET['ttd']; 
        }else{
            $format_ttd = 0;
        }
        
        $hasilbagi = Hasilbagi::find($request);
        $hasilbagidetail = HasilbagiDetail::with('mobil','customer','gudangdetail')->where('no_hasilbagi',$request)->get();
        $leng = count($hasilbagidetail);

        $get_nama = $hasilbagi->kode_sopir;
        $sopir = Sopir::find($get_nama);

        $dt = Carbon\Carbon::now();
        $date_now = Carbon\Carbon::parse($dt)->format('d/m/Y');

        $tgl = $hasilbagi->tanggal_hasilbagi;
        $date=date_create($tgl);

        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;

        $ttd = $hasilbagi->created_by;
        $diperiksa1 = Signature::where('jabatan','AST1')->first();
        $diperiksa2 = Signature::where('jabatan','AST2')->first();
        $disetujui = Signature::where('jabatan','MGR1')->first();
        $diketahui = Signature::where('jabatan','DIREKTUR UTAMA')->first();

        $pdf = PDF::loadView('/admin/hasilbagi/pdf', compact('hasilbagidetail','hasilbagi', 'date_now','date','sopir','request','nama','nama2','dt','ttd','diperiksa2','diperiksa1','disetujui','diketahui','leng','format_ttd'));
        $pdf->setPaper([0, 0, 684, 792], 'landscape');

        return $pdf->stream('Hasil Bagi Usaha Sopir '.$request.'.pdf');
    }

    public function detail($hasilbagi)
    {
        $hasilbagi = Hasilbagi::find($hasilbagi);
        $no_hasilbagi = $hasilbagi->no_hasilbagi;

        $tgl = $hasilbagi->tanggal_hasilbagi;
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

        $get_nama = Sopir::find($hasilbagi->kode_sopir);
        $nama_sopir = $get_nama->nama_sopir;

        $hasilbagidetail = HasilbagiDetail::where('no_hasilbagi', $hasilbagi->no_hasilbagi)->orderBy('created_at','desc')->get();

        $Gudang = Customer::pluck('nama_customer','id');

        $Mobil = Mobil::pluck('nopol','kode_mobil');

        $list_url= route('hasilbagi.index');
                    
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.hasilbagidetail.index', compact('hasilbagi','hasilbagidetail','list_url','period','nama_lokasi','nama_company','Mobil','Gudang', 'nama_sopir'));
    }

    public function Showdetail()
    {
        $hasilbagidetail = HasilbagiDetail::with('mobil','gudangdetail')->where('no_hasilbagi',request()->id)->orderBy('tanggal_kembali', 'desc')->get();
    
        $output = array();
        
            foreach($hasilbagidetail as $row)
            {
                $no_spb = $row->no_spb;
                $tanggal_spb = $row->tanggal_spb;
                $tanggal_kembali = $row->tanggal_kembali;
                $kode_mobil = $row->mobil->nopol;
                $kode_container = $row->kode_container;
                if($row->kode_gudang != '-'){
                    $kode_gudang = $row->gudangdetail->nama_gudang;
                }else{
                    $kode_gudang = $row->kode_gudang;
                }
                $tarif = $row->tarif;
                $uang_jalan = $row->uang_jalan;
                $bbm = $row->bbm;
                $sisa = $row->sisa;
                $sisa_ujbbm = $row->sisa_ujbbm;
                $dari = $row->dari;
                $tujuan = $row->tujuan;

                $output[] = array(
                    'no_spb'=>$no_spb,
                    'tanggal_spb'=>$tanggal_spb,
                    'tanggal_kembali'=>$tanggal_kembali,
                    'kode_mobil'=>$kode_mobil,
                    'kode_container'=>$kode_container,
                    'kode_gudang'=>$kode_gudang,
                    'tarif'=>$tarif,
                    'uang_jalan'=>$uang_jalan,
                    'bbm'=>$bbm,
                    'sisa'=>$sisa,
                    'sisa_ujbbm'=>$sisa_ujbbm,
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

            $permintaan = Hasilbagi::find(request()->id);

            $tgl = $permintaan->tanggal_hasilbagi;
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

            $getspb = Spb::join('hasilbagi_detail','hasilbagi_detail.no_spb','=','spb.no_spb')->where('hasilbagi_detail.no_hasilbagi',request()->id)->get();

            foreach($getspb as $row)
            {
                $row->status_hasilbagi = '2';
                $row->save();
            }

            $getspbnon = TruckingnonDetail::select('truckingnoncontainer_detail.*')->join('hasilbagi_detail','hasilbagi_detail.no_spb','=','truckingnoncontainer_detail.no_spb')->where('hasilbagi_detail.no_hasilbagi',request()->id)->get();

            foreach($getspbnon as $row2)
            {
                $row2->status_hasilbagi = '2';
                $row2->save();
            }

                $permintaan->status = "POSTED";
                $permintaan->save();

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Post Hasil Bagi Usaha: '.$permintaan->no_hasilbagi.'.','created_by'=>$nama,'updated_by'=>$nama];
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

            $permintaan = Hasilbagi::find(request()->id);
            
            $tgl = $permintaan->tanggal_hasilbagi;
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

            $getspb = Spb::join('hasilbagi_detail','hasilbagi_detail.no_spb','=','spb.no_spb')->where('hasilbagi_detail.no_hasilbagi',request()->id)->get();

            foreach($getspb as $row)
            {
                $row->status_hasilbagi = '1';
                $row->save();
            }

            $getspbnon = TruckingnonDetail::select('truckingnoncontainer_detail.*')->join('hasilbagi_detail','hasilbagi_detail.no_spb','=','truckingnoncontainer_detail.no_spb')->where('hasilbagi_detail.no_hasilbagi',request()->id)->get();

            foreach($getspbnon as $row2)
            {
                $row2->status_hasilbagi = '1';
                $row2->save();
            }

            $getall = HasilbagiDetail::where('no_hasilbagi',request()->id)->delete();

            $total = [
                'total_item'=>0,
                'nilai_gaji'=>0,
                'nilai_tabungan'=>0,
                'gt_hbu'=>0,
            ];

            $update_total = Hasilbagi::where('no_hasilbagi', request()->id)->update($total);

                $permintaan->status = "OPEN";
                $permintaan->save();    

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Unpost No. Hasil Bagi Usaha: '.$permintaan->no_hasilbagi.'.','created_by'=>$nama,'updated_by'=>$nama];

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
        $kode_sopir = $request->kode_sopir;
        $get_nama = Sopir::find($kode_sopir);
        $cek = Hasilbagi::where('kode_sopir',$kode_sopir)->where('status','OPEN')->first();
        if($cek != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Masih ada transaksi dengan sopir ['.$get_nama->nama_sopir.'] yang OPEN.'
            ];
            return response()->json($message);
        } 

        $tgl = $request->tanggal_hasilbagi;
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

        Hasilbagi::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_hasilbagi()
    {
        $no_hasilbagi = request()->id;
        $data = Hasilbagi::find($no_hasilbagi);
        $output = array(
            'no_hasilbagi'=>$data->no_hasilbagi,
            'tanggal_hasilbagi'=>$data->tanggal_hasilbagi,
            'kode_sopir'=>$data->kode_sopir,
            'nis'=>$data->nis,
            'spb_dari'=>$data->spb_dari,
            'spb_sampai'=>$data->spb_sampai,
            'gaji'=>$data->gaji,
            'tabungan'=>$data->tabungan,
            'honor_kenek'=>$data->honor_kenek,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_hasilbagi = $request->no_hasilbagi;
        $request->validate([
            'no_hasilbagi'=>'required',
            'tanggal_hasilbagi'=> 'required',
            'kode_sopir'=> 'required',
        ]);

        $tgl = $request->tanggal_hasilbagi;
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

          Hasilbagi::find($request->no_hasilbagi)->update($request->all());
       
          $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
    }

    public function hapus_hasilbagi()
    {   
        $no_hasilbagi = request()->id;
        $hasilbagi = Hasilbagi::find(request()->id);

            $hasilbagi->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$hasilbagi->no_hasilbagi.'] telah dihapus.'
            ];
            return response()->json($message);
    }

}
