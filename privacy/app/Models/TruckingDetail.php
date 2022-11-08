<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\GudangDetail;
use App\Models\Sizecontainer;
use App\Models\Spb;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Pemilik;

class TruckingDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'trucking_detail';

	public $incrementing = true;

	protected $fillable = [
    	'no_trucking',
        'no_joborder',
        'kode_gudang',
        'kode_container',
        'kode_size',
        'no_seal',
        'no_spb',
        'no_spb_manual',
        'muatan',
        'colie',
        'tarif_trucking',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

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
    
    public function Spb()
    {
        return $this->belongsTo(Spb::class,'no_spb');
    }

    public function GudangDetail()
    {
        return $this->belongsTo(GudangDetail::class,'kode_gudang');
    }

    public function Sizecontainer()
    {
        return $this->belongsTo(Sizecontainer::class,'kode_size');
    }

     public function getDestroyUrlAttribute()
    {
        return route('truckingdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('truckingdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('truckingdetail.update',$this->id);
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
