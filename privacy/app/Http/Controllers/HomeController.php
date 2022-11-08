<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon;
use App\Models\tb_akhir_bulan;
use App\Models\sessions;
use App\Models\MasterLokasi;
use App\Models\Company;
use App\Models\Chat;
use App\User;
use Alert;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $lokasi = auth()->user()->kode_lokasi;
        $company = auth()->user()->kode_company;

        $tgl_jalan = tb_akhir_bulan::where('reopen_status','true')->orwhere('status_periode','Open')->first();
        $tgl_jalan2 = $tgl_jalan->periode;
        $period = Carbon\Carbon::parse($tgl_jalan2)->format('F Y');

        $get_lokasi = MasterLokasi::where('kode_lokasi',auth()->user()->kode_lokasi)->first();
        $nama_lokasi = $get_lokasi->nama_lokasi;
        
        $get_company = Company::where('kode_company',auth()->user()->kode_company)->first();
        $nama_company = $get_company->nama_company;

        $level = auth()->user()->level;
        $nama_user = auth()->user()->username;

        $user_login = User::join('sessions', 'users.id', '=', 'sessions.user_id')
                ->get();
        $leng4 = $user_login->count();

        $user_login2 = User::select('users.name')
                ->where('users.kode_company',auth()->user()->kode_company)
                ->get();

        return view('home',compact('period', 'user_login', 'nama_lokasi', 'level','nama_company','nama_user', 'user_login2','company','leng4'));
    }


    public function savechat(Request $request)
    {
        $from_id = auth()->user()->id;
        $pesan = $request->pesan;
        $tujuan = $request->tujuan;

        $gettujuan_id = User::where('name',$tujuan)->first();
        $to_id = $gettujuan_id->id;

        $chat = [
            'from_id'=>$from_id,
            'to_id'=>$to_id,
            'chat'=>$pesan,
        ];

        $savechat = Chat::create($chat);
        return redirect()->back();
    }

}
