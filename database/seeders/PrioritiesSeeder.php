<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Priority::insert([
            [
                'name' => 'Baja',
                'color' => '#2e7d32'
            ],
            [
                'name' => 'Media',
                'color' => '#bb4d00'
            ],
            [
                'name' => 'Alta',
                'color' => '#c62828'
            ]
        ]);
    }
}
