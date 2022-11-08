<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Carbon;

class PemakaianAlatDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'pemakaian_alat_detail';

	public $incrementing = true;

	protected $fillable = [
        'no_pemakaian',
    	'kode_alat',
        'hitungan_pemakaian',
        'no_timesheet',
        'operator',
        'helper1',
        'helper2',
        'pekerjaan',
        'tgl_pakai',
        'hari_libur',
        'jam_dr',
        'jam_sp',
        'istirahat',
        'stand_by',
        'hm_dr',
        'hm_sp',
        'total_jam',
        'total_hm',
        'no_insentif',
        'no_insentif_helper1',
        'no_insentif_helper2',
        'status',
        'kode_company',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Alat()
    {
        return $this->belongsTo(Alat::class,'kode_alat');
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
        return route('pemakaianalatdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('pemakaianalatdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('pemakaianalatdetail.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->kode_company = auth()->user()->kode_company;
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
