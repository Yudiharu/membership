<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\OperatorCounter;

class HistoryPremi extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'history_premi';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'id',
        'nik',
        'no_rekening',
        'nama',
        'premi',
        'type',
        'tgl_insentif',
        'no_insentif',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('historypremi.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('historypremi.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('historypremi.update',$this->id);
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
