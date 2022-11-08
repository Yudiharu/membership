<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Memo;
use App\Models\Vendor;
use App\Models\Signature;
use App\Models\SPB;
use App\Models\Pemilik;
use App\Models\TransaksiSetup;
use App\Models\tb_akhir_bulan;
use Carbon;

class Pembayaran extends Model
{
    //
    use AuditableTrait;

    protected $table = 'pembayaran_pemilik';

    protected $primaryKey = 'no_pembayaran';

    public $incrementing = false;

    protected $fillable = [
        'no_pembayaran',
        'tanggal_pembayaran',
        'tanggalkembali_dari',
        'tanggalkembali_sampai',
        'kode_pemilik',
        'total_item',
        'status',
        'no_invoice',
    ];

    public function Pemilik()
    {
        return $this->belongsTo(Pemilik::class,'kode_pemilik');
    }

     public function getDestroyUrlAttribute()
    {
        return route('pembayaran.destroy', $this->no_pembayaran);
    }

    public function getEditUrlAttribute()
    {
        return route('pembayaran.edit',$this->no_pembayaran);
    }

    public function getUpdateUrlAttribute()
    {
        return route('pembayaran.update',$this->no_pembayaran);
    }

    public function getShowUrlAttribute()
    {
        return route('pembayaran.show',$this->no_pembayaran);
    }

    public function getCetakUrlAttribute()
    {
        return route('pembayaran.cetak',$this->no_pembayaran);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_pembayaran = static::generateKode(request()->tanggal_pembayaran);
            $query->created_by = Auth()->user()->name;
            $query->updated_by = Auth()->user()->name;
        });

        static::updating(function ($query){
           $query->updated_by = Auth()->user()->name;
        });
    }

    public static function generateKode($data)
    {
        $user = Auth()->user()->level;
            
        $getkode = TransaksiSetup::where('kode_setup','020')->first();
        $kode = $getkode->kode_transaksi;

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;
        $get_prefix_2 = strtoupper($kode);

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$get_prefix_2.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$get_prefix_2.$get_prefix_3);

        $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_pembayaran', 'desc')->first();

        if ( ! $lastRecort )
            $number = 0;
        else {
            $get_record_prefix = strtoupper(substr($lastRecort->{$primary_key}, 0,$prefix_result_length));
            if ($get_record_prefix == $prefix_result){
                $number = substr($lastRecort->{$primary_key},$prefix_result_length);
            }else {
                $number = 0;
            }
        }

        $result_number = $prefix_result . sprintf('%05d', intval($number) + 1);
        return $result_number ;
    }
}
