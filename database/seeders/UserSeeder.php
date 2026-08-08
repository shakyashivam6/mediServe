<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates one user per role defined on the `users` table
     * (admin, store, customer, distributer) so every role can be
     * logged into and tested straight after a fresh migrate.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Mukesh',
                'second_name' => 'Shakya',
                'mobile' => '9898989898',
                'adhaar' => '100000000001',
                'pan' => 'ABCPA0001A',
                'email' => 'admin@mediserve.test',
                'gender' => 'male',
                'otp' => '000000',
                'dob' => '1990-01-01',
                'city' => 41,
                'state' => 34,
                'pincode' => 226001,
                'address' => 1,
                'role' => 'admin',
                'isActive' => true,
                'password' => '8892205223',
            ],
            [
                'first_name' => 'Abhishek',
                'second_name' => 'Shakya',
                'mobile' => '9595959595',
                'adhaar' => '100000000002',
                'pan' => 'ABCPA0002B',
                'email' => 'store@mediserve.test',
                'gender' => 'male',
                'otp' => '000000',
                'dob' => '1990-01-02',
                'city' => 41,
                'state' => 34,
                'pincode' => 226001,
                'address' => 2,
                'role' => 'store',
                'isActive' => true,
                'password' => '123456',
            ],
            [
                'first_name' => 'Distributer',
                'second_name' => 'User',
                'mobile' => '9292929292',
                'adhaar' => '100000000003',
                'pan' => 'ABCPA0003C',
                'email' => 'distributer@mediserve.test',
                'gender' => 'female',
                'otp' => '000000',
                'dob' => '1990-01-03',
                'city' => 41,
                'state' => 34,
                'pincode' => 226001,
                'address' => 3,
                'role' => 'distributer',
                'isActive' => true,
                'password' => '123456',
            ],
            [
                'first_name' => 'Customer',
                'second_name' => 'User',
                'mobile' => '9000000004',
                'adhaar' => '100000000004',
                'pan' => 'ABCPA0004D',
                'email' => 'customer@mediserve.test',
                'gender' => 'other',
                'otp' => '000000',
                'dob' => '1990-01-04',
                'city' => 41,
                'state' => 34,
                'pincode' => 226001,
                'address' => 4,
                'role' => 'customer',
                'isActive' => true,
                'password' => '123456',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['mobile' => $user['mobile']],
                array_merge($user, [
                    'password' => Hash::make($user['password']),
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
