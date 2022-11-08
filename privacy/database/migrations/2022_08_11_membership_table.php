<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 100);
            $table->string('alamat', 200);
            $table->string('jabatan', 30);
            $table->string('gender');
            $table->string('nik');
            $table->date('tanggal_masuk');
            $table->string('lokasi_kerja');
            $table->string('tempat');
            $table->date('tanggal_lahir');
            $table->integer('umur');
            $table->string('agama');
            $table->string('status');
            $table->string('no_ktp');
            $table->string('no_npwp');
            $table->string('no_kk');
            $table->string('gol_darah');
            $table->string('ktp_img');
            $table->string('npwp_img');
            $table->string('kk_img');
            $table->string('keterangan');
            $table->string('status_kerja');
            $table->rememberToken();
            $table->timestamps();
            $table->string('kode_company');
            $table->binary('ttd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member');
    }
}
