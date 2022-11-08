<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Customer;

class Gudang extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'gudang';

    protected $primaryKey = 'kode_shipper';

    public $incrementing = false;

    protected $fillable = [
        'kode_shipper',
        'kode_customer',
    ];

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

    public function getDestroyUrlAttribute()
    {
        return route('gudang.destroy', $this->kode_shipper);
    }

    public function getEditUrlAttribute()
    {
        return route('gudang.edit',$this->kode_shipper);
    }

    public function getUpdateUrlAttribute()
    {
        return route('gudang.update',$this->kode_shipper);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->kode_company = Auth()->user()->kode_company;
            $query->kode_shipper = request()->kode_customer;
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;

        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
