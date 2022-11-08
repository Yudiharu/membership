<?php
 
namespace App\Exports;
 
use App\Models\tb_item_bulanan;
use App\Models\Joborder;
use App\Models\JoborderDetail;
use App\Models\JobrequestDetail;
use App\Models\tb_akhir_bulan;
use App\Models\Spb;
use App\Models\Trucking;
use App\Models\TruckingDetail;
use App\Models\Truckingnon;
use App\Models\TruckingnonDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekaptglExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

   	public function __construct(string $tglawal, string $tglakhir, string $type)
    {
        $this->tglawal = $tglawal;
        $this->tglakhir = $tglakhir;
        $this->type = $type;
    }

    public function view(): View
    {   
        if($this->type == 'Container'){
            $type = $this->type;
            return view('/admin/laporanrekap_pertgl/excel', [
                'data' => Spb::select('spb.*','trucking_detail.*','trucking.*','vendor.nama_vendor')->with('mobil','sopir','pemilik','gudangdetail','customer')->join('trucking_detail','trucking_detail.no_spb','=','spb.no_spb')->join('trucking','trucking.no_trucking','=','trucking_detail.no_trucking')->join('u5611458_db_pusat.vendor','spb.kode_pemilik','=','u5611458_db_pusat.vendor.id')->whereBetween('spb.tgl_kembali',array($this->tglawal,$this->tglakhir))->get(),
                'type'=>$this->type
            ]);
        }else{
            $type = $this->type;
            return view('/admin/laporanrekap_pertgl/excel', [
                'data2'=> TruckingnonDetail::select('truckingnoncontainer_detail.*','trucking_noncontainer.*','vendor.nama_vendor')->with('mobil','sopir','customer')->join('trucking_noncontainer','trucking_noncontainer.no_truckingnon','=','truckingnoncontainer_detail.no_truckingnon')->join('u5611458_db_pusat.vendor','truckingnoncontainer_detail.kode_pemilik','=','u5611458_db_pusat.vendor.id')->whereBetween('truckingnoncontainer_detail.tanggal_kembali',array($this->tglawal,$this->tglakhir))->get(),
                'type'=>$this->type
            ]);
        }
    }
}