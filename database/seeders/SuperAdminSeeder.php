<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'super-admin']);
        $user = User::create([
            "name" => "Admin",
            "email" => "admin@admin.prueba",
            'email_verified_at' => now(),
            'password' => Hash::make('adminsecret')
        ]);

        $user->assignRole($role);
    }
}
