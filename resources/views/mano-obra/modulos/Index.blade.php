<x-app-layout>
    <div class="py-3">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- ENCABEZADO --}}
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-3">
                    <x-back-button :href="route('mano.obra.hub', $proyecto)" label="" />
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">📐 Módulos de Obra</h1>
                        <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="document.getElementById('import-form').classList.toggle('hidden')"
                            class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-3 py-2 rounded-lg">
                        📤 Importar Excel
                    </button>
                    <a href="{{ route('mano.obra.modulos.create_modulo', $proyecto) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-2 rounded-lg">
                        + Módulo
                    </a>
                </div>
            </div>

            {{-- FORM IMPORTAR --}}
            <div id="import-form" class="hidden mb-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200">
                <h3 class="font-semibold text-sm mb-3 text-gray-700 dark:text-gray-200">
                    Importar desde Excel
                    <span class="font-normal text-gray-400 ml-2">
                        — Formato: Nº | Descripción | Und. | Cantidad | Unitario | Parcial
                    </span>
                </h3>
                <form action="{{ route('mano.obra.modulos.import', $proyecto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <input type="file" name="archivo_excel" accept=".xlsx,.xls,.csv" required
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4 file:rounded file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                            <p class="text-xs text-gray-400 mt-1">
                                Las filas que empiezan con <strong>></strong> se detectan como módulos (ej: <code>> M01 - OBRAS PRELIMINARES</code>).
                                ⚠ Esto reemplazará todos los módulos e ítems existentes del proyecto.
                            </p>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700">
                            Importar
                        </button>
                        <button type="button"
                                onclick="document.getElementById('import-form').classList.add('hidden')"
                                class="px-3 py-2 bg-gray-400 text-white rounded-lg text-sm hover:bg-gray-500">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>

            {{-- MENSAJES --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            @if($modulos->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-10 text-center text-gray-400">
                    <p class="text-2xl mb-2">📋</p>
                    <p class="font-medium">No hay módulos registrados</p>
                    <p class="text-sm mt-1">Importa el Excel del presupuesto o crea un módulo manualmente.</p>
                </div>
            @else

                {{-- RESUMEN GLOBAL --}}
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                        <p class="text-xs text-gray-400 mb-1">Módulos</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $modulos->count() }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                        <p class="text-xs text-gray-400 mb-1">Total Presupuestado</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100">Bs {{ number_format($totalPresupuestado, 2) }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 text-center">
                        <p class="text-xs text-gray-400 mb-1">Total Ejecutado</p>
                        <p class="text-lg font-bold text-emerald-600">Bs {{ number_format($totalPagado, 2) }}</p>
                    </div>
                </div>

                {{-- LISTA DE MÓDULOS (acordeón) --}}
                <div class="space-y-3">
                    @foreach($modulos as $modulo)
                    @php
                        $pct = $modulo->porcentaje_avance;
                        $barColor = $pct >= 100 ? 'bg-green-500' : ($pct > 0 ? 'bg-blue-500' : 'bg-gray-300');
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">

                        {{-- CABECERA DEL MÓDULO --}}
                        <div class="flex items-center justify-between px-5 py-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30"
                             onclick="toggleModulo('modulo-{{ $modulo->id }}')">
                            <div class="flex items-center gap-3">
                                <span class="inline-block bg-blue-100 text-blue-700 font-bold text-xs px-2 py-1 rounded-lg">
                                    {{ $modulo->codigo }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $modulo->nombre }}</p>
                                    <p class="text-xs text-gray-400">{{ $modulo->items->count() }} ítems</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-200">
                                        Bs {{ number_format($modulo->total_presupuestado, 2) }}
                                    </p>
                                    <p class="text-xs text-emerald-600">
                                        Ejec: Bs {{ number_format($modulo->total_pagado, 2) }}
                                    </p>
                                </div>
                                {{-- Mini barra --}}
                                <div class="w-20">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $barColor }} h-2 rounded-full" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <p class="text-xs text-center text-gray-500 mt-0.5">{{ $pct }}%</p>
                                </div>
                                {{-- Acciones --}}
                                <div class="flex gap-1" onclick="event.stopPropagation()">
                                    <a href="{{ route('mano.obra.modulos.show', [$proyecto, $modulo]) }}"
                                       class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 px-2 py-1 rounded-lg">
                                        Ver
                                    </a>
                                    <form action="{{ route('mano.obra.modulos.destroy_modulo', [$proyecto, $modulo]) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar módulo {{ $modulo->codigo }} y todos sus ítems?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-xs bg-red-50 hover:bg-red-100 text-red-500 px-2 py-1 rounded-lg">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                                <span class="text-gray-400 text-sm" id="arrow-{{ $modulo->id }}">▼</span>
                            </div>
                        </div>

                        {{-- ÍTEMS DEL MÓDULO (colapsable) --}}
                        <div id="modulo-{{ $modulo->id }}" class="hidden border-t border-gray-100 dark:border-gray-700">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr class="text-gray-500">
                                        <th class="px-4 py-2 text-left w-8">N°</th>
                                        <th class="px-4 py-2 text-left">Descripción</th>
                                        <th class="px-3 py-2 text-center">Und.</th>
                                        <th class="px-3 py-2 text-right">Cant.</th>
                                        <th class="px-3 py-2 text-right">P.Unit.</th>
                                        <th class="px-3 py-2 text-right">Parcial</th>
                                        <th class="px-3 py-2 text-center">Avance</th>
                                        <th class="px-3 py-2 text-center">Acc.</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                    @foreach($modulo->items as $item)
                                    @php
                                        $ip = $item->porcentaje_avance;
                                        $ic = $ip >= 100 ? 'bg-green-500' : ($ip > 0 ? 'bg-blue-400' : 'bg-gray-200');
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20">
                                        <td class="px-4 py-2 text-gray-400">{{ $item->numero }}</td>
                                        <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $item->descripcion }}</td>
                                        <td class="px-3 py-2 text-center text-gray-500">{{ $item->unidad }}</td>
                                        <td class="px-3 py-2 text-right text-gray-600">{{ number_format($item->cantidad, 2) }}</td>
                                        <td class="px-3 py-2 text-right text-gray-600">{{ number_format($item->precio_unitario, 2) }}</td>
                                        <td class="px-3 py-2 text-right font-medium text-gray-700">{{ number_format($item->parcial, 2) }}</td>
                                        <td class="px-3 py-2">
                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="{{ $ic }} h-1.5 rounded-full" style="width:{{ $ip }}%"></div>
                                            </div>
                                            <p class="text-center text-gray-400 mt-0.5">{{ $ip }}%</p>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <form action="{{ route('mano.obra.modulos.destroy_item', [$proyecto, $item]) }}"
                                                  method="POST" class="inline"
                                                  onsubmit="return confirm('¿Eliminar ítem?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600">✕</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    {{-- Agregar ítem --}}
                                    <tr class="bg-gray-50 dark:bg-gray-700/20">
                                        <td colspan="8" class="px-4 py-2">
                                            <a href="{{ route('mano.obra.modulos.create_item', [$proyecto, $modulo]) }}"
                                               class="text-blue-500 hover:underline text-xs">
                                                + Agregar ítem manualmente
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/30 font-bold">
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-right text-gray-600">Subtotal módulo:</td>
                                        <td class="px-3 py-2 text-right text-gray-800 dark:text-gray-100">
                                            Bs {{ number_format($modulo->total_presupuestado, 2) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- TOTAL GENERAL --}}
                <div class="mt-4 bg-gray-800 dark:bg-gray-900 rounded-xl p-4 text-white flex justify-between items-center">
                    <p class="font-semibold">TOTAL PRESUPUESTO MANO DE OBRA</p>
                    <p class="text-2xl font-bold">Bs {{ number_format($totalPresupuestado, 2) }}</p>
                </div>

            @endif
        </div>
    </div>

<script>
function toggleModulo(id) {
    const el = document.getElementById(id);
    const arrow = document.getElementById('arrow-' + id.replace('modulo-', ''));
    el.classList.toggle('hidden');
    if (arrow) arrow.textContent = el.classList.contains('hidden') ? '▼' : '▲';
}
</script>
</x-app-layout>