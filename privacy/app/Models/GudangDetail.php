<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Gudang;
use App\Models\Customer;
use App\Models\GudangdetailCounter;


class GudangDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'gudang_detail';

    public $incrementing = true;

    protected $fillable = [
        'kode_shipper',
        'nama_gudang',
        'tanggal_berlaku',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Gudang()
    {
        return $this->belongsTo(Gudang::class,'kode_shipper');
    } 

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }   

     public function getDestroyUrlAttribute()
    {
        return route('gudangdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('gudangdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('gudangdetail.update',$this->id);
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
