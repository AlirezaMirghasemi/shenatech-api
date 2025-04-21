<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // General Permissions
        Permission::create(['name' => 'view published content']);

        // User Permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit own profile']);
        Permission::create(['name' => 'delete own profile']);

        // Article Permissions
        Permission::create(['name' => 'manage articles']);
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit own articles']);
        Permission::create(['name' => 'delete own articles']);
        Permission::create(['name' => 'publish articles']);

        // Event Permissions
        Permission::create(['name' => 'manage events']);
        Permission::create(['name' => 'create events']);
        Permission::create(['name' => 'edit own events']);
        Permission::create(['name' => 'delete own events']);
        Permission::create(['name' => 'publish events']);

        // Video Permissions
        Permission::create(['name' => 'manage videos']);
        Permission::create(['name' => 'create videos']);
        Permission::create(['name' => 'edit own videos']);
        Permission::create(['name' => 'delete own videos']);
        Permission::create(['name' => 'publish videos']);

        // Comment Permissions
        Permission::create(['name' => 'manage comments']);
        Permission::create(['name' => 'create comments']);
        Permission::create(['name' => 'edit own comments']);
        Permission::create(['name' => 'delete own comments']);

        // Taxonomy Permissions
        Permission::create(['name' => 'manage tags']);
        Permission::create(['name' => 'manage slugs']);

        // Media Permissions
        Permission::create(['name' => 'manage images']);

        // Role and Permission Management
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']);

        // Create Roles
        $viewer = Role::create(['name' => 'Viewer']);
        $viewer->givePermissionTo(['view published content', 'create comments']);

        $contributor = Role::create(['name' => 'Contributor']);
        $contributor->givePermissionTo([
            'view published content',
            'create comments',
            'edit own comments',
            'delete own comments',
            'create articles',
            'edit own articles',
            'delete own articles',
            'create events',
            'edit own events',
            'delete own events',
            'create videos',
            'edit own videos',
            'delete own videos',
            'edit own profile',
        ]);

        $editor = Role::create(['name' => 'Editor']);
        $editor->givePermissionTo([
            'view published content',
            'manage comments',
            'publish articles',
            'publish events',
            'publish videos',
            'manage tags',
            'manage slugs',
            'manage images',
        ]);
        $editor->syncPermissions($contributor->permissions);

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());
    }
}
