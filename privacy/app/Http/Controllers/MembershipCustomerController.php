<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Customer;
use App\Models\MembershipCustomer;
use App\Models\MembershipDetail;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Joborder;
use Carbon;
use DB;

class MembershipCustomerController extends Controller
{
    public function index()
    {
        $create_url = route('membershipcustomer.create');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;

        $Customer = Customer::pluck('nama_customer','id');
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.membershipcustomer.index',compact('create_url','period', 'nama_lokasi','nama_company','Customer'));
    }

    public function anyData()
    {
        return Datatables::of(MembershipCustomer::with('customer')->orderby('kode_customer','asc'))->make(true);
    }

    public function getDatabyID(){
        $customer = Customer::find(request()->id);
        return Datatables::of(MembershipDetail::where('kode_customer',request()->id)->where('jenis_harga',$customer->jenis_harga)->orderBy('created_at','asc'))->make(true);
    }

    public function store(Request $request)
    {
        $nama_customer = $request->kode_customer;
        $cek_customer = MembershipCustomer::where('kode_customer',$nama_customer)->first();
        if($cek_customer == null){
            MembershipCustomer::create($request->all());
            // $konversi_simbol7 = Customer::where('nama_kontak', 'LIKE', '%&%')->update(['nama_kontak' => DB::raw("REPLACE(nama_kontak,  '&', 'DAN')")]);
            $message = [
                'success' => true,
                'title' => 'Simpan',
                'message' => 'Data telah disimpan.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'Nama customer Sudah Ada',
            ];
            return response()->json($message);
        }  
    }

    public function detail($customer)
    {
        $member = MembershipCustomer::with('customer')->where('kode_customer',$customer)->first();
                    
        $memberdetail = MembershipDetail::where('kode_customer', $member->kode_customer)->orderBy('created_at','desc')->get();

        $list_url= route('membershipcustomer.index');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.membershipdetail.index', compact('member','memberdetail','list_url','Mobil','period','nama_lokasi','nama_company','vendor'));
    }

    public function store_detail(Request $request)
    {
        $kode_customer = $request->kode_customer;

        MembershipDetail::create($request->all());

        // $update_customer = Customer::where('id',$request->kode_customer)->first();
        // $update_customer->tipe = 'Asosiasi/Member';
        // $update_customer->save();
        
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function edit_detail()
    {
        $kode_customer = request()->id;
        $data = MembershipDetail::find($kode_customer);
        $output = array(
            'id'=>$data->id,
            'id_header'=>$data->id_header,
            'kode_customer'=>$data->kode_customer,
            'tgl_aktif_alfi'=>$data->tgl_aktif_alfi,
            'tgl_akhir_alfi'=>$data->tgl_akhir_alfi,
            'tgl_aktif_apbmi'=>$data->tgl_aktif_apbmi,
            'tgl_akhir_apbmi'=>$data->tgl_akhir_apbmi,
        );
        return response()->json($output);
    }

    public function hapus_detail()
    {
        $hapus = MembershipDetail::find(request()->id);
        $kode = $hapus->kode_customer;

        $hapus->delete();

        // $cekcust = MembershipDetail::where('kode_customer', $kode)->first();
        // if ($cekcust == null) {
        //     $cust = Customer::find($kode);
        //     $cust->tipe = 'Umum';
        //     $cust->save();
        // }

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

    public function updateAjax(Request $request)
    {
        $kode_customer = $request->kode_customer;
        $cek_transaksi = Joborder::where('kode_customer',$kode_customer)->first();
    }

    public function updateAjax_detail(Request $request)
    {
        $memberdetail = MembershipDetail::find($request->id);
        
        if ($request->tgl_aktif_alfi == null) {
            $request->tgl_akhir_alfi = null;
        }

        if ($request->tgl_aktif_apbmi == null) {
            $request->tgl_akhir_apbmi = null;
        }

        $memberdetail->update($request->all());
        $message = [
            'success' => true,
        ];
        return response()->json($message);
    }

    public function hapus_customer()
    {   
        $kode_customer = request()->id;
        $customer = MembershipCustomer::find(request()->id);
        $cek_transaksi = MembershipDetail::where('kode_customer',$kode_customer)->first();
        if ($cek_transaksi == null){
            $customer->delete();

            $message = [
                'success' => true,
                'title' => 'Update',
                'message' => 'Data ['.$customer->nama_customer.'] telah dihapus.'
            ];
            return response()->json($message);
        }else{
            $message = [
                'success' => false,
                'title' => 'Update',
                'message' => 'Data ['.$customer->nama_customer.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }

}
