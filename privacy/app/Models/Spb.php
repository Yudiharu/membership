<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Memo;
use App\Models\Mobil;
use App\Models\Signature;
use App\Models\Sopir;
use App\Models\Pemilik;
use App\Models\Vendor;
use App\Models\GudangDetail;
use App\Models\Customer;
use App\Models\TransaksiSetup;
use App\Models\tb_akhir_bulan;
use Carbon;

class Spb extends Model
{
    //
    use AuditableTrait;

    protected $table = 'spb';

    protected $primaryKey = 'no_spb';

    public $incrementing = false;

    protected $fillable = [
        'no_spb',
        'no_joborder',
        'no_spb_manual',
        'tgl_spb',
        'tgl_kembali',
        'kode_mobil',
        'no_asset_mobil',
        'kode_sopir',
        'kode_pemilik',
        'uang_jalan',
        'bbm',
        'bpa',
        'honor',
        'biaya_lain',
        'trucking',
        'status_spb',
        'status_hasilbagi',
    ];

    public function Vendor()
    {
        return $this->belongsTo(Vendor::class,'kode_pemilik');
    }

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

    public function Pemilik()
    {
        return $this->belongsTo(Pemilik::class,'kode_pemilik');
    }

    public function GudangDetail()
    {
        return $this->belongsTo(GudangDetail::class,'kode_gudang');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

     public function getDestroyUrlAttribute()
    {
        return route('spb.destroy', $this->no_spb);
    }

    public function getEditUrlAttribute()
    {
        return route('spb.edit',$this->no_spb);
    }

    public function getUpdateUrlAttribute()
    {
        return route('spb.update',$this->no_spb);
    }

    public function getShowUrlAttribute()
    {
        return route('spb.show',$this->no_spb);
    }

    public function getCetakUrlAttribute()
    {
        return route('spb.cetak',$this->no_spb);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status_spb = 1;
            $query->status_hasilbagi = 1;
            $query->kode_company = Auth()->user()->kode_company;
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
