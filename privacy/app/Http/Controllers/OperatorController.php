<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Operator;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Coa;
use App\Models\Bank;
use App\Models\Systemsetup;
use Carbon;
use DB;

class OperatorController extends Controller
{
    public function index()
    {
        $create_url = route('operator.create');

        $Coa = Coa::select('coa.kode_coa', DB::raw("concat(coa.account,' - ',coa.ac_description) as coas"))->join('u5611458_gui_general_ledger_laravel.coa_detail','coa.kode_coa','=','u5611458_gui_general_ledger_laravel.coa_detail.kode_coa')->where('u5611458_gui_general_ledger_laravel.coa_detail.kode_company', auth()->user()->kode_company)->pluck('coas','coa.kode_coa');

        $Bank = Bank::pluck('nama_bank','kode_bank');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.operator.index',compact('Bank','create_url','Coa','period', 'nama_lokasi','nama_company'));
        
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Operator::with('bank')->get())->make(true);
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
            $nama_sopir = $request->nama_operator;
            $cek_sopir = Operator::where('nama_operator',$nama_sopir)->first();
            if($cek_sopir == null){
                $nik = Operator::where('nik', $request->nik)->first();
                if ($nik != null) {
                    $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'NIK sudah ada.',
                    ];
                    return response()->json($message);
                }
                Operator::create($request->all());

                // $cekvendor = Vendor::where(DB::raw("LEFT(nama_vendor,6)"), 'like',$request->nis.'%')->first();
                // if ($cekvendor != null) {
                //     $message = [
                //         'success' => false,
                //         'title' => 'Simpan',
                //         'message' => 'Penyimpanan NIS pada sopir gagal karena sudah pernah terdaftar.',
                //     ];
                //     return response()->json($message);
                // }else {
                //     $tabel_baru = [
                //         'type'=>2,
                //         'nama_vendor'=>$request->nis.' - '.$request->nama_sopir,
                //         'nama_vendor_po'=>$request->nis.' - '.$request->nama_sopir,
                //         'alamat'=>$request->alamat,
                //         'telp'=>$request->telp,
                //         'hp'=>$request->hp,
                //         'npwp'=>null,
                //         'nama_kontak'=>$request->nama_sopir,
                //         'kode_coa'=>$request->coa,
                //         'status'=>'Aktif',
                //     ];
                //     $create = Vendor::create($tabel_baru);
                // }

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

    public function edit_operator()
    {
        $kode_sopir = request()->id;
        $data = Operator::find($kode_sopir);
        $output = array(
            'id'=>$data->id,
            'nama_operator'=>$data->nama_operator,
            'kontak_pic'=>$data->kontak_pic,
            'kode_bank'=>$data->kode_bank,
            'alamat'=>$data->alamat,
            'kota'=>$data->kota,
            'kode_pos'=>$data->kode_pos,
            'nik'=>$data->nik,
            'telp'=>$data->telp,
            'hp'=>$data->hp,
            'status_insentif'=>$data->status_insentif,
            'status_tembak'=>$data->status_tembak,
            'no_rekening'=>$data->no_rekening,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_sopir = $request->id;
        Operator::find($request->id)->update($request->all());
       
        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_operator()
    {   
        $kode_sopir = request()->id;
        $sopir = Operator::find(request()->id);
        $sopir->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$sopir->nama_operator.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
