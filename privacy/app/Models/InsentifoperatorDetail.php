<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Carbon;

class InsentifoperatorDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'insentif_operator_detail';

	public $incrementing = true;

	protected $fillable = [
        'no_timesheet',
    	'no_pemakaian',
        'no_joborder',
        'tgl_pakai',
        'hari_libur',
        'jam_dr',
        'jam_sp',
        'hm_dr',
        'hm_sp',
        'istirahat',
        'stand_by',
        'total_jam',
        'total_hm',
        'total_helper',
        'premi_perjam',
        'premi_libur',
        'total_insentif',
        'luar_kota',
        'no_insentif',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function getDestroyUrlAttribute()
    {
        return route('insentifoperatordetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('insentifoperatordetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('insentifoperatordetail.update',$this->id);
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
