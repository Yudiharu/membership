<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class JenisHarga extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'jenis_harga';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'description',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('jenisharga.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('jenisharga.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('jenisharga.update',$this->id);
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
