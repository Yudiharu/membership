<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\MasterLokasi;
use App\Models\Produk;
use App\Models\Pemakaian;

class Kapal extends Model
{
    //
    use AuditableTrait;

    protected $connection = 'mysqlinvpbm';

    protected $table = 'kapal';

    protected $primaryKey = 'kode_kapal';

    public $incrementing = false;

    protected $fillable = [
        'kode_kapal',
        'nama_kapal',
        'type',
    ];

    public function MasterLokasi()
    {
        return $this->belongsTo(MasterLokasi::class,'kode_lokasi');
    }

    public function getDestroyUrlAttribute()
    {
        return route('kapal.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('kapal.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('kapal.update',$this->id);
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->kode_lokasi = 'HO';
            $query->kode_company = Auth()->user()->kode_company;
            $query->kode_kapal = static::generateNumber(request()->nama_kapal);
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;

        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }
    
    public static function generateNumber($sumber_text)
    {
        $lastRecort = self::orderBy('kode_kapal', 'desc')->first();
        $prefix = strtoupper($sumber_text) ;
        $primary_key = (new self)->getKeyName();


        if ( $lastRecort == null )
            $number = 0;
        else {
            $field = $lastRecort->{$primary_key} ;

            if ($prefix[0] != $lastRecort->{$primary_key}[0]){
                $number = $field;
            }else {
                $number = 0;
            }
        }

        return sprintf('%03d', intval($number) + 1);
    }
}
