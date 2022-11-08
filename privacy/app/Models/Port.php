<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class Port extends Model
{
    //
    use AuditableTrait;

    protected $table = 'port';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_port',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('port.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('port.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('port.update',$this->id);
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->kode_company = Auth()->user()->kode_company;
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
