<?php
 
namespace App\Exports;
 
use App\Models\tb_item_bulanan;
use App\Models\Joborder;
use App\Models\JoborderDetail;
use App\Models\JobrequestDetail;
use App\Models\tb_akhir_bulan;
use App\Models\Trucking;
use App\Models\TruckingDetail;
use App\Models\Truckingnon;
use App\Models\TruckingnonDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Rekap_percontainerExport implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */

   	public function __construct(string $kode_container)
    {
        $this->kode_container = $kode_container;
    }

    public function view(): View
    {   
            $truck = TruckingDetail::where('kode_container',$this->kode_container)->first();
                return view('/admin/laporanrekap_percontainer/excel', [
                'data' => TruckingDetail::select('trucking_detail.*','spb.tgl_spb','spb.kode_mobil','spb.kode_sopir','jobrequest_detail.status_muatan','jobrequest_detail.tujuan')->with('mobil','sopir')->join('spb','spb.no_spb','=','trucking_detail.no_spb')->join('jobrequest_detail','jobrequest_detail.kode_container','=','trucking_detail.kode_container')->where('trucking_detail.kode_container',$this->kode_container)->get(),
                ]);
    }
}