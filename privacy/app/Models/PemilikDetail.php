<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Sopir;
use App\Models\Mobil;
use App\Models\Pemilik;
use App\Models\JenisMobil;


class PemilikDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'pemilik_detail';

	public $incrementing = true;

	protected $fillable = [
    	'kode_pemilik',
        'kode_mobil',
        'kode_jenis_mobil',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

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

    public function Pemilik()
    {
        return $this->belongsTo(Pemilik::class,'kode_pemilik');
    }  

     public function getDestroyUrlAttribute()
    {
        return route('pemilikdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('pemilikdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('pemilikdetail.update',$this->id);
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
