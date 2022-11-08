<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class PremiHelper extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'premi_helper';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'kode_alat',
        'tgl_berlaku',
        'premi_harian_dk',
        'premi_harian_lk',
        'hari_libur',
    ];

    public function Alat()
    {
        return $this->belongsTo(Alat::class,'kode_alat');
    }

     public function getDestroyUrlAttribute()
    {
        return route('premioperator.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('premihelper.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('premihelper.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($query){
            if ($query->premi_harian_dk == null) {
                $query->premi_harian_dk = 0;
            }

            if ($query->premi_harian_lk == null) {
                $query->premi_harian_lk = 0;
            }

            if ($query->hari_libur == null) {
                $query->hari_libur = 0;
            }

            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
