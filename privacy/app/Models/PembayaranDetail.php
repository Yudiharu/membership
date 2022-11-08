<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Spb;
use App\Models\Mobil;
use App\Models\Sopir;
use App\Models\Pemilik;
use App\Models\GudangDetail;

class PembayaranDetail extends Model
{
    //
    use AuditableTrait;

    protected $table = 'pembayaranpemilik_detail';

	public $incrementing = true;

	protected $fillable = [
    	'no_pembayaran',
        'no_joborder',
        'no_spb',
        'tgl_spb',
        'tgl_kembali',
        'kode_mobil',
        'kode_sopir',
        'kode_container',
        'kode_gudang',
        'tarif',
        'uang_jalan',
        'sisa',
        'dari',
        'tujuan',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function Mobil()
    {
        return $this->belongsTo(Mobil::class,'kode_mobil');
    }

    public function Sopir()
    {
        return $this->belongsTo(Sopir::class,'kode_sopir');
    }

    public function Pemilik()
    {
        return $this->belongsTo(Pemilik::class,'kode_pemilik');
    }

    public function GudangDetail()
    {
        return $this->belongsTo(GudangDetail::class,'kode_gudang');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class,'kode_customer');
    }

    public function getDestroyUrlAttribute()
    {
        return route('pembayarandetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('pembayarandetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('pembayarandetail.update',$this->id);
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
