<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Gudang;
use App\Models\GudangDetail;
use App\Models\TarifTrucking;
use App\Models\Customer;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use Carbon;
use DB;

class GudangController extends Controller
{
    public function index()
    {
        $create_url = route('gudang.create');
        $Customer = Customer::where('status','Aktif')->pluck('nama_customer','id');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.gudang.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer'));
        
    }

    public function getkode(){
        $get_inv = Gudang::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kode_customer = $rowdata->kode_customer;
            $kode_shipper = $rowdata->kode_shipper;

            $data[] = array(
                'kode_customer'=>$kode_customer,
                'kode_shipper'=>$kode_shipper,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_cust = Customer::where('kode_customer', $data[$i]['kode_customer'])->first();
            $cek_ship = Customer::where('kode_customer', $data[$i]['kode_shipper'])->first();

            if($cek_cust != null){
                $id_cust = $cek_cust->id;

                $tabel_baru_cust = [
                    'kode_customer'=>$id_cust,
                ];
                $update_cust = Gudang::where('kode_customer', $data[$i]['kode_customer'])->update($tabel_baru_cust);   
            }
            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kode_shipper'=>$id_ship,
                ];
                $update_ship = Gudang::where('kode_shipper', $data[$i]['kode_shipper'])->update($tabel_baru_ship);
            }
        }



        $get_inv = GudangDetail::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kode_shipper = $rowdata->kode_shipper;

            $data[] = array(
                'kode_shipper'=>$kode_shipper,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_ship = Customer::where('kode_customer', $data[$i]['kode_shipper'])->first();

            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kode_shipper'=>$id_ship,
                ];
                $update_ship = GudangDetail::where('kode_shipper', $data[$i]['kode_shipper'])->update($tabel_baru_ship);
            }
        }



        $get_inv = TarifTrucking::get();
        $leng = count($get_inv);

        $data = array();

        foreach ($get_inv as $rowdata){
            $kode_shipper = $rowdata->kode_shipper;
            $kode_gudang = $rowdata->kode_gudang;

            $data[] = array(
                'kode_shipper'=>$kode_shipper,
                'kode_gudang'=>$kode_gudang,
            );
        }

        for ($i = 0; $i < $leng; $i++) { 
            $cek_ship = Customer::where('kode_customer', $data[$i]['kode_shipper'])->first();

            if($cek_ship != null){
                $id_ship = $cek_ship->id;

                $tabel_baru_ship = [
                    'kode_shipper'=>$id_ship,
                ];
                $update_ship = TarifTrucking::where('kode_shipper', $data[$i]['kode_shipper'])->update($tabel_baru_ship);
            }



            $cek_gudang = GudangDetail::where('kode_gudang', $data[$i]['kode_gudang'])->first();

            if($cek_gudang != null){
                $id_gudang = $cek_gudang->id;

                $tabel_baru_ship = [
                    'kode_gudang'=>$id_gudang,
                ];
                $update_ship = TarifTrucking::where('kode_gudang', $data[$i]['kode_gudang'])->update($tabel_baru_ship);
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
            return Datatables::of(Gudang::with('customer')->orderBy('created_at','desc'))->make(true);
    }

    public function detail($gudang)
    {
        $gudang = Gudang::find($gudang);
        $kode_shipper = $gudang->kode_shipper;

        $data = Gudang::find($kode_shipper);
                    
        $gudangdetail = GudangDetail::with('customer')->where('kode_shipper', $gudang->kode_shipper)->orderBy('created_at','desc')->get();

        $list_url= route('gudang.index');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.gudangdetail.index', compact('gudang','gudangdetail','list_url','period','nama_lokasi','nama_company'));
    }

    public function Showdetail()
    {
        $total_qty = 0;
        $total_qty_received = 0;
        $total_harga = 0;
        $grand_total = 0;

        $dt = Carbon\Carbon::now();
        $date_now = Carbon\Carbon::parse($dt)->format('Y-m-d');

        $gudangdetail= GudangDetail::where('kode_shipper',request()->id)->orderBy('gudang_detail.created_at', 'desc')->get();

        $gudang = Gudang::where('kode_shipper',request()->id)->first();

        $output = array();

        foreach($gudangdetail as $row)
        {
            $kode_shipper = $row->kode_shipper;
            $kode_gudang = $row->kode_gudang;
            $nama_gudang = $row->nama_gudang;
            
            $output[] = array(
                'kode_shipper'=>$kode_shipper,
                'kode_gudang'=>$kode_gudang,
                'nama_gudang'=>$nama_gudang,
            );
        }

        return response()->json($output);
    }

    public function store(Request $request)
    {
            $kode_customer = $request->kode_customer;
            $cek_gudang = Gudang::where('kode_customer',$kode_customer)->first();
            if($cek_gudang == null){
                $validator = $request->validate([
                    'kode_customer'=> 'required',
                  ]);

                try {
                    Gudang::create($request->all());
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
            else{
                $message = [
                        'success' => false,
                        'title' => 'Simpan',
                        'message' => 'Shipper Sudah Ada',
                        ];
                return response()->json($message);
            }  
    }

    public function edit_gudang()
    {
        $kode_shipper = request()->id;
        $data = Gudang::find($kode_shipper);
        $output = array(
            'kode_shipper'=>$data->kode_shipper,
            'kode_customer'=>$data->kode_customer,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_shipper = $request->kode_shipper;
        $request->validate([
            'kode_shipper'=>'required',
            'kode_customer'=> 'required',
        ]);

        $cek_nama = Gudang::where('kode_customer',$request->kode_customer)->first();
        if($cek_nama == null){
            $kode_customer = $request->kode_customer;
            $cek_kode= substr($kode_shipper,0,1);
            $cek_nama = substr($kode_customer,0,1);
            if($cek_kode == $cek_nama){
                Gudang::find($request->kode_shipper)->update($request->all());
           
                $message = [
                    'success' => true,
                    'title' => 'Update',
                    'message' => 'Data telah di Update.'
                ];
                return response()->json($message);
            }else{
                $message = [
                    'success' => false,
                    'title' => 'Update',
                    'message' => 'Huruf awal gudang ['.$cek_kode.'] tidak dapat diubah, karena kode shipper sudah terbentuk.'
                ];
                return response()->json($message);
            }
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Nama shipper sudah ada.'
            ];
            return response()->json($message);
        }
    }

    public function hapus_gudang()
    {   
        $kode_shipper = request()->id;
        $gudang = Gudang::find(request()->id);

            $gudang->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$gudang->kode_shipper.'] telah dihapus.'
            ];
            return response()->json($message);
    }

}
