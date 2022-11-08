<?php
 
namespace App\Exports;
 
use App\Models\tb_item_bulanan;
use App\Models\Joborder;
use App\Models\JoborderDetail;
use App\Models\JobrequestDetail;
use App\Models\tb_akhir_bulan;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapbyrExport implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */

   	public function __construct(string $dari, string $sampai, string $report, string $stat, string $kode_pemilik)
    {
        $this->dari = $dari;
        $this->sampai = $sampai;
        $this->report = $report;
        $this->stat = $stat;
        $this->kode_pemilik = $kode_pemilik;
    }

    public function view(): View
    {   
        if($this->kode_pemilik == 'SEMUA'){
            if ($this->stat == 'SEMUA'){
                return view('/admin/laporanpembayaran/excel', [
                'data' => PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.no_joborder','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->whereBetween('tanggal_pembayaran',array($this->dari,$this->sampai))->get(),
                'kode_pemilik' => $this->kode_pemilik,
                'stat' => $this->stat ]);
            }else {
                return view('/admin/laporanpembayaran/excel', [
                'data' => PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.no_joborder','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->whereBetween('tanggal_pembayaran',array($this->dari,$this->sampai))->where('status', $this->stat)->get(),
                'kode_pemilik' => $this->kode_pemilik,
                'stat' => $this->stat ]);
            }
        }else{
            if ($this->stat == 'SEMUA'){
                return view('/admin/laporanpembayaran/excel', [
                'data' => PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.no_joborder','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->where('pembayaran_pemilik.kode_pemilik',$this->kode_pemilik)->whereBetween('tanggal_pembayaran',array($this->dari,$this->sampai))->get(),
                'kode_pemilik' => $this->kode_pemilik,
                'stat' => $this->stat ]);
            }else {
                return view('/admin/laporanpembayaran/excel', [
                'data' => PembayaranDetail::with('mobil','gudangdetail')->join('pembayaran_pemilik','pembayaranpemilik_detail.no_pembayaran','=','pembayaran_pemilik.no_pembayaran')->select('pembayaran_pemilik.no_pembayaran','pembayaran_pemilik.tanggal_pembayaran','pembayaranpemilik_detail.no_joborder','pembayaranpemilik_detail.*','pembayaran_pemilik.status','pembayaran_pemilik.kode_pemilik')->where('pembayaran_pemilik.kode_pemilik',$this->kode_pemilik)->whereBetween('tanggal_pembayaran',array($this->dari,$this->sampai))->where('status', $this->stat)->get(),
                'kode_pemilik' => $this->kode_pemilik,
                'stat' => $this->stat ]);
            }
        }
    }
}