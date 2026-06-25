<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create or update the user
        $user = User::updateOrCreate(
            ['email' => 'sandeep198558@yahoo.com'],
            [
                'name' => 'Sandeep Rathod',
                'mobile' => '9664588677',
                'password' => bcrypt('password'),
            ]
        );

        // Define the roles to assign to grant full access
        $roles = [
            'Administrator',
            'Web-Admin',
            'Store Manager',
            'Customer'
        ];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'role' => $roleName,
                ],
                [
                    'status' => 'Active',
                ]
            );
        }
    }
}
