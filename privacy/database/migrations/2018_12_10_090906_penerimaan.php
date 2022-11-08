<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Penerimaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('penerimaan', function (Blueprint $table) {
            $table->char('no_penerimaan', 15)->primary();
            $table->char('no_pembelian', 15);
            $table->date('tanggal_penerimaan');
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
