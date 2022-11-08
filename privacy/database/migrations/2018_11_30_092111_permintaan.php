<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Permintaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permintaan', function (Blueprint $table) {
            $table->char('no_permintaan', 15)->primary();
            $table->string('deskripsi', 200);
            $table->date('tanggal_permintaan');
            $table->enum('type', ['Umum', 'Mobil', 'Alat','Jasa','Stok']);	
            $table->string('status', 20);
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
