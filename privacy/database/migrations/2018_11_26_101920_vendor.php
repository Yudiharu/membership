<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('vendor', function (Blueprint $table) {
            $table->char('kode_vendor', 15)->primary();
            $table->string('nama_vendor', 200);
            $table->string('alamat');
            $table->string('telp', 15);
            $table->string('hp', 15);
            $table->string('npwp', 30);
            $table->integer('termin_pembayaran');
            $table->integer('status');
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
