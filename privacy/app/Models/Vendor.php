<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use DB;
use Carbon;

class Vendor extends Model
{
    use AuditableTrait;
    
    protected $connection = 'mysql2';
    
    protected $table = 'vendor';

    protected $primaryKey = 'id';

    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'type',
        'nama_vendor',
        'nama_vendor_po',
        'alamat',
        'telp',
        'hp',
        'norek_vendor',
        'npwp',
        'nama_kontak',
        'kode_coa',
        'status',
    ];

    public function Coa()
    {
        return $this->belongsTo(Coa::class,'kode_coa');
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
