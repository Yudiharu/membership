<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class MembershipCustomer extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'membership_customer';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'kode_customer',
    ];

    public function Coa()
    {
        return $this->belongsTo(Coa::class,'kode_coa');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

     public function getDestroyUrlAttribute()
    {
        return route('membershipcustomer.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('membershipcustomer.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('membershipcustomer.update',$this->id);
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
