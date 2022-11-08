<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Vendor;
use App\Models\VendorCounter;
use App\Models\Pembelian;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Coa;
use App\Models\Systemsetup;
use Carbon;
use DB;

class VendorController extends Controller
{
    public function konek()
    {
        $compa = auth()->user()->kode_company;
        if ($compa == '01'){
            $koneksi = 'mysqldepo';
        }else if ($compa == '02'){
            $koneksi = 'mysqlpbm';
        }else if ($compa == '0401'){
            $koneksi = 'mysqlgutjkt';
        }else if ($compa == '03'){
            $koneksi = 'mysql';
        }else if ($compa == '04'){
            $koneksi = 'mysqlgut';
        }else if ($compa == '05'){
            $koneksi = 'mysqlsub';
        }else if ($compa == '06'){
            $koneksi = 'mysqlinf';
        }
        return $koneksi;
    }
    
    public function index()
    {
        $konek = self::konek();
        $create_url = route('vendor.create');

        $Coa = Coa::select('coa.kode_coa', DB::raw("concat(coa.account,' - ',coa.ac_description) as coas"))->join('u5611458_gui_general_ledger_laravel.coa_detail','coa.kode_coa','=','u5611458_gui_general_ledger_laravel.coa_detail.kode_coa')->where('u5611458_gui_general_ledger_laravel.coa_detail.kode_company', auth()->user()->kode_company)->pluck('coas','coa.kode_coa');

        $tgl_jalan = tb_akhir_bulan::on($konek)->where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.vendor.index',compact('create_url','period', 'nama_lokasi','nama_company','Coa'));
        
    }

    public function anyData()
    {
        $level = auth()->user()->level;
            return Datatables::of(Vendor::with('coa')->orderby('nama_vendor','asc'))->make(true);
    }

    public function getcoa()
    {
        //Coa Hutang Usaha
        $get_setup = Systemsetup::find('18');
        $kode_coa = $get_setup->kode_setup;
        $output = [
            'kode_coa'=>$kode_coa,
        ];
        return response()->json($output);
    }

    public function store(Request $request)
    {
        $datas= $request->all();
        if($request->type == '1'){
            $datas['kode_coa'] = $request->kode_coa;

            $nama_vendor = $request->nama_vendor;
            $cek_vendor = Vendor::where('nama_vendor',$nama_vendor)->first();
            if($cek_vendor == null){
                Vendor::create($datas);
                //KONVERSI DIGUNAKAN UNTUK KONVERSI SIMBOL '&' AGAR TIDAK EROR SAAT TARIL EXCEL
                // $konversi_simbol = Vendor::where('nama_vendor', 'LIKE', '%&%')->update(['nama_vendor' => DB::raw("REPLACE(nama_vendor,  '&', 'DAN')")]);

                // $konversi_simbol2 = Vendor::where('nama_vendor_po', 'LIKE', '%&%')->update(['nama_vendor_po' => DB::raw("REPLACE(nama_vendor_po,  '&', 'DAN')")]);

                $konversi_simbol3 = Vendor::where('alamat', 'LIKE', '%&%')->update(['alamat' => DB::raw("REPLACE(alamat,  '&', 'DAN')")]);

                // $konversi_simbol4 = Vendor::where('nama_kontak', 'LIKE', '%&%')->update(['nama_kontak' => DB::raw("REPLACE(nama_kontak,  '&', 'DAN')")]);
            }else{
                $message = [
                    'success' => false,
                    'title' => 'Simpan',
                    'message' => 'Nama Vendor Sudah Ada',
                ];
                return response()->json($message);
            }  
        }else{
            $nama_vendor = $request->nama_vendor;
            $cek_vendor = Vendor::where('nama_vendor',$nama_vendor)->first();
            if($cek_vendor == null){
                Vendor::create($datas);
                //KONVERSI DIGUNAKAN UNTUK KONVERSI SIMBOL '&' AGAR TIDAK EROR SAAT TARIL EXCEL
                // $konversi_simbol = Vendor::where('nama_vendor', 'LIKE', '%&%')->update(['nama_vendor' => DB::raw("REPLACE(nama_vendor,  '&', 'DAN')")]);

                // $konversi_simbol2 = Vendor::where('nama_vendor_po', 'LIKE', '%&%')->update(['nama_vendor_po' => DB::raw("REPLACE(nama_vendor_po,  '&', 'DAN')")]);

                $konversi_simbol3 = Vendor::where('alamat', 'LIKE', '%&%')->update(['alamat' => DB::raw("REPLACE(alamat,  '&', 'DAN')")]);

                // $konversi_simbol4 = Vendor::where('nama_kontak', 'LIKE', '%&%')->update(['nama_kontak' => DB::raw("REPLACE(nama_kontak,  '&', 'DAN')")]);
            }else{
                $message = [
                    'success' => false,
                    'title' => 'Simpan',
                    'message' => 'Nama Vendor Sudah Ada',
                ];
                return response()->json($message);
            }
        }
        $vendor = Vendor::orderBy('created_at', 'desc')->first();
        $compan = Company::where('status', 'Aktif')->get();

        foreach ($compan as $row) {
            $isicoa = [
                'kode_vendor'=>$vendor->id,
                'kode_coa'=>$vendor->kode_coa,
                'kode_company'=>$row->kode_company,
            ];
            VendorCoa::create($isicoa);
        }

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function edit_vendor()
    {
        $id = request()->id;
        $data = Vendor::find($id);
        $output = array(
            'id'=>$data->id,
            'type'=>$data->type,
            'nama_vendor'=>$data->nama_vendor,
            'nama_vendor_po'=>$data->nama_vendor_po,
            'alamat'=>$data->alamat,
            'telp'=>$data->telp,
            'hp'=>$data->hp,
            'norek_vendor'=>$data->norek_vendor,
            'nama_kontak'=>$data->nama_kontak,
            'npwp'=>$data->npwp,
            'kode_coa'=>$data->kode_coa,
            'status'=>$data->status,
        );
        return response()->json($output);
    }


    public function updateAjax(Request $request)
    {
        $id = $request->id;
        $cek_vendor2 = Pembelian::where('kode_vendor',$id)->first();

        $datas= $request->all();
        if($request->type == '1'){
            $datas['kode_coa'] = $request->kode_coa;
            // if ($cek_vendor2 == null){
                Vendor::find($request->id)->update($datas);
           
                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data telah di Update.'
                ];
                return response()->json($message);
            // }
            // else{
            //     $message = [
            //         'success' => false,
            //         'title' => 'Update',
            //         'message' => 'Data ['.$request->nama_vendor.'] dipakai dalam transaksi.'
            //     ];
            //     return response()->json($message);
            // }
        }else{
            Vendor::find($request->id)->update($datas);
           
            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data telah di Update.'
            ];
            return response()->json($message);
        }
    }

    public function hapus_vendor()
    {   
        $id = request()->id;
        $vendor = Vendor::find(request()->id);
        $cek_vendor2 = Pembelian::where('kode_vendor',$id)->first();

        if ($cek_vendor2 == null){
            
            // $prefix = strtoupper($kode_vendor[0]);
            // $produk_index = VendorCounter::where('index', $prefix)->first();
            // $jumlah_final = $produk_index->jumlah - 1;
            // $tabel_baru2 = [
            //     'jumlah'=>$jumlah_final,
            // ];
            // $update = VendorCounter::where('index', $prefix)->update($tabel_baru2);
            
            $vendor->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$vendor->nama_vendor.'] telah dihapus.'
            ];
            return response()->json($message);
        } else {
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$vendor->nama_vendor.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
        
    }
}
