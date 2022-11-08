<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TarifContainerSize extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'tarif_container_size';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_tarif_container',
        'kode_size',
        'harga_empty',
        'harga_loaded',
    ];

    public function Size()
    {
        return $this->belongsTo(Sizecontainer::class,'kode_size');
    }

     public function getDestroyUrlAttribute()
    {
        return route('tarifkegiatan.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('tarifkegiatan.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('tarifkegiatan.update',$this->id);
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
