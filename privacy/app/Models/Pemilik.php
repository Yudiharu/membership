<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\PemilikCounter;
use App\Models\JenisMobil;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Vendor;

class Pemilik extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'pemilik';

    protected $primaryKey = 'kode_pemilik';

    public $incrementing = false;

    protected $fillable = [
        'kode_pemilik',
        'total_mobil',
    ];

    public function Mobil()
    {
        return $this->belongsTo(Mobil::class,'kode_mobil');
    }

    public function JenisMobil()
    {
        return $this->belongsTo(JenisMobil::class,'kode_jenis_mobil');
    }

    public function Sopir()
    {
        return $this->belongsTo(Sopir::class,'kode_sopir');
    }

    public function getDestroyUrlAttribute()
    {
        return route('pemilik.destroy', $this->kode_pemilik);
    }

    public function getEditUrlAttribute()
    {
        return route('pemilik.edit',$this->kode_pemilik);
    }

    public function getUpdateUrlAttribute()
    {
        return route('pemilik.update',$this->kode_pemilik);
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
