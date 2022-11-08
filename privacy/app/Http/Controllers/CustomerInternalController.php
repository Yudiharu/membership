<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\CustomerInternal;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Joborder;
use App\Models\Coa;
use Carbon;
use DB;

class CustomerInternalController extends Controller
{
    public function index()
    {
        $create_url = route('customerinternal.create');

        $Coa = Coa::select('coa.kode_coa', DB::raw("concat(coa.account,' - ',coa.ac_description) as coa"))->join('u5611458_gui_general_ledger_laravel.coa_detail','u5611458_gui_general_ledger_laravel.coa.kode_coa','=','u5611458_gui_general_ledger_laravel.coa_detail.kode_coa')->where('u5611458_gui_general_ledger_laravel.coa_detail.kode_company', auth()->user()->kode_company)->where('position','DETAIL')->pluck('coa','coa.kode_coa');

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        return view('admin.customerinternal.index',compact('create_url','period', 'nama_lokasi','nama_company','Coa'));
        
    }

    public function anyData()
    {
        return Datatables::of(CustomerInternal::with('coa')->orderby('nama_customer','asc'))->make(true);
    }

    public function store(Request $request)
    {
            $nama_customer = $request->nama_customer;
            $cek_customer = CustomerInternal::where('nama_customer',$nama_customer)->first();
            if($cek_customer == null){
                    CustomerInternal::create($request->all());

                    if($request->telp == null || $request->telp == 0){
                        $telp = '(    )-';
                    }else{
                        $telp = $request->telp;
                    }

                    if($request->hp == null || $request->hp == 0){
                        $hp = '(    )-';
                    }else{
                        $hp = $request->hp;
                    }

                    if($request->fax == null || $request->fax == 0){
                        $fax = '(    )-';
                    }else{
                        $fax = $request->fax;
                    }

                    if($request->contact_pic == null || $request->contact_pic == 0){
                        $contact_pic = '(    )-';
                    }else{
                        $contact_pic = $request->contact_pic;
                    }

                    if($request->npwp == null || $request->npwp == 0){
                        $npwp = '.   .   . -   .';
                    }else{
                        $npwp = $request->npwp;
                    }

                    $update_info = [
                        'telp'=>$telp,
                        'hp'=>$hp,
                        'fax'=>$fax,
                        'contact_pic'=>$contact_pic,
                        'npwp'=>$npwp,
                    ];

                    $update_customer = CustomerInternal::where('nama_customer', $nama_customer)->update($update_info);
                    
                    $konversi_simbol = CustomerInternal::where('nama_customer', 'LIKE', '%&%')->update(['nama_customer' => DB::raw("REPLACE(nama_customer,  '&', 'DAN')")]);

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
                        'message' => 'Nama customer Sudah Ada',
                        ];
                return response()->json($message);
            }  
    }

    public function edit_customer()
    {
        $kode_customer = request()->id;
        $data = CustomerInternal::find($kode_customer);
        $output = array(
            'kode_customer'=>$data->id,
            'nama_customer'=>$data->nama_customer,
            'nama_customer_po'=>$data->nama_customer_po,
            'kode_coa'=>$data->kode_coa,
            'alamat'=>$data->alamat,
            'alamat2'=>$data->alamat2,
            'alamat3'=>$data->alamat3,
            'alamat4'=>$data->alamat4,
            'kota'=>$data->kota,
            'kode_pos'=>$data->kode_pos,
            'telp'=>$data->telp,
            'fax'=>$data->fax,
            'hp'=>$data->hp,
            'nama_kontak'=>$data->nama_kontak,
            'contact_pic'=>$data->contact_pic,
            'npwp'=>$data->npwp,
            'type_company'=>$data->type_company,
            'no_kode_pajak'=>$data->no_kode_pajak,
            'status'=>$data->status,
        );
        return response()->json($output);
    }

    public function updateAjax(Request $request)
    {
        $kode_customer = $request->kode_customer;
        $cek_transaksi = Joborder::where('kode_customer',$kode_customer)->first();

        if ($cek_transaksi == null){
                $datas= $request->all();
                if($request->numbertelp == 0){
                    $datas['telp'] = '(    )-';
                }else{
                    $datas['telp'] = $request->telp;
                }
                if($request->number1 == 0){
                    $datas['hp'] = '(    )-';
                }else{
                    $datas['hp'] = $request->hp;
                }
                if($request->numberfax == 0){
                    $datas['fax'] = '(    )-';
                }else{
                    $datas['fax'] = $request->fax;
                }
                if($request->numberpic == 0){
                    $datas['contact_pic'] = '(    )-';
                }else{
                    $datas['contact_pic'] = $request->contact_pic;
                }
                if($request->number == 0){
                    $datas['npwp'] = '.   .   . -   .';
                }else{
                    $datas['npwp'] = $request->npwp;
                }
                   
                $update = CustomerInternal::find($request->kode_customer)->update($datas);
       
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
                'message' => 'Data ['.$request->nama_customer.'] dipakai dalam transaksi.'
            ];
            return response()->json($message);
        }
    }

    public function hapus_customer()
    {   
        $kode_customer = request()->id;
        $customer = CustomerInternal::find(request()->id);
        $cek_transaksi = Joborder::where('kode_customer',$kode_customer)->first();

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
