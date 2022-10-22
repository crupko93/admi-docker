<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Permission, Role};

class RolesTableAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'read_administration_section']);
        Permission::create(['name' => 'write_administration_section']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'read_administration_section',
            'write_administration_section'
        ]);
        $admin->save();

        $moderator = Role::create(['name' => 'moderator']);
        $moderator->save();

        $user = Role::create(['name' => 'user']);
        $user->save();
    }
}
