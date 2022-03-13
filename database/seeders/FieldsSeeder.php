<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Field::insert([
            [
                'name' => 'marengo'
            ],
            [
                'name' => 'romeral'
            ],
            [
                'name' => 'los castaños'
            ],
            [
                'name' => 'campito pg'
            ],
            [
                'name' => 'campito prado'
            ]
        ]);
    }
}
