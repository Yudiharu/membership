<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\OperatorCounter;

class Operator extends Model
{
    //
    use AuditableTrait;
  
    protected $table = 'operator';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_operator',
        'alamat',
        'kota',
        'kode_pos',
        'telp',
        'hp',
        'kontak_pic',
        'nik',
        'kode_bank',
        'no_rekening',
        'status_tembak',
        'status_insentif',
    ];
    
    public function Bank()
    {
        return $this->belongsTo(Bank::class,'kode_bank');
    }

    public function getDestroyUrlAttribute()
    {
        return route('operator.destroy', $this->id);
    }

    public function getEditUrlAttribute()
    {
        return route('operator.edit',$this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('operator.update',$this->id);
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
