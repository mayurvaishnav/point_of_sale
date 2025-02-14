<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Command to run this seeder:
     * php artisan db:seed --class=PermissionsSeeder
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'user-list', 'group_name' => 'User'],
            ['name' => 'user-create', 'group_name' => 'User'],
            ['name' => 'user-edit', 'group_name' => 'User'],
            ['name' => 'user-delete', 'group_name' => 'User'],
            ['name' => 'role-list', 'group_name' => 'Role'],
            ['name' => 'role-create', 'group_name' => 'Role'],
            ['name' => 'role-edit', 'group_name' => 'Role'],
            ['name' => 'role-delete', 'group_name' => 'Role'],
            ['name' => 'product-list', 'group_name' => 'Product'],
            ['name' => 'product-create', 'group_name' => 'Product'],
            ['name' => 'product-edit', 'group_name' => 'Product'],
            ['name' => 'product-delete', 'group_name' => 'Product'],
            ['name' => 'customer-list', 'group_name' => 'Customer'],
            ['name' => 'customer-create', 'group_name' => 'Customer'],
            ['name' => 'customer-edit', 'group_name' => 'Customer'],
            ['name' => 'customer-delete', 'group_name' => 'Customer'],
            ['name' => 'supplier-list', 'group_name' => 'Supplier'],
            ['name' => 'supplier-create', 'group_name' => 'Supplier'],
            ['name' => 'supplier-edit', 'group_name' => 'Supplier'],
            ['name' => 'supplier-delete', 'group_name' => 'Supplier'],
            ['name' => 'category-list', 'group_name' => 'Category'],
            ['name' => 'category-create', 'group_name' => 'Category'],
            ['name' => 'category-edit', 'group_name' => 'Category'],
            ['name' => 'category-delete', 'group_name' => 'Category'],
            ['name' => 'customer-account-list', 'group_name' => 'Customer Account'],
            ['name' => 'customer-account-details', 'group_name' => 'Customer Account'],
            ['name' => 'customer-account-add-payment', 'group_name' => 'Customer Account'],
            ['name' => 'customer-account-delete-payment', 'group_name' => 'Customer Account'],
            ['name' => 'stock-management-list', 'group_name' => 'Stock Management'],
            ['name' => 'stock-management-add-sctock', 'group_name' => 'Stock Management'],
            ['name' => 'stock-management-adjust', 'group_name' => 'Stock Management'],
            ['name' => 'order-list', 'group_name' => 'Order'],
            ['name' => 'order-show', 'group_name' => 'Order'],
            ['name' => 'order-edit', 'group_name' => 'Order'],
            ['name' => 'order-delete', 'group_name' => 'Order'],
            ['name' => 'pos-take-order', 'group_name' => 'POS'],
            ['name' => 'report-sales', 'group_name' => 'Report'],
            ['name' => 'report-customer', 'group_name' => 'Report'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
