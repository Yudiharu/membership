<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class JenisAlat extends Model
{
    //
    use AuditableTrait;

    protected $connection = 'mysqlinvdepo';
  
    protected $table = 'jenis_alat';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'kode_jenis',
        'description',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('jenisalat.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('jenisalat.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('jenisalat.update',$this->id);
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
