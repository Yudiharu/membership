<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\PembelianDetail;
use App\Models\Pembelian;
use App\Models\Produk;

class LaporanPembelian extends Model
{
    //
    use AuditableTrait;

	public $incrementing = false;

	protected $fillable = [
        'tanggal_pembelian',
        'no_pembelian',
        'jenis_po',
        'kode_produk',
        'nama_produk',
        'nama_item',
        'qty',
        'harga',
        'sub_total',
        'tanggal_awal',
        'tanggal_akhir',
    ];
}
