<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Memo;
use App\Models\Vendor;
use App\Models\Signature;
use App\Models\Kapal;
use App\Models\Joborder;
use App\Models\TransaksiSetup;
use App\Models\tb_akhir_bulan;
use Carbon;

class Truckingnon extends Model
{
    //
    
    use AuditableTrait;

    protected $table = 'trucking_noncontainer';

    protected $primaryKey = 'no_truckingnon';

    public $incrementing = false;

    protected $fillable = [
        'no_truckingnon',
        'no_joborder',
        'tanggal_truckingnon',
        'kode_customer',
        'total_item',
        'gt_tarif',
        'gt_uang_jalan',
        'gt_bbm',
        'kode_company',
        'status',
        'status_kembali',
    ];

    public function Company()
    {
        return $this->belongsTo(Company::class,'kode_company');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

    public function Joborder()
    {
        return $this->Joborder(Kapal::class,'no_joborder');
    }

     public function getDestroyUrlAttribute()
    {
        return route('truckingnon.destroy', $this->no_truckingnon);
    }

    public function getEditUrlAttribute()
    {
        return route('truckingnon.edit',$this->no_truckingnon);
    }

    public function getUpdateUrlAttribute()
    {
        return route('truckingnon.update',$this->no_truckingnon);
    }

    public function getShowUrlAttribute()
    {
        return route('truckingnon.show',$this->no_truckingnon);
    }

    public function getCetakUrlAttribute()
    {
        return route('truckingnon.cetak',$this->no_truckingnon);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->status_kembali = 'FALSE';
            $query->gt_tarif = 0;
            $query->gt_uang_jalan = 0;
            $query->gt_bbm = 0;
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_truckingnon = static::generateKode(request()->tanggal_truckingnon);
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
            
        $getkode = TransaksiSetup::where('kode_setup','019')->first();
        $kode = $getkode->kode_transaksi;

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$kode.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$kode.$get_prefix_3);

        $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_truckingnon', 'desc')->first();

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
