<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Reopen;

class tb_akhir_bulan extends Model
{
    //
    use AuditableTrait;

    protected $table = 'tb_akhir_bulan';

    protected $primaryKey = 'periode';

    public $incrementing = false;

    protected $fillable = [
        'kode_company',
        'periode',
        'status_periode',
        'reopen_status',
        'name',    
    ];

    public function Reopen()
    {
    return $this->hasMany(Reopen::class,'periode');
    }

     public function getDestroyUrlAttribute()
    {
        return route('jasa.destroy', $this->kode_jasa);
    }

    public function getEditUrlAttribute()
    {
        return route('jasa.edit',$this->kode_jasa);
    }

    public function getUpdateUrlAttribute()
    {
        return route('jasa.update',$this->kode_jasa);
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