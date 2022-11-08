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

class Trucking extends Model
{
    //
    use AuditableTrait;

    protected $table = 'trucking';

    protected $primaryKey = 'no_trucking';

    public $incrementing = false;

    protected $fillable = [
        'no_trucking',
        'no_joborder',
        'no_req_jo',
        'tanggal_trucking',
        'kode_customer',
        'kode_shipper',
        'kode_kapal',
        'voyage',
        'no_do',
        'total_item',
        'gt_uang_jalan',
        'kode_company',
        'status',
        'status_kembali',
    ];

    public function Company()
    {
        return $this->belongsTo(Company::class,'kode_company');
    }

    public function Customer1()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

    public function Customer2()
    {
        return $this->belongsTo(Customer::class,'kode_shipper');
    }

    public function Gudang()
    {
        return $this->belongsTo(Gudang::class,'kode_shipper');
    }

    public function Kapal()
    {
        return $this->belongsTo(Kapal::class,'kode_kapal');
    }

    public function Joborder()
    {
        return $this->Joborder(Kapal::class,'no_joborder');
    }

     public function getDestroyUrlAttribute()
    {
        return route('trucking.destroy', $this->trucking);
    }

    public function getEditUrlAttribute()
    {
        return route('trucking.edit',$this->trucking);
    }

    public function getUpdateUrlAttribute()
    {
        return route('trucking.update',$this->trucking);
    }

    public function getShowUrlAttribute()
    {
        return route('trucking.show',$this->trucking);
    }

    public function getCetakUrlAttribute()
    {
        return route('trucking.cetak',$this->trucking);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->status_kembali = 'FALSE';
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_trucking = static::generateKode(request()->tanggal_trucking);
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
            
        $getkode = TransaksiSetup::where('kode_setup','018')->first();
        $kode = $getkode->kode_transaksi;

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$kode.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$kode.$get_prefix_3);

        $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_trucking', 'desc')->first();

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
