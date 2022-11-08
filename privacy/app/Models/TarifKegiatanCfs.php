<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class TarifKegiatanCfs extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'tarif_kegiatan_cfs';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_tarif',
        'type_pallet',
        'tgl_berlaku',
        'jenis_tarif',
        'biaya_storage',
        'biaya_receiving',
        'biaya_delivery',
    ];

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
            if ($query->biaya_storage == null) {
                $query->biaya_storage = 0;
            }

            if ($query->biaya_receiving == null) {
                $query->biaya_receiving = 0;
            }

            if ($query->biaya_delivery == null) {
                $query->biaya_delivery = 0;
            }
            
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
