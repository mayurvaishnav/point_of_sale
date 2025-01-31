<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TaxRate;
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
                'group_name' => 'Role',
            ],
            [
                'name' => 'role-create',
                'group_name' => 'Role',
            ],
            [
                'name' => 'role-edit',
                'group_name' => 'Role',
            ],
            [
                'name' => 'role-delete',
                'group_name' => 'Role',
            ],
            [
                'name' => 'product-list',
                'group_name' => 'Product',
            ],
            [
                'name' => 'product-create',
                'group_name' => 'Product',
            ],
            [
                'name' => 'product-edit',
                'group_name' => 'Product',
            ],
            [
                'name' => 'product-delete',
                'group_name' => 'Product',
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

        // Tax rates
        TaxRate::create(['name' => '0% Vat', 'value' => 0]);
        TaxRate::create(['name' => '13.5% Vat', 'value' => 13.5]);
        TaxRate::create(['name' => '23% Vat', 'value' => 23]);

        Customer::factory(10)->create();
        Supplier::factory(10)->create();
        
        // Create parent categories
        Category::factory(10)->create();

        // Create child categories
        // Category::factory(20)->create();
        
        Product::factory(10)->create();
    }
}
