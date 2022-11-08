<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class CustomerInternal extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'customer_internal';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_customer',
        'nama_customer_po',
        'alamat2',
        'alamat3',
        'alamat4',
        'kota',
        'kode_pos',
        'fax',
        'contact_pic',
        'type_company',
        'cost_center',
        'status_aging',
        'no_kode_pajak',
        'kode_coa',
        'status',
    ];

    public function Coa()
    {
        return $this->belongsTo(Coa::class,'kode_coa');
    }

     public function getDestroyUrlAttribute()
    {
        return route('customerinternal.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('customerinternal.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('customerinternal.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status_aging = '0';
            $query->status = 'Aktif';
            //Coa Piutang Usaha
            
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
}
