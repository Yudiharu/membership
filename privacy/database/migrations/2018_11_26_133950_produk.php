<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Produk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('produk', function (Blueprint $table) {
            $table->char('kode_produk', 15)->primary();
            $table->string('nama_produk', 200);
            $table->char('kode_kategori', 15);
            $table->char('kode_merek', 15);
            $table->char('kode_ukuran', 15);
            $table->char('kode_satuan', 15);
            $table->string('type', 10);
            $table->double('harga_beli', 13, 2);
            $table->double('harga_jual', 13, 2);
            $table->double('hpp', 13, 2);
            $table->float('stok', 12, 3);
            $table->tinyInteger('aktif');
            $table->timestamps();
            $table->auditable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
