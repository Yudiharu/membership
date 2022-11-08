<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\SopirCounter;

class Sopir extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'sopir';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_sopir',      
        'alamat',
        'kota',
        'kode_pos',
        'telp',
        'hp',
        'nis',
        'gaji',
        'tabungan',
        'no_rekening',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('sopir.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('sopir.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('sopir.update',$this->id);
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
