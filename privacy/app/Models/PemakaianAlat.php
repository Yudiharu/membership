<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Memo;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Signature;
use App\Models\Kapal;
use App\Models\Port;
use App\Models\TransaksiSetup;
use App\Models\tb_akhir_bulan;
use Carbon;

class PemakaianAlat extends Model
{
    //
    use AuditableTrait;

    protected $table = 'pemakaian_alat';

    protected $primaryKey = 'no_pemakaian';

    public $incrementing = false;

    protected $fillable = [
        'no_pemakaian',
        'tgl_pemakaian',
        'no_joborder',
        'kode_customer',
        'type_jo',
        'type_kegiatan',
        'status_lokasi',
        'total_timesheet',
        'status',
        'kode_company',
    ];
    
    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

     public function getDestroyUrlAttribute()
    {
        return route('pemakaianalat.destroy', $this->no_pemakaian);
    }

    public function getEditUrlAttribute()
    {
        return route('pemakaianalat.edit',$this->no_pemakaian);
    }

    public function getUpdateUrlAttribute()
    {
        return route('pemakaianalat.update',$this->no_pemakaian);
    }

    public function getShowUrlAttribute()
    {
        return route('pemakaianalat.show',$this->no_pemakaian);
    }

    public function getCetakUrlAttribute()
    {
        return route('pemakaianalat.cetak',$this->no_pemakaian);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->total_timesheet = 0;
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_pemakaian = static::generateKode(request()->tgl_pemakaian);
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }

    public static function generateKode($data)
    {
        $user = Auth()->user()->level;
        
        // $getkode = TransaksiSetup::where('kode_setup','016')->first();
        $kode = 'PSA';

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$kode.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$kode.$get_prefix_3);

        $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_pemakaian', 'desc')->first();

        if ( ! $lastRecort )
            $number = 0;
        else {
            $get_record_prefix = strtoupper(substr($lastRecort->{$primary_key}, 0,$prefix_result_length));
            if ($get_record_prefix == $prefix_result){
                $number = substr($lastRecort->{$primary_key},$prefix_result_length);
            }else {
                $number = 0;
            }
        }

        $result_number = $prefix_result . sprintf('%05d', intval($number) + 1);
        return $result_number ;
    }
}
