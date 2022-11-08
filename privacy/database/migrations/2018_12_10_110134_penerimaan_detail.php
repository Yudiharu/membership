<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PenerimaanDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('penerimaan_detail', function (Blueprint $table) {
            $table->char('no_penerimaan', 15);
            $table->char('kode_produk', 15);
            $table->float('qty',10,3);
            $table->double('harga', 13,2);
            $table->increments('id');
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
