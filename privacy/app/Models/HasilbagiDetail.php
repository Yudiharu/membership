<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Port;
use App\Models\Spbnon;
use App\Models\Gudang;
use App\Models\GudangDetail;

class HasilbagiDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'hasilbagi_detail';

	public $incrementing = true;

	protected $fillable = [
    	'no_hasilbagi',
        'no_spb',
        'no_spb_manual',
        'tanggal_spb',
        'tanggal_kembali',
        'kode_mobil',
        'kode_gudang',
        'kode_container',
        'muatan',
        'tarif',
        'uang_jalan',
        'bbm',
        'sisa',
        'sisa_ujbbm',
        'dari',
        'tujuan',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Spbnon()
    {
        return $this->belongsTo(Spbnon::class,'no_spb');
    }

    public function Mobil()
    {
        return $this->belongsTo(Mobil::class,'kode_mobil');
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
        return route('hasilbagidetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('hasilbagidetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('hasilbagidetail.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($query){
            $query->kode_company = Auth()->user()->kode_company;
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
