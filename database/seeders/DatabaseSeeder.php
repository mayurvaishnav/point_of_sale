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

        $manager = User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@bowesautocentre.ie',
            'username' => 'manager',
        ]);

        $frontdesk = User::factory()->create([
            'name' => 'Frontdesk',
            'email' => 'frontdesk@bowesautocentre.ie',
            'username' => 'frontdesk',
        ]);

        $adminRole = Role::create(['name' => 'Admin']);
        $managerRole = Role::create(['name'=> 'Manager']);
        $frontdeskRole = Role::create(['name'=> 'Frontdesk']);
        
        $permissions = Permission::pluck('id','id')->all();
        $frontdeskPermissions = Permission::where('id', [9,13,14,15,16,17,21,25,26,27,28,29,30,31,32,33,34,45,36])->pluck('id','id')->all();
        
        $adminRole->syncPermissions($permissions);
        $managerRole->syncPermissions($permissions);
        $frontdeskRole->syncPermissions($frontdeskPermissions);
        
        $admin->assignRole([$adminRole->id]);
        $manager->assignRole([$managerRole->id]);
        $frontdesk->assignRole([$frontdeskRole->id]);

        // Customer::factory(5)->create();
        // Supplier::factory(2)->create();
        // Category::factory(2)->create();
        // Product::factory(10)->create();
    }
}
