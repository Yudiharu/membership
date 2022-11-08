<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Port;
use App\Models\tb_akhir_bulan;

class KapalCounter extends Model
{
    use AuditableTrait;

    protected $table = 'kapal_counter';

    public $incrementing = false;

    protected $fillable = [
        'index',
        'jumlah',
    ];

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
