<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TarifCfsAlat extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'tarif_cfs_alat';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_tarif',
        'tgl_berlaku',
        'jenis_tarif',
        'kode_alat',
        'per_jam',
        'per_ton',
    ];

    public function Alat()
    {
        return $this->belongsTo(Alat::class,'kode_alat');
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
