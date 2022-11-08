<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Memo;
use App\Models\Vendor;
use App\Models\Signature;
use App\Models\SPB;
use App\Models\Sopir;
use App\Models\TransaksiSetup;
use App\Models\tb_akhir_bulan;
use Carbon;

class Hasilbagi extends Model
{
    //
    use AuditableTrait;

    protected $table = 'hasilbagi_usaha';

    protected $primaryKey = 'no_hasilbagi';

    public $incrementing = false;

    protected $fillable = [
        'no_hasilbagi',
        'tanggal_hasilbagi',
        'kode_sopir',
        'nis',
        'spb_dari',
        'spb_sampai',
        'gaji',
        'tabungan',
        'honor_kenek',
        'gt_hbu',
        'total_item',
        'status',
        'no_invoice',
    ];

    public function Sopir()
    {
        return $this->belongsTo(Sopir::class,'kode_sopir');
    }

     public function getDestroyUrlAttribute()
    {
        return route('hasilbagi.destroy', $this->no_hasilbagi);
    }

    public function getEditUrlAttribute()
    {
        return route('hasilbagi.edit',$this->no_hasilbagi);
    }

    public function getUpdateUrlAttribute()
    {
        return route('hasilbagi.update',$this->no_hasilbagi);
    }

    public function getShowUrlAttribute()
    {
        return route('hasilbagi.show',$this->no_hasilbagi);
    }

    public function getCetakUrlAttribute()
    {
        return route('hasilbagi.cetak',$this->no_hasilbagi);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_hasilbagi = static::generateKode(request()->tanggal_hasilbagi);
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
            
        $getkode = TransaksiSetup::where('kode_setup','021')->first();
        $kode = $getkode->kode_transaksi;

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;
        $get_prefix_2 = strtoupper($kode);

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$get_prefix_2.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$get_prefix_2.$get_prefix_3);

        $lastRecort = self::where($primary_key,'like',$prefix_result.'%')->orderBy('no_hasilbagi', 'desc')->first();

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
