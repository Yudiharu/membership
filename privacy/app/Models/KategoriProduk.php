<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Produk;

class KategoriProduk extends Model
{
    //
    protected $connection = 'mysql2';
    
    use AuditableTrait;

    protected $table = 'kategori_produk';

    protected $primaryKey = 'kode_kategori';

    public $incrementing = false;

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'status',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('kategoriproduk.destroy', $this->kode_kategori);
    }

    public function getEditUrlAttribute()
    {
        return route('kategoriproduk.edit',$this->kode_kategori);
    }

    public function getUpdateUrlAttribute()
    {
        return route('kategoriproduk.update',$this->kode_kategori);
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
