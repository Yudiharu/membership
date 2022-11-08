<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Produk;
use App\Models\Konversi;
use App\Models\PermintaanDetail;

class Satuan extends Model
{
    use AuditableTrait;
    
    protected $table = 'satuan';

    protected $primaryKey = 'kode_satuan';

    public $incrementing = false;

    protected $fillable = [
        'kode_satuan',
        'nama_satuan',
        'status',
        'created_at',
        'updated_at',
        'create_by',
        'updated_by',
    ];

    public function Produk()
    {
        return $this->hasMany(Produk::class,'kode_satuan');
    }

    public function getDestroyUrlAttribute()
    {
        return route('Satuan.destroy', $this->kode_satuan);
    }

    public function getEditUrlAttribute()
    {
        return route('Satuan.edit',$this->kode_satuan);
    }

    public function getUpdateUrlAttribute()
    {
        return route('Satuan.update',$this->kode_satuan);
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
