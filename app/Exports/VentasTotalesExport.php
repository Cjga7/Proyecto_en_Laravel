<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VentasTotalesExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $ventasDelMesSeleccionado;
    protected $anio;
    protected $mesSeleccionado;

    public function __construct($ventasDelMesSeleccionado, $anio, $mesSeleccionado)
    {
        $this->ventasDelMesSeleccionado = $ventasDelMesSeleccionado;
        $this->anio = $anio;
        $this->mesSeleccionado = $mesSeleccionado;
    }

    // Generar la colección de datos
    public function collection()
{
    return collect($this->ventasDelMesSeleccionado)->map(function ($venta) {
        return [
            'dia' => $venta['dia'],  // Acceder como arreglo
            'total' => $venta['total'], // Acceder como arreglo
        ];
    });
}



    // Encabezados de las columnas
    public function headings(): array
    {
        return [
            'Día',
            'Total Ventas (BOB)', // Ajustado a la moneda de Bolivia
        ];
    }

    // Estilos para las celdas
    public function styles(Worksheet $sheet)
    {
        return [
            // Aplicar negrita en la primera fila (encabezados)
            1 => ['font' => ['bold' => true]],

            // Otras configuraciones de estilo pueden añadirse aquí
        ];
    }

    // Formato de las columnas
    public function columnFormats(): array
    {
        return [
        ];
    }
}
