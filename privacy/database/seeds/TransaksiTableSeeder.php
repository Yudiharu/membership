<?php

use Illuminate\Database\Seeder;

class TransaksiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\transaksi::class, 100)->create();
    }
}
