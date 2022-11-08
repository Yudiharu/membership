<?php

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Role;
use App\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
  		Role::truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
 

		$role = new Role();
		$roleAdmin = $role->create([
		    'name' => 'Administrator',
		    'slug' => 'administrator',
		    'description' => 'manage administration privileges'
		]);

		$role = new Role();
		$roleModerator = $role->create([
		    'name' => 'Moderator',
		    'slug' => 'moderator',
		    'description' => 'manage moderator privileges'
		]);

		$user = User::find(1);
		$user->syncRoles([$roleAdmin, $roleModerator]);
    }
}
