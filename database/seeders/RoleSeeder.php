<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'seller']);
        $role->givePermissionTo('materials');

        $role = Role::create(['name' => 'boss']);
        $role->givePermissionTo('materials', 'categories', 'unit_measures');	
        
    }
}
