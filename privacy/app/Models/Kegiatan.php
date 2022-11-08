<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class Kegiatan extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'kegiatan';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'description',
        'container',
        'lainnya',
        'cfs',
        'kode_coa',
    ];

    public function Coa()
    {
        return $this->belongsTo(Coa::class,'kode_coa');
    }

     public function getDestroyUrlAttribute()
    {
        return route('kegiatan.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('kegiatan.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('kegiatan.update',$this->id);
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
