<?php
 
namespace App\Exports;
 
use App\Models\HistoryPremi;
use App\Models\tb_akhir_bulan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class RekapPremiExport implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */

   	public function __construct(string $bulan, string $tahun, string $report, string $jenis)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->report = $report;
        $this->jenis = $jenis;
    }

    public function view(): View
    {   
        if($this->jenis == 'BANK'){
            return view('/admin/laporanpremi/excel', [
                'data' => HistoryPremi::select('nama','no_rekening','nik','type', DB::raw('sum(premi) as total'))->whereMonth('tgl_insentif', $this->bulan)->whereYear('tgl_insentif', $this->tahun)->where('no_rekening', '<>', null)->groupBy('nama','no_rekening','nik','type')->orderBy('nama')->get()
            ]);
        }else{
            return view('/admin/laporanpremi/excel', [
                'data' => HistoryPremi::select('nama','no_rekening','nik','type', DB::raw('sum(premi) as total'))->whereMonth('tgl_insentif', $this->bulan)->whereYear('tgl_insentif', $this->tahun)->where('no_rekening', null)->groupBy('nama','no_rekening','nik','type')->orderBy('nama')->get()
            ]);
        }
    }
}