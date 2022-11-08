<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Carbon;

class JoborderDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'joborder_detail';

	public $incrementing = true;

	protected $fillable = [
        'no_joborder',
    	'deskripsi',
        'qty',
        'satuan',
        'harga',
        'mob_demob',
        'total_harga',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function getDestroyUrlAttribute()
    {
        return route('joborderdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('joborderdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('joborderdetail.update',$this->id);
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
