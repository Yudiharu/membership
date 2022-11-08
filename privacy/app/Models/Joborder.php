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

class Joborder extends Model
{
    //
    use AuditableTrait;

    protected $table = 'job_order';

    protected $primaryKey = 'no_joborder';

    public $incrementing = false;

    protected $fillable = [
        'no_joborder',
        'tgl_joborder',
        'jenis_jo',
        'type_jo',
        'no_spk',
        'kode_customer',
        'kode_consignee',
        'kode_vendor',
        'kode_kapal',
        'type_cargo',
        'tgl_muat',
        'tgl_selesai',
        'bongkar_muat_via',
        'no_reff',
        'tgl_reff',
        'type_kegiatan',
        'status_lokasi',
        'total_item',
        'grand_total',
        'order_by',
        'tongkang',
        'lokasi',
        'mob_demob',
        'periode',
        'status',
        'no_invoice',
        'kode_company',
    ];
    
    public function Customer1()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

    public function Customer2()
    {
        return $this->belongsTo(Customer::class,'kode_consignee');
    }

    public function Kapal()
    {
        return $this->belongsTo(Kapal::class,'kode_kapal');
    }

    public function Tongkangs()
    {
        return $this->belongsTo(Kapal::class,'tongkang');
    }

    public function Vendor()
    {
        return $this->belongsTo(Vendor::class,'kode_vendor');
    }

     public function getDestroyUrlAttribute()
    {
        return route('joborder.destroy', $this->no_joborder);
    }

    public function getEditUrlAttribute()
    {
        return route('joborder.edit',$this->no_joborder);
    }

    public function getUpdateUrlAttribute()
    {
        return route('joborder.update',$this->no_joborder);
    }

    public function getShowUrlAttribute()
    {
        return route('joborder.show',$this->no_joborder);
    }

    public function getCetakUrlAttribute()
    {
        return route('joborder.cetak',$this->no_joborder);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = '1';
            $query->kode_company = Auth()->user()->kode_company;
            $query->jenis_jo = 'Non-Container';
            $query->no_joborder = static::generateKode(request()->tgl_joborder);
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
        
        $getkode = TransaksiSetup::where('kode_setup','016')->first();
        $kode = $getkode->kode_transaksi;

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$kode.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$kode.$get_prefix_3);

        $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_joborder', 'desc')->first();

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
