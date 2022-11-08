<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class MembershipDetail extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'membership_detail';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_header',
        'kode_customer',
        'jenis_harga',
        'tgl_aktif_alfi',
        'tgl_akhir_alfi',
        'tgl_aktif_apbmi',
        'tgl_akhir_apbmi',
    ];

    public function Coa()
    {
        return $this->belongsTo(Coa::class,'kode_coa');
    }

     public function getDestroyUrlAttribute()
    {
        return route('membershipdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('membershipdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('membershipdetail.update',$this->id);
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
