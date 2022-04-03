<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolAdministrativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Role::create(['name' => 'Administrativo']);
        $user = User::factory()->create([
            'name'          => 'Administrativo',
            'email'         => 'administrativo@example.com',
            'password'      => Hash::make('1234'),
        ]);
        $user->assignRole('Administrativo');
    }
}
