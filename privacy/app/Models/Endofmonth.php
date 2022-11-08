<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\Produk;
use App\Models\tb_akhir_bulan;

class Endofmonth extends Model
{
    //
    use AuditableTrait;

    public $incrementing = false;

    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
    ];
}
