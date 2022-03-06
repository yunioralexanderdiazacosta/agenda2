<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name'          => 'Admin',
            'email'         => 'admin@example.com',
            'password'      => Hash::make('1234'),
        ]);
        $user->assignRole('Admin');
    }
}
