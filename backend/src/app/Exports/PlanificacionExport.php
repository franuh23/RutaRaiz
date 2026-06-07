<?php

namespace App\Exports;

use App\Models\Planificacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlanificacionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $id;

    // Le pasamos el ID de la planificación al constructor
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Traemos las etapas de esta planificación específica
     */
    public function collection()
    {
        // Traemos la planificación del usuario con sus etapas ordenadas
        $planificacion = Planificacion::with(['etapas.localizacionInicio', 'etapas.localizacionFin'])
            ->findOrFail($this->id);

        return $planificacion->etapas;
    }

    /**
     * Cabeceras del Excel
     */
    public function headings(): array
    {
        return [
            'Día de Caminata',
            'Punto de Origen',
            'Punto de Destino',
            'Distancia del Tramo (Km)'
        ];
    }

    /**
     * Mapeo de datos: Qué va en cada columna
     * $etapa es cada registro de la colección que sacamos arriba
     */
    public function map($etapa): array
    {
        return [
            'Día ' . $etapa->dia,
            $etapa->localizacionInicio->nombre,
            $etapa->localizacionFin->nombre,
            round($etapa->distancia, 1)
        ];
    }

    /**
     * Estilos para el Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2D5A27']
                ]
            ],
        ];
    }
}
