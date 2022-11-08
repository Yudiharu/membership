<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Member;
use App\Models\tb_akhir_bulan;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Exports\ListExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Carbon;
use DB;
use PDF;

class MembershipController extends Controller
{
    public function index()
    {
        $create_url = route('membership.create');
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $Company = Company::pluck('nama_company','kode_company');
        DB::update("UPDATE member SET umur=DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),tanggal_lahir)), '%Y')+0");

        $level = auth()->user()->level;
        return view('admin.membership.index',compact('create_url','period', 'nama_lokasi','nama_company','Company'));
    }

    public function anyData()
    {
        return Datatables::of(Member::orderby('nik','asc'))->make(true);
    }
    
    public function exportexcel(){
        $kode_company = auth()->user()->kode_company;
        $nama = Company::find($kode_company);
        return Excel::download(new ListExport($kode_company), 'List Tenaga Kerja.xlsx');
    }

    public function ttd_buat()
    {
        $konek = self::konek();
        $signature = request()->img;
        $signatureFileName = request()->no.'-dibuat'.'.png';
        $signature = str_replace('data:image/png;base64,', '', $signature);
        $signature = str_replace(' ', '+', $signature);
        $data = base64_decode($signature);
        $member = Member::on($konek)->find(request()->no);
        $member->ttd = $signature;
        $member->save();

        // $cekfile = realpath(dirname(getcwd())).'/gui_finance_laravel/digital/cbo/'.$signatureFileName;
        // if (file_exists($cekfile)) {
        //     unlink($cekfile);
        // }

        // $folder = realpath(dirname(getcwd())).'/gui_finance_laravel/digital/cbo/';
        // $file = $folder.$signatureFileName;
        // file_put_contents($file, $data);

        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'TTD telah disimpan.'
        ];
        return response()->json($message);
    }
    
    public function exportPDF(){
        $request = $_GET['id'];
        $member = Member::find($request);
        
        $get_lokasi = auth()->user()->kode_lokasi;
        $get_company = auth()->user()->kode_company;

        $nama_lokasi = MasterLokasi::find($get_lokasi);
        $nama = $nama_lokasi->nama_lokasi;

        $nama_company = Company::find($get_company);
        $nama2 = $nama_company->nama_company;
        $dt = Carbon\Carbon::now();

        $company = auth()->user()->kode_company;
        
        $pdf = PDF::loadView('/admin/membership/pdf', compact('member','get_lokasi','nama','dt','company','nama2','request'));
        $pdf->setPaper([0, 0, 684, 792], 'potrait');
        return $pdf->stream('Data Tenaga Kerja NIB '.$member->nik.'.pdf');
    }

    public function store(Request $request)
    {
        // $nama = $request->nama;
        // $cek_customer = Member::where('nama',$nama)->first();
        // if($cek_customer != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Simpan',
        //         'message' => 'Nama Tenaga Kerja Sudah Ada',
        //     ];
        //     return response()->json($message);
        // }
        
        $cek_ktp = Member::where('no_ktp', $request->no_ktp)->first();
        if ($cek_ktp != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'KTP ini sudah terdaftar...',
            ];
            return response()->json($message);
        }
        
        $cek_npwp = Member::where('no_npwp', $request->no_npwp)->first();
        if ($cek_npwp != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'NPWP ini sudah terdaftar...',
            ];
            return response()->json($message);
        }
        
        // $cek_kk = Member::where('no_kk', $request->no_kk)->first();
        // if ($cek_kk != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Simpan',
        //         'message' => 'KK ini sudah terdaftar...',
        //     ];
        //     return response()->json($message);
        // }
        
        Member::create($request->all());
        $message = [
            'success' => true,
            'title' => 'Simpan',
            'message' => 'Data telah disimpan.'
        ];
        return response()->json($message);
    }

    public function detail($customer)
    {
        $member = Customer::where('id',$customer)->first();
        $memberdetail = MembershipDetail::where('kode_customer', $member->id)->orderBy('created_at','desc')->get();

        $list_url= route('customer.index');
        
        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');
        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        return view('admin.membershipdetail.index', compact('member','memberdetail','list_url','Mobil','period','nama_lokasi','nama_company','vendor'));
    }

    public function edit_customer()
    {
        $kode_customer = request()->id;
        $data = Member::find($kode_customer);
        $output = array(
            'id'=>$data->id,
            'nama'=>$data->nama,
            'nik'=>$data->nik,
            'tanggal_masuk'=>$data->tanggal_masuk,
            'lokasi_kerja'=>$data->lokasi_kerja,
            'jabatan'=>$data->jabatan,
            'gender'=>$data->gender,
            'alamat'=>$data->alamat,
            'tempat'=>$data->tempat,
            'tanggal_lahir'=>$data->tanggal_lahir,
            'umur'=>$data->umur,
            'agama'=>$data->agama,
            'status'=>$data->status,
            'no_ktp'=>$data->no_ktp,
            'no_npwp'=>$data->no_npwp,
            'no_kk'=>$data->no_kk,
            'gol_darah'=>$data->gol_darah,
            'keterangan'=>$data->keterangan,
            'status_kerja'=>$data->status_kerja,
            'kode_company'=>$data->kode_company,
        );
        return response()->json($output);
    }
    
    public function compressImage($source, $destination, $quality) { 
        // Get image info 
        $imgInfo = getimagesize($source); 
        $mime = $imgInfo['mime']; 
         
        // Create a new image from file 
        switch($mime){ 
            case 'image/jpeg': 
                $image = imagecreatefromjpeg($source); 
                break; 
            case 'image/png': 
                $image = imagecreatefrompng($source); 
                break; 
            case 'image/gif': 
                $image = imagecreatefromgif($source); 
                break; 
            default: 
                $image = imagecreatefromjpeg($source); 
        } 
         
        // Save image 
        imagejpeg($image, $destination, $quality); 
         
        // Return compressed image 
        return $destination; 
    }

    public function uploado()
    {
        $folder = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id;
        if (!file_exists($folder)) {
            mkdir(realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id, 0755);
        }

        $target_dir = "member_img/";
            
        $target_file_ktp = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $namafile_ktp = basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType_ktp = strtolower(pathinfo($target_file_ktp,PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if (file_exists($target_file_ktp)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 3500000) {
            echo "File terlalu besar, maks 3,5mb.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType_ktp != "jpg" && $imageFileType_ktp != "png" && $imageFileType_ktp != "jpeg") {
            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
            $uploadOk = 0;
        }
            
        $cekfile = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id.'/'.'KTP-'.request()->id.'.jpg';
        if (file_exists($cekfile)) {
            unlink($cekfile);
        }

        $dirpath = realpath(dirname(getcwd())).'/gui_membership_system';
        
        if ($this->compressImage($_FILES["fileToUpload"]["tmp_name"], $dirpath.'/member_img/'.request()->id.'/'.'KTP-'.request()->id.'.jpg', 7)) {
            echo "Upload KTP berhasil.";
        }else {
            echo "Sorry, there was an error uploading your file.";
        }
        Storage::disk('ftp')->put(request()->id.'/'.'KTP-'.request()->id.'.jpg', fopen(realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id.'/'.'KTP-'.request()->id.'.jpg', 'r+'));
        // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $dirpath.'/member_img/'.request()->id.'/'.'KTP-'.request()->id.'.jpg')) {
        //     echo "Upload KTP berhasil.";
        // } else {
        //     echo "Sorry, there was an error uploading your file.";
        // }
    }

    public function uploadi()
    {
        $folder = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id;
        if (!file_exists($folder)) {
            mkdir(realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id, 0755);
        }

        $target_dir = "member_img/";

        $target_file_npwp = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
        $namafile_npwp = basename($_FILES["fileToUpload2"]["name"]);
        $uploadOk = 1;
        $imageFileType_npwp = strtolower(pathinfo($target_file_npwp,PATHINFO_EXTENSION));

        $check2 = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
        if($check2 !== false) {
            echo "File is an image - " . $check2["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if (file_exists($target_file_npwp)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload2"]["size"] > 3500000) {
            echo "File terlalu besar, maks 3,5mb.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType_npwp != "jpg" && $imageFileType_npwp != "png" && $imageFileType_npwp != "jpeg") {
            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
            $uploadOk = 0;
        }
        
        $cekfile = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id.'/'.'NPWP-'.request()->id.'.jpg';
        if (file_exists($cekfile)) {
            unlink($cekfile);
        }

        $dirpath = realpath(dirname(getcwd())).'/gui_membership_system';
        if ($this->compressImage($_FILES["fileToUpload2"]["tmp_name"], $dirpath.'/member_img/'.request()->id.'/'.'NPWP-'.request()->id.'.jpg', 7)) {
            echo "Upload NPWP berhasil.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
        Storage::disk('ftp')->put(request()->id.'/'.'NPWP-'.request()->id.'.jpg', fopen(realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id.'/'.'NPWP-'.request()->id.'.jpg', 'r+'));
    }

    public function uploade()
    {
        $folder = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id;
        if (!file_exists($folder)) {
            mkdir(realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id, 0755);
        }

        $target_dir = "member_img/";
        $target_file_kk = $target_dir . basename($_FILES["fileToUpload3"]["name"]);
        $namafile_kk = basename($_FILES["fileToUpload3"]["name"]);
        $uploadOk = 1;
        $imageFileType_kk = strtolower(pathinfo($target_file_kk,PATHINFO_EXTENSION));
        $check3 = getimagesize($_FILES["fileToUpload3"]["tmp_name"]);
        if($check3 !== false) {
            echo "File is an image - " . $check3["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if (file_exists($target_file_kk)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload3"]["size"] > 3500000) {
            echo "File terlalu besar, maks 3,5mb.";
            $uploadOk = 0;
        }

        if($imageFileType_kk != "jpg" && $imageFileType_kk != "png" && $imageFileType_kk != "jpeg") {
            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
            $uploadOk = 0;
        }
        
        $cekfile = realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id.'/'.'KK-'.request()->id.'.jpg';
        if (file_exists($cekfile)) {
            unlink($cekfile);
        }

        $dirpath = realpath(dirname(getcwd())).'/gui_membership_system';
        if ($this->compressImage($_FILES["fileToUpload3"]["tmp_name"], $dirpath.'/member_img/'.request()->id.'/'.'KK-'.request()->id.'.jpg', 15)) {
            echo "Upload KK berhasil.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
        Storage::disk('ftp')->put(request()->id.'/'.'KK-'.request()->id.'.jpg', fopen(realpath(dirname(getcwd())).'/gui_membership_system/member_img/'.request()->id.'/'.'KK-'.request()->id.'.jpg', 'r+'));
    }

    public function updateAjax(Request $request)
    {
        $kode_customer = $request->id;
        
        $cek_ktp = Member::where('no_ktp', $request->no_ktp)->where('no_ktp','<>', null)->where('id', '<>', $request->id)->first();
        if ($cek_ktp != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'KTP ini sudah terdaftar...',
            ];
            return response()->json($message);
        }
        
        $cek_npwp = Member::where('no_npwp', $request->no_npwp)->where('no_npwp','<>', null)->where('id', '<>', $request->id)->first();
        if ($cek_npwp != null){
            $message = [
                'success' => false,
                'title' => 'Simpan',
                'message' => 'NPWP ini sudah terdaftar...',
            ];
            return response()->json($message);
        }
        
        // $cek_kk = Member::where('no_kk', $request->no_kk)->where('no_kk','<>', null)->where('id', '<>', $request->id)->first();
        // if ($cek_kk != null){
        //     $message = [
        //         'success' => false,
        //         'title' => 'Simpan',
        //         'message' => 'KK ini sudah terdaftar...',
        //     ];
        //     return response()->json($message);
        // }
        
        $datas= $request->all();
        $update = Member::find($request->id)->update($datas);

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data telah di Update.'
        ];
        return response()->json($message);
    }

    public function hapus_customer()
    {   
        $kode_customer = request()->id;
        $customer = Member::find(request()->id);
        $customer->delete();

        $message = [
            'success' => true,
            'title' => 'Update',
            'message' => 'Data ['.$customer->nama.'] telah dihapus.'
        ];
        return response()->json($message);
    }

}
