<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Carbon;

class InsentifhelperDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'insentif_helper_detail';

	public $incrementing = true;

	protected $fillable = [
        'id',
    	'no_timesheet',
        'no_pemakaian',
        'no_joborder',
        'tgl_pakai',
        'hari_libur',
        'premi_dalamkota',
        'premi_luarkota',
        'premi_libur',
        'total_insentif',
        'luar_kota',
        'no_insentif',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Alat()
    {
        return $this->belongsTo(Alat::class,'kode_alat');
    }

    public function JobOrder()
    {
        return $this->belongsTo(PemakaianAlat::class,'no_joborder');
    }

    public function TimeSheet()
    {
        return $this->belongsTo(PemakaianAlatDetail::class,'no_timesheet');
    }

    public function Operator()
    {
        return $this->belongsTo(Operator::class,'operator');
    }

    public function Helper1()
    {
        return $this->belongsTo(Helper::class,'helper1');
    }

    public function Helper2()
    {
        return $this->belongsTo(Helper::class,'helper2');
    }

    public function getDestroyUrlAttribute()
    {
        return route('insentifhelperdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('insentifhelperdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('insentifhelperdetail.update',$this->id);
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
