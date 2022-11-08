<?php
 
namespace App\Exports;
 
use App\Models\Member;
use App\Models\tb_akhir_bulan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class RekapMemberExport implements FromView
{
    
    /**
    * @return \Illuminate\Support\Collection
    */

   	public function __construct(string $mandor)
    {
        $this->mandor = $mandor;
    }

    public function view(): View
    {   
        return view('/admin/laporanmember/excel', [
                'data' => Member::where(DB::raw("LEFT(nik,1)"), 'like', substr($this->mandor, 0,1).'%')->where('status','Aktif')->get()
        ]);
    }
}