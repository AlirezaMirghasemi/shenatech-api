<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Define Permissions ---

        // User Permissions
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'manage users']); // CRUD all users
        Permission::firstOrCreate(['name' => 'assign roles']);
        Permission::firstOrCreate(['name' => 'view own profile']);
        Permission::firstOrCreate(['name' => 'edit own profile']);

        // Role & Permission Management
        Permission::firstOrCreate(['name' => 'view roles']);
        Permission::firstOrCreate(['name' => 'manage roles']); // CRUD roles & assign permissions
        Permission::firstOrCreate(['name' => 'view permissions']);
        Permission::firstOrCreate(['name' => 'manage permissions']);

        // Article Permissions
        Permission::firstOrCreate(['name' => 'view articles']); // View published/approved articles
        Permission::firstOrCreate(['name' => 'manage articles']); // CRUD all articles, change status
        Permission::firstOrCreate(['name' => 'create articles']);
        Permission::firstOrCreate(['name' => 'edit own articles']);
        Permission::firstOrCreate(['name' => 'delete own articles']);
        Permission::firstOrCreate(['name' => 'publish articles']); // Approve/reject articles

        // Event Permissions (similar to articles)
        Permission::firstOrCreate(['name' => 'view events']);
        Permission::firstOrCreate(['name' => 'manage events']);
        Permission::firstOrCreate(['name' => 'create events']);
        Permission::firstOrCreate(['name' => 'edit own events']);
        Permission::firstOrCreate(['name' => 'delete own events']);
        Permission::firstOrCreate(['name' => 'publish events']);

        // Video Permissions (similar to articles)
        Permission::firstOrCreate(['name' => 'view videos']);
        Permission::firstOrCreate(['name' => 'manage videos']);
        Permission::firstOrCreate(['name' => 'create videos']);
        Permission::firstOrCreate(['name' => 'edit own videos']);
        Permission::firstOrCreate(['name' => 'delete own videos']);
        Permission::firstOrCreate(['name' => 'publish videos']);

        // Comment Permissions
        Permission::firstOrCreate(['name' => 'view comments']); // View approved comments
        Permission::firstOrCreate(['name' => 'manage comments']); // Approve/reject/delete all comments
        Permission::firstOrCreate(['name' => 'create comments']);
        Permission::firstOrCreate(['name' => 'edit own comments']); // Maybe time-limited?
        Permission::firstOrCreate(['name' => 'delete own comments']);

        // Taxonomy Permissions (Tags & Slugs)
        Permission::firstOrCreate(['name' => 'view tags']);
        Permission::firstOrCreate(['name' => 'manage tags']);
        Permission::firstOrCreate(['name' => 'view slugs']);
        Permission::firstOrCreate(['name' => 'manage slugs']);

        // Media Permissions (Images)
        Permission::firstOrCreate(['name' => 'view images']); // View own/related images?
        Permission::firstOrCreate(['name' => 'manage images']); // Upload, delete all images
        Permission::firstOrCreate(['name' => 'upload images']);


        // --- Define Roles and Assign Permissions ---

        // Viewer Role (Guest/Basic User)
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);
        $viewerRole->givePermissionTo([
            'view own profile',
            'edit own profile',
            'view articles',
            'view events',
            'view videos',
            'view comments',
            'create comments',
            'edit own comments', // Consider adding time limit logic later
            'delete own comments',
            'view tags',
            'view slugs',
            'view images', // Maybe restrict further?
            'upload images', // Allow users to upload their own profile pic etc.
        ]);

        // Contributor Role (Can create content, needs approval)
        $contributorRole = Role::firstOrCreate(['name' => 'Contributor']);
        $contributorRole->givePermissionTo([
            'create articles',
            'edit own articles',
            'delete own articles',
            'create events',
            'edit own events',
            'delete own events',
            'create videos',
            'edit own videos',
            'delete own videos',
        ]);
        $contributorRole->syncPermissions($viewerRole->permissions->pluck('name')->toArray()); // Inherit Viewer permissions

        // Editor Role (Can manage content, taxonomy, comments)
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $editorRole->givePermissionTo([
            'publish articles',
            'publish events',
            'publish videos',
            'manage comments',
            'manage tags',
            'manage slugs',
            'manage images', // Can manage all images
            'view users', // Can view user list
        ]);
        // Inherit Contributor permissions (which already includes Viewer)
        $editorRole->syncPermissions(
            array_merge($contributorRole->permissions->pluck('name')->toArray(), $editorRole->permissions->pluck('name')->toArray())
        );


        // Admin Role (Full Access)
        // Use firstOrCreate to avoid creating duplicates on re-seeding
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        // Grant all permissions to Admin (alternative to listing all)
        $adminRole->syncPermissions(Permission::all());

        // --- (Optional) Create a default Admin User ---
        // You might want to create an initial admin user here
        $adminUser = \App\Models\User::firstOrCreate(
            ['email' => 'alireza.mirghasemi@gmail.com'],
            [
                'username' => 'don_miralone',
                'first_name' => 'Alireza',
                'last_name' => 'Mirghasemi',
                'password' => bcrypt('2129638Dm@14'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($adminRole);

        // Clear cache again after seeding
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        echo "Roles and Permissions seeded successfully.\n";
    }
}
