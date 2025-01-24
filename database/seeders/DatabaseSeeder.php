<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $permissions = [
            [
                'name' => 'role-list',
                'group_name' => 'role',
            ],
            [
                'name' => 'role-create',
                'group_name' => 'role',
            ],
            [
                'name' => 'role-edit',
                'group_name' => 'role',
            ],
            [
                'name' => 'role-delete',
                'group_name' => 'role',
            ],
            [
                'name' => 'product-list',
                'group_name' => 'product',
            ],
            [
                'name' => 'product-create',
                'group_name' => 'product',
            ],
            [
                'name' => 'product-edit',
                'group_name' => 'product',
            ],
            [
                'name' => 'product-delete',
                'group_name' => 'product',
            ]
         ];
         
         foreach ($permissions as $permission) {
              Permission::create($permission);
         }

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
        ]);

        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $admin->assignRole([$role->id]);

        Customer::factory(10)->create();
        Supplier::factory(10)->create();
        
        // Create parent categories
        Category::factory(5)->create();

        // Create child categories
        Category::factory(20)->create();

    }
}
