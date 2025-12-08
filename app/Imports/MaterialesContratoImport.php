<?php

namespace App\Imports;

use App\Models\MaterialContrato;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MaterialesContratoImport implements ToCollection, WithHeadingRow
{
    protected $proyectoId;
    public $importedCount = 0;
    public $skippedCount = 0;

    public function __construct(int $proyectoId)
    {
        $this->proyectoId = $proyectoId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (empty(trim($row['descripcion'] ?? '')) && empty(trim($row['unidad'] ?? ''))) {
                continue;
            }

            $existing = MaterialContrato::where('proyecto_id', $this->proyectoId)
                ->where('descripcion', trim($row['descripcion']))
                ->first();

            if ($existing) {
                $this->skippedCount++;
                Log::info("Material duplicado saltado: {$row['descripcion']}");
                continue;
            }

            MaterialContrato::create([
                'proyecto_id' => $this->proyectoId,
                'descripcion' => trim($row['descripcion']),
                'unidad' => $row['unidad'],
                'cantidad' => $row['cantidad'],
                'precio_unit' => $row['precio_unit'],
                'total' => $row['cantidad'] * $row['precio_unit'],
            ]);

            $this->importedCount++;
        }
    }
}