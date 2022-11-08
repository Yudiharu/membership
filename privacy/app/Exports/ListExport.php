<?php
 
namespace App\Exports;
 
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Member;

class ListExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function konek()
    {
        $compa2 = auth()->user()->kode_company;
        $compa = substr($compa2,0,2);
        if ($compa == '01'){
            $koneksi = 'mysqldepo';
        }else if ($compa == '02'){
            $koneksi = 'mysqlpbm';
        }else if ($compa == '03'){
            $koneksi = 'mysqlemkl';
        }else if ($compa == '22'){
            $koneksi = 'mysqlskt';
        }else if ($compa == '04'){
            $koneksi = 'mysqlgut';
        }else if ($compa == '05'){
            $koneksi = 'mysql';
        }
        return $koneksi;
    }

   	public function __construct(string $kode_company)
    {
        $this->kode_company = $kode_company;
    }

    public function view(): View
    {   
        return view('/admin/membership/listexcel', [
            'data' => Member::orderBy('id')->get()
        ]);
    }
}