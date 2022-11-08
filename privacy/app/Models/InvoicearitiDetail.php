<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Cashbankin;
use App\Models\Coa;


class InvoicearitiDetail extends Model
{
    //
    use AuditableTrait;

    protected $connection = 'mysqlpbm';

    protected $table = 'invoiceariti_detail';

	public $incrementing = true;

	protected $fillable = [
        'no_invoice',
    	'kode_coa',
        'kode_alat',
        'keterangan',
        'qty',
        'harga_satuan',
        'sub_total',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Cashbankin()
    {
        return $this->belongsTo(Cashbankin::class,'no_cashbank_in');
    }  

    public function Coa()
    {
        return $this->belongsTo(Coa::class,'kode_coa');
    }

    public function getDestroyUrlAttribute()
    {
        return route('invoicearitidetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('invoicearitidetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('invoicearitidetail.update',$this->id);
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
