<?php

namespace App\Imports;

use App\Models\ManoObraModulo;
use App\Models\ManoObraItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ManoObraImport implements ToCollection
{
    protected int $proyectoId;
    protected int $moduloOrden  = 0;
    protected int $itemOrden    = 0;
    protected ?ManoObraModulo $moduloActual = null;

    public function __construct(int $proyectoId)
    {
        $this->proyectoId = $proyectoId;
    }

    public function collection(Collection $rows)
    {
        // Limpiar datos previos del proyecto
        ManoObraModulo::where('proyecto_id', $this->proyectoId)->delete();

        foreach ($rows as $index => $row) {
            // Ignorar filas completamente vacías
            $fila = array_values($row->toArray());
            if (empty(array_filter($fila, fn($v) => $v !== null && $v !== ''))) {
                continue;
            }

            // ─────────────────────────────────────────────
            // DETECTAR FILA DE MÓDULO
            // El Excel tiene el formato: [vacío, "> M01 - NOMBRE", vacío, vacío, vacío, parcial]
            // o la primera celda puede ser ">" o vacío y la descripción empieza con ">"
            // ─────────────────────────────────────────────
            $col0 = trim((string)($fila[0] ?? ''));
            $col1 = trim((string)($fila[1] ?? ''));

            $esModulo = false;
            $textoModulo = '';

            if (str_starts_with($col1, '>') || str_starts_with($col0, '>')) {
                $esModulo    = true;
                $textoModulo = ltrim($col1 ?: $col0, '> ');
            }

            if ($esModulo) {
                // Parsear "M01 - OBRAS PRELIMINARES"
                $parts   = explode(' - ', $textoModulo, 2);
                $codigo  = trim($parts[0]);              // M01
                $nombre  = trim($parts[1] ?? $textoModulo); // OBRAS PRELIMINARES

                $this->moduloActual = ManoObraModulo::create([
                    'proyecto_id' => $this->proyectoId,
                    'codigo'      => $codigo,
                    'nombre'      => $nombre,
                    'orden'       => ++$this->moduloOrden,
                ]);

                $this->itemOrden = 0; // reset orden de ítems para cada módulo
                continue;
            }

            // ─────────────────────────────────────────────
            // DETECTAR FILA DE ÍTEM
            // Formato: [Nº, Descripción, Und., Cantidad, Unitario, Parcial]
            // ─────────────────────────────────────────────
            $numero      = $fila[0] ?? null;
            $descripcion = trim((string)($fila[1] ?? ''));
            $unidad      = trim((string)($fila[2] ?? ''));
            $cantidad    = $this->parseNumero($fila[3] ?? 0);
            $unitario    = $this->parseNumero($fila[4] ?? 0);
            $parcial     = $this->parseNumero($fila[5] ?? 0);

            // Si no hay descripción o no hay módulo activo, saltar
            if (empty($descripcion) || !$this->moduloActual) {
                continue;
            }

            // Ignorar filas de totales
            if (str_contains(strtoupper($descripcion), 'TOTAL')) {
                continue;
            }

            // Calcular parcial si viene en 0 pero hay cantidad y unitario
            if ($parcial == 0 && $cantidad > 0 && $unitario > 0) {
                $parcial = $cantidad * $unitario;
            }

            ManoObraItem::create([
                'modulo_id'       => $this->moduloActual->id,
                'proyecto_id'     => $this->proyectoId,
                'numero'          => is_numeric($numero) ? (int)$numero : ++$this->itemOrden,
                'descripcion'     => $descripcion,
                'unidad'          => $unidad ?: 'glb',
                'cantidad'        => $cantidad,
                'precio_unitario' => $unitario,
                'parcial'         => $parcial,
                'orden'           => ++$this->itemOrden,
            ]);
        }
    }

    private function parseNumero($valor): float
{
    if (is_numeric($valor)) return (float) $valor;
    
    $valor = trim((string) $valor);
    if (empty($valor)) return 0.0;
    
    // Detectar formato europeo: 1.000,00 (punto=miles, coma=decimal)
    if (str_contains($valor, ',') && str_contains($valor, '.')) {
        $lastComma = strrpos($valor, ',');
        $lastDot = strrpos($valor, '.');
        
        if ($lastComma > $lastDot) {
            // Europeo: 1.000,00 → quitar puntos, coma a punto
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        } else {
            // US: 1,000.00 → quitar comas
            $valor = str_replace(',', '', $valor);
        }
    } elseif (str_contains($valor, ',')) {
        // Solo coma: si tiene 2 dígitos después, es decimal europeo
        $parts = explode(',', $valor);
        if (count($parts) === 2 && strlen($parts[1]) <= 2) {
            $valor = str_replace(',', '.', $valor);  // 1,00 → 1.00
        } else {
            $valor = str_replace(',', '', $valor);   // 1,000 → 1000
        }
    }
    
    return is_numeric($valor) ? (float) $valor : 0.0;
}
}