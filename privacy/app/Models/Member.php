<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class Member extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'member';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'nik',
        'tanggal_masuk',
        'lokasi_kerja',
        'jabatan',
        'gender',
        'tempat',
        'tanggal_lahir',
        'umur',
        'alamat',
        'agama',
        'status',
        'no_ktp',
        'no_npwp',
        'no_kk',
        'gol_darah',
        'keterangan',
        'ktp_img',
        'npwp_img',
        'kk_img',
        'kode_company',
        'status_kerja',
        'ttd',
    ];

     public function getDestroyUrlAttribute()
    {
        return route('customer.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('customer.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('customer.update',$this->id);
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
