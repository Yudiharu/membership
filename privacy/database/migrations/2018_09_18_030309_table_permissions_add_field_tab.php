<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablePermissionsAddFieldTab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('tab')->nullable()->after('display_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         if (Schema::hasColumn('permissions', 'tab')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropColumn('tab');
            });
        }
    }
}
