<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TarifTrucking extends Model
{
    //
    use AuditableTrait;

    protected $table = 'tarif';

    public $incrementing = false;

    protected $fillable = [
        'kode_gudang',
        'kode_shipper',
        'tarif_trucking',
        'tanggal_berlaku',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('gudangdetail.destroy', $this->kode_gudang);
    }

    public function getEditUrlAttribute()
    {
        return route('gudangdetail.edit',$this->kode_gudang);
    }

    public function getUpdateUrlAttribute()
    {
        return route('gudangdetail.update',$this->kode_gudang);
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
