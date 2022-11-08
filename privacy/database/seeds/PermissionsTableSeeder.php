<?php

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Permission;
use Kodeine\Acl\Models\Eloquent\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
  		Permission::truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permission = new Permission();
		$permUser = $permission->create([ 
		    'name'        => 'user',
		    'slug'        => [          // pass an array of permissions.
		        'create'     => true,
		        'view'       => true,
		        'update'     => true,
		        'delete'     => true,
		        'view.phone' => true
		    ],
		    'description' => 'manage user permissions'
		]);

		$permission = new Permission();
		$permPost = $permission->create([ 
		    'name'        => 'post',
		    'slug'        => [          // pass an array of permissions.
		        'create'     => true,
		        'view'       => true,
		        'update'     => true,
		        'delete'     => true,
		    ],
		    'description' => 'manage post permissions'
		]);

		$roleAdmin = Role::first(); // administrator
		$roleAdmin->syncPermissions([$permUser, $permPost]);
    }
}
