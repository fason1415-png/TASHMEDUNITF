<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            DemoDataSeeder::class,
        ]);

        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'super.admin@shiforeyting.local'],
            [
                'name' => 'Super Admin',
                'clinic_id' => null,
                'branch_id' => null,
                'doctor_id' => null,
                'phone' => '+998900000000',
                'email_verified_at' => now(),
                'password' => Hash::make('Password123!'),
                'preferred_language' => 'uz_latn',
                'is_active' => true,
            ]
        );

        $superAdmin->assignRole('super_admin');
    }
}

