<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Sizecontainer;
use Carbon;

class JobrequestDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'jobrequest_detail';

	public $incrementing = true;

	protected $fillable = [
        'no_joborder',
    	'no_jobrequest',
        'kode_alat',
        'qty',
        'harga',
        'subtotal',
        'tgl_request',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Alat()
    {
        return $this->belongsTo(Alat::class,'kode_alat');
    }

    public function getDestroyUrlAttribute()
    {
        return route('jobrequestdetail.destroy', $this->no_jobrequest);
    }

    public function getEditUrlAttribute()
    {
        return route('jobrequestdetail.edit',$this->no_jobrequest);
    }

    public function getUpdateUrlAttribute()
    {
        return route('jobrequestdetail.update',$this->no_jobrequest);
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
