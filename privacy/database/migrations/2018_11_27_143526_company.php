<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Company extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('company', function (Blueprint $table) {
            $table->char('kode_company', 15)->primary();
            $table->string('nama_company', 200);
            $table->string('alamat');
            $table->string('telp', 15);
            $table->string('npwp', 30);
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
