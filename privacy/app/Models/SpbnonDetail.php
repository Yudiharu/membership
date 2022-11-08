<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;


class SpbnonDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'spbnon_detail';

	public $incrementing = false;

	protected $fillable = [
        'no_spbnon',
        'no_joborder',
    	'kode_item',
        'qty',
        'berat_satuan',
        'total_berat',
        'keterangan',
        'id',
    ];

    public function getDestroyUrlAttribute()
    {
        return route('truckingnondetail.destroy', $this->no_spbnon);
    }

    public function getEditUrlAttribute()
    {
        return route('truckingnondetail.edit',$this->no_spbnon);
    }

    public function getUpdateUrlAttribute()
    {
        return route('truckingnondetail.update',$this->no_spbnon);
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
