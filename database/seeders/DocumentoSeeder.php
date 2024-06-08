<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Documento;
use Illuminate\Database\Seeder;

class DocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Documento::insert([
            [
                'tipo_documeto' => 'DNI',
            ],
            [
                'tipo_documeto' => 'Pasaporte',
            ],
            [
                'tipo_documeto' => 'RUC',
            ],
            [
                'tipo_documeto' => 'Carnet Extranjería',
            ]
        ]);
    }
}
