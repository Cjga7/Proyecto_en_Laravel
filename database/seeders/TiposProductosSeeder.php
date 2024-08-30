<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_productos')->insert([
            ['nombre' => 'Producto Terminado'],
            ['nombre' => 'Materia Prima'],
        ]);
    }
}
