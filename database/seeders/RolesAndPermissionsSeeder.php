<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ایجاد کاربر ادمین
        $adminUser = User::firstOrCreate(
            ['email' => 'alireza.mirghasemi@gmail.com'],
            [
                'username' => 'don_miralone',
                'first_name' => 'Alireza',
                'last_name' => 'Mirghasemi',
                'password' => Hash::make('2129638Dm@14'),
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE
            ]
        );

        // ایجاد مجوزها
        $permissions = [
            'view users', 'manage users', 'assign roles',
            'view own profile', 'edit own profile', 'view roles',
            'manage roles', 'view permissions', 'manage permissions',
            'view articles', 'manage articles', 'create articles',
            'edit own articles', 'delete own articles', 'publish articles',
            'view events', 'manage events', 'create events',
            'edit own events', 'delete own events', 'publish events',
            'view videos', 'manage videos', 'create videos',
            'edit own videos', 'delete own videos', 'publish videos',
            'view comments', 'manage comments', 'create comments',
            'edit own comments', 'delete own comments', 'view tags',
            'manage tags', 'view slugs', 'manage slugs',
            'view images', 'manage images', 'upload images'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'api'],
                ['created_by' => $adminUser->id]
            );
        }

        // ایجاد نقش‌ها
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'api'],
            ['created_by' => $adminUser->id]
        );
        $adminRole->syncPermissions(Permission::all());

        $viewerRole = Role::firstOrCreate(
            ['name' => 'Viewer', 'guard_name' => 'api'],
            ['created_by' => $adminUser->id]
        );
        $viewerRole->syncPermissions(Permission::whereIn('name', [
            'view own profile', 'edit own profile', 'view articles',
            'view events', 'view videos', 'view comments',
            'create comments', 'edit own comments', 'delete own comments',
            'view tags', 'view slugs', 'view images', 'upload images'
        ])->get());

        $contributorRole = Role::firstOrCreate(
            ['name' => 'Contributor', 'guard_name' => 'api'],
            ['created_by' => $adminUser->id]
        );
        $contributorPermissions = array_merge(
            $viewerRole->permissions->pluck('name')->toArray(),
            [
                'create articles', 'edit own articles', 'delete own articles',
                'create events', 'edit own events', 'delete own events',
                'create videos', 'edit own videos', 'delete own videos'
            ]
        );
        $contributorRole->syncPermissions($contributorPermissions);

        $editorRole = Role::firstOrCreate(
            ['name' => 'Editor', 'guard_name' => 'api'],
            ['created_by' => $adminUser->id]
        );
        $editorPermissions = array_merge(
            $contributorPermissions,
            [
                'publish articles', 'publish events', 'publish videos',
                'manage comments', 'manage tags', 'manage slugs',
                'manage images', 'view users'
            ]
        );
        $editorRole->syncPermissions($editorPermissions);

        // اختصاص نقش به کاربر
        $adminUser->assignRole($adminRole);

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
