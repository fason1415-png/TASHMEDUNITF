<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage_clinics',
            'manage_branches',
            'manage_departments',
            'manage_doctors',
            'manage_service_points',
            'manage_qr_codes',
            'manage_surveys',
            'manage_feedback',
            'moderate_feedback',
            'manage_rewards',
            'manage_escalations',
            'manage_billing',
            'manage_users',
            'view_dashboard',
            'view_analytics',
            'export_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $rolePermissions = [
            'super_admin' => $permissions,
            'clinic_admin' => array_diff($permissions, ['manage_clinics']),
            'branch_manager' => [
                'manage_branches',
                'manage_departments',
                'manage_doctors',
                'manage_service_points',
                'manage_qr_codes',
                'manage_feedback',
                'moderate_feedback',
                'manage_escalations',
                'view_dashboard',
                'view_analytics',
                'export_reports',
            ],
            'doctor' => [
                'view_dashboard',
                'view_analytics',
            ],
            'analyst' => [
                'view_dashboard',
                'view_analytics',
                'export_reports',
            ],
            'support_moderator' => [
                'moderate_feedback',
                'manage_escalations',
                'view_dashboard',
            ],
        ];

        foreach ($rolePermissions as $roleName => $assignedPermissions) {
            $role = Role::findOrCreate($roleName);
            $role->syncPermissions($assignedPermissions);
        }
    }
}

