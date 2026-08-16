<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates one user per role defined on the `users` table
     * (admin, store, customer, captain) so every role can be
     * logged into and tested straight after a fresh migrate.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Mukesh',
                'second_name' => 'Shakya',
                'login_id' => 'MS0001',
                'mobile' => '9898989898',
                'adhaar' => '100000000001',
                'pan' => 'ABCPA0001A',
                'email' => 'admin@mediserve.test',
                'gender' => 'male',
                'otp' => '123456',
                'dob' => '1990-01-01',
                'city' => 41,
                'state' => 34,
                'pincode' => 226001,
                'address' => 1,
                'role' => 'admin',
                'isActive' => true,
                'password' => '4029',
            ],
            [
                'first_name' => 'Abhishek',
                'second_name' => 'Shakya',
                'login_id' => 'MS0002',
                'mobile' => '9595959595',
                'adhaar' => '100000000002',
                'pan' => 'ABCPA0002B',
                'email' => 'store@mediserve.test',
                'gender' => 'male',
                'otp' => '123456',
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
                'first_name' => 'Captain',
                'second_name' => 'User',
                'login_id' => 'MS0003',
                'mobile' => '9292929292',
                'adhaar' => '100000000003',
                'pan' => 'ABCPA0003C',
                'email' => 'captain@mediserve.test',
                'gender' => 'female',
                'otp' => '123456',
                'dob' => '1990-01-03',
                'city' => 41,
                'state' => 34,
                'pincode' => 226001,
                'address' => 3,
                'role' => 'captain',
                'isActive' => true,
                'password' => '123456',
            ],
            [
                'first_name' => 'Customer',
                'second_name' => 'User',
                'login_id' => 'MS0004',
                'mobile' => '9000000004',
                'adhaar' => '100000000004',
                'pan' => 'ABCPA0004D',
                'email' => 'customer@mediserve.test',
                'gender' => 'other',
                'otp' => '123456',
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
            $model = User::updateOrCreate(
                ['mobile' => $user['mobile']],
                array_merge($user, [
                    'password' => Hash::make($user['password']),
                    'email_verified_at' => now(),
                ])
            );

            // The seeded admin account is the platform's first Super Admin —
            // real sub-admin roles (Store Manager, Support Admin, ...) get
            // assigned later from the Roles & Permissions screen.
            if ($user['role'] === 'admin') {
                $model->syncRoles('Super Admin');
            }

            // A role=store User is always paired with a `stores` row in the
            // real signup flow (Admin\StoreController creates both
            // together) — this seeded account needs the same pairing, or
            // every Store-panel screen (which reads $user->store) 404s/500s
            // on it. Pre-approved so the seeded login is immediately usable.
            if ($user['role'] === 'store') {
                $model->store()->updateOrCreate(
                    ['user_id' => $model->id],
                    [
                        'shop_name' => 'MediServe Demo Pharmacy',
                        'license_no' => 'DL-DEMO-0001',
                        'gst_no' => null,
                        'latitude' => null,
                        'longitude' => null,
                        'delivery_radius_km' => null,
                        'delivery_speed_kmph' => null,
                        'status' => 'approved',
                    ]
                );
            }
        }
    }
}
