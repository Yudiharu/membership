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

class Insentifhelper extends Model
{
    //
    use AuditableTrait;

    protected $table = 'insentif_helper';

    protected $primaryKey = 'no_insentif';

    public $incrementing = false;

    protected $fillable = [
        'no_insentif',
        'tgl_insentif',
        'type_helper',
        'kode_helper',
        'tgl_pakai_dari',
        'tgl_pakai_sampai',
        'keterangan',
        'total_dalamkota',
        'total_luarkota',
        'total_libur',
        'status',
        'kode_company',
    ];
    
    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

    public function Helper()
    {
        return $this->belongsTo(Helper::class,'kode_helper');
    }

     public function getDestroyUrlAttribute()
    {
        return route('insentifhelper.destroy', $this->no_insentif);
    }

    public function getEditUrlAttribute()
    {
        return route('insentifhelper.edit',$this->no_insentif);
    }

    public function getUpdateUrlAttribute()
    {
        return route('insentifhelper.update',$this->no_insentif);
    }

    public function getShowUrlAttribute()
    {
        return route('insentifhelper.show',$this->no_insentif);
    }

    public function getCetakUrlAttribute()
    {
        return route('insentifhelper.cetak',$this->no_insentif);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->total_dalamkota = 0;
            $query->total_luarkota = 0;
            $query->total_libur = 0;
            $query->total_premi_dalamkota = 0;
            $query->total_premi_luarkota = 0;
            $query->total_premi_libur = 0;
            $query->gt_insentif = 0;
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_insentif = static::generateKode(request()->tgl_insentif);
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
        $kode = 'IHL';

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$kode.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$kode.$get_prefix_3);

        $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_insentif', 'desc')->first();

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
