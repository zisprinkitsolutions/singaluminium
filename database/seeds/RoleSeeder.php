<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_permissions = Permission::all();

        Role::updateOrCreate(
            ['slug' => 'admin-accounts'],
            ['name' => 'ADMIN & ACCOUNTS', 'deletable' => false]
        )->permissions()->sync($admin_permissions->pluck('id'));
        Role::updateOrCreate(['slug' => 'user'], ['name' => 'USER', 'deletable' => false]);
        Role::updateOrCreate(['slug' => 'supervisor'], ['name' => 'SUPERVISOR', 'deletable' => false]);
        Role::updateOrCreate(['slug' => 'watchman'], ['name' => 'WATCHMAN', 'deletable' => false]);
        Role::updateOrCreate(['slug' => 'technician'], ['name' => 'TECHNICIAN', 'deletable' => false]);
        Role::updateOrCreate(['slug' => 'engineering'], ['name' => 'ENGINEERING', 'deletable' => false]);
        Role::updateOrCreate(['slug' => 'administration'], ['name' => 'ADMINISTRATION', 'deletable' => false]);
    }
}

