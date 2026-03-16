<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        if (User::where('email', 'superadmin@nusaapp.com')->exists()) {
            $this->command->info('Super Admin already exists!');
            return;
        }

        // Create Super Admin Employee
        $employee = Employee::create([
            'nik' => 'EMP001',
            'nip' => 'NIPJKT260001',
            'full_name' => 'Super Admin',
            'email' => 'superadmin@nusaapp.com',
            'phone_number' => '081234567890',
            'current_address' => 'Jakarta, Indonesia',
            'birth_date' => '1990-01-01',
            'employment_status' => 'Permanent',
        ]);

        // Create Super Admin User
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@nusaapp.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'employee_id' => $employee->id,
        ]);

        // Assign super-admin role
        $user->assignRole('super-admin');

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: superadmin@nusaapp.com');
        $this->command->info('Password: password');
    }
}
