<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
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

        $user = User::create([
            "name" => "Seller",
            "email" => "seler@seller.prueba",
            'email_verified_at' => now(),
            'password' => Hash::make('secret1234')
        ]);

        $user->assignRole($role);

        $role = Role::create(['name' => 'boss']);
        $role->givePermissionTo('materials', 'categories', 'unit_measures');

        $user = User::create([
            "name" => "Boss",
            "email" => "boss@boss.prueba",
            'email_verified_at' => now(),
            'password' => Hash::make('secret1234')
        ]);

        $user->assignRole($role);
    }
}
