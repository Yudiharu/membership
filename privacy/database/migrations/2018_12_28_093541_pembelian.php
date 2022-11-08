<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pembelian', function (Blueprint $table) {
            $table->char('no_pembelian', 15)->primary();
            $table->char('no_memo', 15);
            $table->char('kode_vendor', 15);
            $table->date('tanggal_pembelian');
            $table->string('status',20);
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
