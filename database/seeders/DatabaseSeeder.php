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
        $this->call([
            PermissionsSeeder::class,
            TaxRateSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Mayur',
            'email' => 'mayurvaishnav36@gmail.com',
            'username' => 'mayurvaishnav36',
        ]);

        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $admin->assignRole([$role->id]);

        // Customer::factory(5)->create();
        // Supplier::factory(2)->create();
        // Category::factory(2)->create();
        // Product::factory(10)->create();
    }
}
