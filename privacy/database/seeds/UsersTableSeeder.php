<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
        	'name' => 'Fauzan Azis',
        	'email' => 'fauzan.binas@gmail.com',
        	'password' => bcrypt('qwerty')
        ]);
    }
}
