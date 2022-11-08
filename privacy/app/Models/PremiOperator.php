<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class PremiOperator extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'premi_operator';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'kode_alat',
        'tipe_hitungan',
        'tgl_berlaku',
        'premi_jam_nontranshipment',
        'premi_jam_transhipment',
        'premi_opr_tembak',
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
        return route('premioperator.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('premioperator.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($query){
            if ($query->premi_jam_transhipment == null) {
                $query->premi_jam_transhipment = 0;
            }

            if ($query->premi_jam_nontranshipment == null) {
                $query->premi_jam_nontranshipment = 0;
            }

            if ($query->premi_opr_tembak == null) {
                $query->premi_opr_tembak = 0;
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
