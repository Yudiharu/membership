<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Mobil;
use App\Models\Pemakaian;


class JenisMobil extends Model
{
    //

    use AuditableTrait;

    protected $table = 'jenis_mobils';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_jenis_mobil',
    ];

    public function Mobil()
    {
    return $this->hasMany(Mobil::class,'id');
    }

    public function Pemakaian()
    {
    return $this->hasMany(Pemakaian::class,'kode_mobil');
    }

     public function getDestroyUrlAttribute()
    {
        return route('jenismobil.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('jenismobil.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('jenismobil.update',$this->id);
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
