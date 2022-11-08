<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermintaanDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permintaan_detail', function (Blueprint $table) {
            $table->char('no_permintaan', 15)->primary();
            $table->char('kode_produk', 15);
            $table->char('kode_satuan', 15);
            $table->float('qty', 10,3);
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
