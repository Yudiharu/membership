<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Signature;
use App\Models\Spbnon;
use App\Models\SpbnonDetail;
use App\Models\tb_akhir_bulan;
use App\Models\user_history;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Port;
use Carbon;
use DB;

class SpbnonController extends Controller
{
    public function index()
    {
        $create_url = route('spbnon.create');
        $Company = Company::pluck('nama_company','kode_company');
        $Mobil = Mobil::pluck('nopol','kode_mobil');
        $Sopir = Sopir::pluck('nama_sopir','kode_sopir');
        $Port = Port::pluck('nama_port','kode_port');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.spbnon.index',compact('create_url','period', 'nama_lokasi','nama_company','Mobil','Sopir','Company','Port'));
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Spbnon::with('mobil','sopir','port','port1')->orderBy('created_at','desc'))->make(true);
    }

    public function detail($spbnon)
    {
        $spbnon = Spbnon::find($spbnon);
        $no_spbnon = $spbnon->no_spbnon;

        $data = Spbnon::find($no_spbnon);
                    
        $spbnondetail = SpbnonDetail::where('no_spbnon', $spbnon->no_spbnon)->orderBy('created_at','desc')->get();

        $list_url= route('spbnon.index');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.spbnondetail.index', compact('spbnon','spbnondetail','list_url','period','nama_lokasi','nama_company'));
    }

    public function Showdetail()
    {
        $spbnondetail = SpbnonDetail::where('no_spbnon',request()->id) ->orderBy('created_at', 'desc')->get();
    
        $output = array();
        
        foreach($spbnondetail as $row)
        {
            $kode_item = $row->kode_item;
            $qty = $row->qty;
            $berat_satuan = $row->berat_satuan;
            $total_berat = $row->total_berat;
            $keterangan = $row->keterangan;
            
            $output[] = array(
                'kode_item'=>$kode_item,
                'qty'=>$qty,
                'berat_satuan'=>$berat_satuan,
                'total_berat'=>$total_berat,
                'keterangan'=>$keterangan,
            );
        }

        return response()->json($output);
    }

    public function Post()
    {
        $level = auth()->user()->level;
        $cek_bulan = tb_akhir_bulan::where('status_periode','Disable')->first();

        if($level == 'superadministrator' && $cek_bulan == null){
            $permintaan = Spbnon::find(request()->id);

            $tgl = $permintaan->tgl_spbnon;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

                $permintaan->status = "POSTED";
                $permintaan->save();

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Post SPB: '.$permintaan->no_spbnon.'.','created_by'=>$nama,'updated_by'=>$nama];
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
            $permintaan = Spbnon::find(request()->id);
            
            $tgl = $permintaan->tgl_spbnon;
            $tahun = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->year;
            $bulan = Carbon\Carbon::createFromFormat('Y-m-d',$tgl)->month;

                $permintaan->status = "OPEN";
                $permintaan->save();    

                $nama = auth()->user()->name;
                $tmp = ['nama' => $nama,'aksi' => 'Unpost No. SPB: '.$permintaan->no_spbnon.'.','created_by'=>$nama,'updated_by'=>$nama];

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
                try {
                    Spbnon::create($request->all());
                    $message = [
                    'success' => true,
                    'title' => 'Simpan',
                    'message' => 'Data telah disimpan.'
                    ];
                    return response()->json($message);
                }catch (\Exception $exception){
                    $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'Data Gagal di Simpan',
                        'error'=> $exception->getMessage()
                        ];
                    return response()->json($message);
                }
    }

    public function edit_spbnon()
    {
        $no_spbnon = request()->id;
        $data = Spbnon::find($no_spbnon);
        $output = array(
            'no_spbnon'=>$data->no_spbnon,
            'no_spbnonmanual'=>$data->no_spbnonmanual,
            'tgl_spbnon'=>$data->tgl_spbnon,
            'kode_mobil'=>$data->kode_mobil,
            'kode_sopir'=>$data->kode_sopir,
            'tarif_gajisopir'=>$data->tarif_gajisopir,
            'uang_jalan'=>$data->uang_jalan,
            'bbm'=>$data->bbm,
            'dari'=>$data->dari,
            'tujuan'=>$data->tujuan,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $no_spbnon = $request->no_spbnon;
        $request->validate([
            'no_spbnon'=>'required',
            'no_spbnonmanual'=> 'required',
            'tgl_spbnon'=> 'required',
            'kode_mobil'=> 'required',
            'kode_sopir'=> 'required',
            'tarif_gajisopir'=> 'required',
            'uang_jalan'=> 'required',
            'bbm'=> 'required',
        ]);

          Spbnon::find($request->no_spbnon)->update($request->all());
       
          $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
    }

    public function hapus_spbnon()
    {   
        $no_spbnon = request()->id;
        $spbnon = Spbnon::find(request()->id);

            $spbnon->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$spbnon->no_spbnon.'] telah dihapus.'
            ];
            return response()->json($message);
    }

}
