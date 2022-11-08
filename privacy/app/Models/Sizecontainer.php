<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class Sizecontainer extends Model
{
    //
    use AuditableTrait;

    protected $table = 'size_container';

    protected $primaryKey = 'kode_size';

    public $incrementing = false;

    protected $fillable = [
        'kode_size',
        'nama_size',
    ];
    
    public function getDestroyUrlAttribute()
    {
        return route('sizecontainer.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('sizecontainer.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('sizecontainer.update',$this->id);
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
