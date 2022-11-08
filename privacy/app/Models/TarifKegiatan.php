<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TarifKegiatan extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'tarif_kegiatan';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_kegiatan',
        'type_kegiatan',
        'jenis_harga',
    ];

    public function Kegiatan()
    {
        return $this->belongsTo(Kegiatan::class,'id_kegiatan');
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
