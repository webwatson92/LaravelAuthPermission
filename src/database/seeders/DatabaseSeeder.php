<?php 

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

    Permission::create(['name' => 'voir_dashboard']);
    Permission::create(['name' => 'gerer_utilisateurs']);

    $admin->givePermissionTo(['voir_dashboard', 'gerer_utilisateurs']);
    $user->givePermissionTo(['voir_dashboard']);
}
