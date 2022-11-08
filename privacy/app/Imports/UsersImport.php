<?php
  
namespace App\Imports;
  
use App\Models\OpnameDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Alert;
  
class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new OpnameDetail([
            'no_opname' => $row[0],
            'kode_produk' => $row[1],
            'partnumber' => $row[2],
            'hpp' => $row[3], 
            'stok' => $row[4],
            'qty_checker1' => $row[5], 
            'qty_checker2' => $row[6], 
            'qty_checker3' => $row[7],  
        ],
        alert()->success('Input Data Excel','BERHASIL!')->persistent('Close'));

        
    }
}