<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // clear the cache – oude permissies/roles verwijderen

        app()[\Spatie\Permission\PermissionRegistrar::class]->

            forgetCachedPermissions();


        // create roles

        $adminRole = Role::create(['name' => 'admin']);

        $userRole = Role::create(['name' => 'user']);


        // Permissions for watchlist CRUD

        Permission::create(['name' => 'index watchlist']);

        Permission::create(['name' => 'show watchlist']);

        Permission::create(['name' => 'create watchlist']);

        Permission::create(['name' => 'edit watchlist']);

        Permission::create(['name' => 'delete watchlist']);


        // Permissions for roles

        $adminRole->givePermissionTo(Permission::all());

        $userRole->givePermissionTo(
            'index watchlist',
            'show watchlist',

            'create watchlist',
            'edit watchlist'
        );


        // create users with role

        $admin = User::create([

            'name' => 'Admin',

            'email' => 'admin@test.com',

            'password' => bcrypt('12345678'),

        ]);

        $admin->assignRole('admin');


        $user1 = User::create([

            'name' => 'User1',

            'email' => 'user1@test.com',

            'password' => bcrypt('12345678'),

        ]);

        $user1->assignRole('user');

    }
}
