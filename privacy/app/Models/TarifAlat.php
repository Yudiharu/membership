<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TarifAlat extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'tarif_alat';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'kode_alat',
        'tgl_berlaku',
        'tarif',
    ];

    public function Alat()
    {
        return $this->belongsTo(Alat::class,'kode_alat');
    }

     public function getDestroyUrlAttribute()
    {
        return route('tarifalat.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('tarifalat.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('tarifalat.update',$this->id);
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
