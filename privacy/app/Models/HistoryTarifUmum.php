<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\OperatorCounter;

class HistoryTarifUmum extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'history_tarif_umum';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'tgl_berlaku',
        'biaya_admin',
        'biaya_pass_tongkang',
        'biaya_pass_truck',
        'biaya_surcharge',
        'biaya_penumpukan_cfs',
        'biaya_recv_delivery',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('tarifumum.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('tarifumum.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('tarifumum.update',$this->id);
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
