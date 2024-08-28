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
        $admin = Role::create(['name' => 'admin']);
        $clerk = Role::create(['name' => 'clerk']);
        $cashier = Role::create(['name' => 'cashier']);
        $permissions = [];

        $permission = Permission::create(['name' => 'accept remittances']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'disburse remittance cash']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'add to inventory']);
        $permissions[] = $permission;
        $clerk->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'approve inventory prices']);
        $permissions[] = $permission;

        $permission = Permission::create(['name' => 'delete inventory items']);
        $permissions[] = $permission;

        $permission = Permission::create(['name' => 'edit inventory items']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);
        $clerk->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'add to cart']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);
        $clerk->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'generate daily report']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'initiate payment']);
        $permissions[] = $permission;
        $clerk->givePermissionTo($permission);
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'receive payment']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'remove initiated payment']);
        $permissions[] = $permission;
        $clerk->givePermissionTo($permission);
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'disburse parcel']);
        $permissions[] = $permission;
        $clerk->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'create withdrawal request']);
        $permissions[] = $permission;
        $clerk->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'approve withdrawal request']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'disburse withdrawal request']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'approve inventory']);
        $permissions[] = $permission;
        $admin->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'initiate refund']);
        $permissions[] = $permission;
        $clerk->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'approve refund']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        $permission = Permission::create(['name' => 'disburse refund amount']);
        $permissions[] = $permission;
        $cashier->givePermissionTo($permission);

        //Super Admin
        $admin->syncPermissions($permissions);

        //
        User::where('email', 'maunga.simbarashe@gmail.com')->first()->syncRoles([$admin, $cashier, $clerk]);
        User::where('email', 'emmanuel@dxbrunners.co.zw')->first()->assignRole('admin');
        User::where('email', 'clerk@gmail.com')->first()->assignRole('clerk');
        User::where('email', 'cashier@gmail.com')->first()->assignRole('cashier');

        User::where('email', 'lulu@dxbrunners.co.zw')->first()->givePermissionTo([
            'approve refund',
            'approve withdrawal request',
            'approve inventory prices'
        ]);
    }
}
