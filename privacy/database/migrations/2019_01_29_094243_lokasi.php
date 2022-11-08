<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Lokasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('master_lokasi', function (Blueprint $table) {
            $table->integer('id_lokasi');
            $table->string('nama_lokasi', 200);
            $table->string('nickname', 50);
            $table->text('alamat');
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
