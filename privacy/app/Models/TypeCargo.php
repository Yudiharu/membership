<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\MasterLokasi;
use App\Models\Produk;
use App\Models\Pemakaian;

class TypeCargo extends Model
{
    //
    use AuditableTrait;

    protected $table = 'type_cargo';

    public $incrementing = true;

    protected $fillable = [
        'type_cargo',
        'kode_inv',
        'kode_inv_um',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('kapal.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('kapal.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('kapal.update',$this->id);
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;

        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
