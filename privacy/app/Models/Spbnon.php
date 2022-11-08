<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Memo;
use App\Models\Mobil;
use App\Models\Signature;
use App\Models\Sopir;
use App\Models\Port;
use App\Models\TransaksiSetup;
use App\Models\tb_akhir_bulan;
use Carbon;

class Spbnon extends Model
{
    //
    use AuditableTrait;

    protected $table = 'spbnon';

    protected $primaryKey = 'no_spbnon';

    public $incrementing = false;

    protected $fillable = [
        'no_spbnon',
        'tgl_spbnon',
        'total_berat',
        'kode_mobil',
        'kode_sopir',
        'tarif_gajisopir',
        'uang_jalan',
        'bbm',
        'dari',
        'tujuan',
        'total_item',
        'status',
    ];

    public function Company()
    {
        return $this->belongsTo(Company::class,'kode_company');
    }

    public function Mobil()
    {
        return $this->belongsTo(Mobil::class,'kode_mobil');
    }

    public function Sopir()
    {
        return $this->belongsTo(Sopir::class,'kode_sopir');
    }

    public function Port()
    {
        return $this->belongsTo(Port::class,'tujuan');
    }

    public function Port1()
    {
        return $this->belongsTo(Port::class,'dari');
    }

     public function getDestroyUrlAttribute()
    {
        return route('spbnon.destroy', $this->no_spbnon);
    }

    public function getEditUrlAttribute()
    {
        return route('spbnon.edit',$this->no_spbnon);
    }

    public function getUpdateUrlAttribute()
    {
        return route('spbnon.update',$this->no_spbnon);
    }

    public function getShowUrlAttribute()
    {
        return route('spbnon.show',$this->no_spbnon);
    }

    public function getCetakUrlAttribute()
    {
        return route('spbnon.cetak',$this->no_spbnon);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_spbnon = static::generateKode(request()->tgl_spbnon);
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
        
            $kode = 'SPBNC';

            $primary_key = (new self)->getKeyName();
            $get_prefix_1 = Auth()->user()->kode_company;
            // $get_prefix_2 = strtoupper($kode->kode_transaksi);

            $period = Carbon\Carbon::parse($data)->format('ym');

            $get_prefix_3 = $period;
            $prefix_result = $get_prefix_1.$kode.$get_prefix_3;
            $prefix_result_length = strlen($get_prefix_1.$kode.$get_prefix_3);

            $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_spbnon', 'desc')->first();

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
            // dd($prefix_result);

            $result_number = $prefix_result . sprintf('%06d', intval($number) + 1);
            return $result_number ;
        
        
    }
}
