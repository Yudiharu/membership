<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Mobil;
use App\Models\Pemakaian;


class TanggalSetup extends Model
{
    //

    use AuditableTrait;

    // protected $connection = 'mysql2';

    protected $table = 'tanggal_setup';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'kode_setup',
        'hari',
    ];

     public function getDestroyUrlAttribute()
    {
        return route('tanggalsetup.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('tanggalsetup.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('tanggalsetup.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            // $query->kode_company = auth()->user()->kode_company;
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
