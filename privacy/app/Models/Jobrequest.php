<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use Carbon;

class Jobrequest extends Model
{
    //
    use AuditableTrait;

    protected $table = 'jobrequest';

	public $incrementing = true;

	protected $fillable = [
        'no_joborder',
    	'no_jobrequest',
        'tanggal_req',
        'freetime',
        'total_item',
        'status',
        'id',
    ];

    protected $appends = ['destroy_url','edit_url'];

    public function getDestroyUrlAttribute()
    {
        return route('jobrequestdetail.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('jobrequestdetail.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('jobrequestdetail.update',$this->id);
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($query){
            $query->status = 'OPEN';
            $query->total_item = 0;
            $query->kode_company = Auth()->user()->kode_company;
            $query->no_jobrequest = static::generateKode(request()->tanggal_req);
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
            
        $getkode = TransaksiSetup::where('kode_setup','017')->first();
        $kode = $getkode->kode_transaksi;

        $primary_key = (new self)->getKeyName();
        $get_prefix_1 = Auth()->user()->kode_company;

        $period = Carbon\Carbon::parse($data)->format('ym');

        $get_prefix_3 = $period;
        $prefix_result = $get_prefix_1.$kode.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$kode.$get_prefix_3);

        $lastRecort = self::where('no_jobrequest','like',$prefix_result.'%')->orderBy('no_jobrequest', 'desc')->first();

        if ( ! $lastRecort )
            $number = 0;
        else {
            $get_record_prefix = strtoupper(substr($lastRecort->{'no_jobrequest'}, 0,$prefix_result_length));
            if ($get_record_prefix == $prefix_result){
                $number = substr($lastRecort->{'no_jobrequest'},$prefix_result_length);
            }else {
                $number = 0;
            }
        }

        $result_number = $prefix_result . sprintf('%05d', intval($number) + 1);
        return $result_number ;
    }
}
