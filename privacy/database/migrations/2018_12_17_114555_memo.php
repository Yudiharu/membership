<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Memo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('memo', function (Blueprint $table) {
            $table->char('no_memo', 15)->primary();
            $table->char('no_permintaan', 15);
            $table->date('tanggal_memo');
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
