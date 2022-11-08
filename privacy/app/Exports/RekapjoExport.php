<?php
 
namespace App\Exports;
 
use App\Models\tb_item_bulanan;
use App\Models\Joborder;
use App\Models\JoborderDetail;
use App\Models\JobrequestDetail;
use App\Models\tb_akhir_bulan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapjoExport implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */

   	public function __construct(string $dari, string $sampai, string $report, string $jenis)
    {
        $this->dari = $dari;
        $this->sampai = $sampai;
        $this->report = $report;
        $this->jenis = $jenis;
    }

    public function view(): View
    {   
        if($this->jenis == 'SEMUA'){
            return view('/admin/laporanrekapjo/excel', [
                'data' => Joborder::with('customer1')->whereBetween('tgl_joborder', array($this->dari, $this->sampai))->get()
            ]);
        }else{
            return view('/admin/laporanrekapjo/excel', [
                'data' => Joborder::with('customer1')->where('type_kegiatan',$this->jenis)->whereBetween('tgl_joborder', array($this->dari, $this->sampai))->get()
            ]);
        }
    }
}