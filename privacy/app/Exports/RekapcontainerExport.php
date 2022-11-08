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

class RekapcontainerExport implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */

   	public function __construct(string $nojo, string $cekdetail)
    {
        $this->nojo = $nojo;
        $this->cekdetail = $cekdetail;
    }

    public function view(): View
    {   
        if($this->cekdetail != 'null'){
            $cekdetail = $this->cekdetail;
            $truck = Trucking::where('no_joborder',$this->nojo)->first();
                return view('/admin/laporanrekapcontainer/excel', [
                'data' => TruckingDetail::select('trucking_detail.*','spb.*','trucking.*','vendor.nama_vendor')->with('mobil','gudangdetail','sopir')->join('spb','spb.no_spb','=','trucking_detail.no_spb')->join('trucking','trucking.no_trucking','=','trucking_detail.no_trucking')->join('u5611458_db_pusat.vendor','spb.kode_pemilik','=','u5611458_db_pusat.vendor.id')->where('trucking_detail.no_trucking',$truck->no_trucking)->get(),
                'cekdetail'=>$this->cekdetail
                ]);
        }else{
            $cekdetail = $this->cekdetail;
            $trucknon = Truckingnon::where('no_joborder',$this->nojo)->first();
                return view('/admin/laporanrekapcontainer/excel', [
                'data' => TruckingnonDetail::select('truckingnoncontainer_detail.*','trucking_noncontainer.*','vendor.nama_vendor')->with('mobil','sopir')->join('trucking_noncontainer','trucking_noncontainer.no_truckingnon','=','truckingnoncontainer_detail.no_truckingnon')->join('u5611458_db_pusat.vendor','truckingnoncontainer_detail.kode_pemilik','=','u5611458_db_pusat.vendor.id')->where('truckingnoncontainer_detail.no_truckingnon',$trucknon->no_truckingnon)->get(),
                'cekdetail'=>$this->cekdetail
                ]);
        }
    }
}