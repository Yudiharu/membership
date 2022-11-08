<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Pemilik;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Port;
use App\Models\Spbnon;
use App\Models\Customer;

class TruckingnonDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'truckingnoncontainer_detail';

	public $incrementing = true;

	protected $fillable = [
    	'no_truckingnon',
        'no_spb',
        'no_spb_manual',
        'tanggal_spb',
        'tanggal_kembali',
        'total_berat',
        'total_item',
        'kode_pemilik',
        'kode_mobil',
        'no_asset_mobil',
        'kode_sopir',
        'tarif_gajisopir',
        'uang_jalan',
        'bbm',
        'dari',
        'tujuan',
        'id',
        'status_spbnon',
        'status_hasilbagi',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Spbnon()
    {
        return $this->belongsTo(Spbnon::class,'no_spb');
    }

    public function Pemilik()
    {
        return $this->belongsTo(Pemilik::class,'kode_pemilik');
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

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

     public function getDestroyUrlAttribute()
    {
        return route('truckingnondetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('truckingnondetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('truckingnondetail.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($query){
            $query->status_spbnon = 1;
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
