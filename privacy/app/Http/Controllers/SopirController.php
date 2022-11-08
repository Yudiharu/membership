<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Sopir;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\PemilikDetail;
use App\Models\Vendor;
use App\Models\Coa;
use App\Models\Systemsetup;
use Carbon;
use DB;

class SopirController extends Controller
{
    public function index()
    {
        $create_url = route('sopir.create');

        $Coa = Coa::select('coa.kode_coa', DB::raw("concat(coa.account,' - ',coa.ac_description) as coas"))->join('u5611458_gui_general_ledger_laravel.coa_detail','coa.kode_coa','=','u5611458_gui_general_ledger_laravel.coa_detail.kode_coa')->where('u5611458_gui_general_ledger_laravel.coa_detail.kode_company', auth()->user()->kode_company)->pluck('coas','coa.kode_coa');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.sopir.index',compact('create_url','Coa','period', 'nama_lokasi','nama_company'));
        
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Sopir::query())->make(true);
    }

    public function getcoa()
    {
        //Coa Hutang Usaha
        $get_setup = Coa::find('153');
        $kode_coa = $get_setup->kode_coa;
        $output = [
            'kode_coa'=>$kode_coa,
        ];
        return response()->json($output);
    }

    public function store(Request $request)
    {
            $nama_sopir = $request->nama_sopir;
            $cek_sopir = Sopir::where('nama_sopir',$nama_sopir)->first();
            if($cek_sopir == null){
                Sopir::create($request->all());

                $cekvendor = Vendor::where(DB::raw("LEFT(nama_vendor,6)"), 'like',$request->nis.'%')->first();
                if ($cekvendor != null) {
                    $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'Penyimpanan NIS pada sopir gagal karena sudah pernah terdaftar.',
                    ];
                    return response()->json($message);
                }else {
                    $tabel_baru = [
                        'type'=>2,
                        'nama_vendor'=>$request->nis.' - '.$request->nama_sopir,
                        'nama_vendor_po'=>$request->nis.' - '.$request->nama_sopir,
                        'alamat'=>$request->alamat,
                        'telp'=>$request->telp,
                        'hp'=>$request->hp,
                        'npwp'=>null,
                        'nama_kontak'=>$request->nama_sopir,
                        'kode_coa'=>$request->coa,
                        'status'=>'Aktif',
                    ];
                    $create = Vendor::create($tabel_baru);
                }

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
                    'message' => 'Nama Sopir Sudah Ada',
                ];
                return response()->json($message);
            }  
    }

    public function edit_sopir()
    {
        $kode_sopir = request()->id;
        $data = Sopir::find($kode_sopir);
        $output = array(
            'kode_sopir'=>$data->id,
            'nama_sopir'=>$data->nama_sopir,
            'alamat'=>$data->alamat,
            'kota'=>$data->kota,
            'kode_pos'=>$data->kode_pos,
            'telp'=>$data->telp,
            'hp'=>$data->hp,
            'nis'=>$data->nis,
            'gaji'=>$data->gaji,
            'tabungan'=>$data->tabungan,
            'no_rekening'=>$data->no_rekening,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_sopir = $request->kode_sopir;

        Sopir::find($request->kode_sopir)->update($request->all());
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_sopir()
    {   
        $kode_sopir = request()->id;
        $sopir = Sopir::find(request()->id);

        $sopir->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$sopir->nama_sopir.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
