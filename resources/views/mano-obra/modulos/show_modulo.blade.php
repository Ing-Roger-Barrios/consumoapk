<x-app-layout>
    <div class="py-3">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-3">
                    <x-back-button :href="route('mano.obra.modulos.index', $proyecto)" label="" />
                    <div>
                        <span class="inline-block bg-blue-100 text-blue-700 font-bold text-sm px-2 py-1 rounded-lg mr-2">{{ $modulo->codigo }}</span>
                        <span class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $modulo->nombre }}</span>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $proyecto->nombre }}</p>
                    </div>
                </div>
                <a href="{{ route('mano.obra.modulos.create_item', [$proyecto, $modulo]) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-2 rounded-lg">
                    + Ítem
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            {{-- RESUMEN DEL MÓDULO --}}
            @php $pct = $modulo->porcentaje_avance; @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-5">
                <div class="grid grid-cols-3 gap-4 mb-3">
                    <div>
                        <p class="text-xs text-gray-400">Presupuestado</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            Bs {{ number_format($modulo->total_presupuestado, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Ejecutado</p>
                        <p class="text-xl font-bold text-emerald-600">
                            Bs {{ number_format($modulo->total_pagado, 2) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Pendiente</p>
                        <p class="text-xl font-bold text-orange-500">
                            Bs {{ number_format($modulo->total_presupuestado - $modulo->total_pagado, 2) }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                        <div class="{{ $pct >= 100 ? 'bg-green-500' : 'bg-blue-500' }} h-3 rounded-full transition-all"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-sm font-bold text-gray-600 w-12 text-right">{{ $pct }}%</span>
                </div>
            </div>

            {{-- ASIGNACIÓN DE MÓDULO COMPLETO --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-gray-700 dark:text-gray-200">
                        🏗️ Asignación del Módulo Completo
                    </h2>
                    <a href="{{ route('mano.obra.item.create_asignacion', $proyecto) }}?modulo_id={{ $modulo->id }}"
                       class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg">
                        + Asignar contratista
                    </a>
                </div>

                @php
                    $asignacionesModulo = $modulo->asignaciones()->with(['trabajador', 'avances'])->get();
                @endphp

                @if($asignacionesModulo->isEmpty())
                    <p class="text-sm text-gray-400 italic">Módulo no asignado a ningún contratista.</p>
                @else
                    <div class="space-y-2">
                        @foreach($asignacionesModulo as $asig)
                        <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                            <div>
                                <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">{{ $asig->trabajador->nombre }}</p>
                                <p class="text-xs text-gray-400">
                                    Monto acordado: Bs {{ number_format($asig->monto_acordado, 2) }} ·
                                    Pagado: Bs {{ number_format($asig->monto_pagado, 2) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                @php $ap = $asig->porcentaje_total; @endphp
                                <span class="text-xs font-bold {{ $ap >= 100 ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $ap }}%
                                </span>
                                @if($ap < 100)
                                <a href="{{ route('mano.obra.item.create_avance', [$proyecto, $asig]) }}"
                                   class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 px-2 py-1 rounded-lg">
                                    + Avance
                                </a>
                                @else
                                <span class="text-xs text-green-600 font-semibold">✅</span>
                                @endif
                                <a href="{{ route('mano.obra.item.show', [$proyecto, $asig]) }}"
                                   class="text-xs text-gray-500 hover:underline">
                                    Historial
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- TABLA DE ÍTEMS --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-700 dark:text-gray-200">
                        📋 Ítems del Módulo ({{ $modulo->items->count() }})
                    </h2>
                </div>

                @if($modulo->items->isEmpty())
                    <div class="p-8 text-center text-gray-400 text-sm">No hay ítems registrados.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-[700px] w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">N°</th>
                                <th class="px-4 py-3 text-left">Descripción</th>
                                <th class="px-3 py-3 text-center">Und.</th>
                                <th class="px-3 py-3 text-right">Cant.</th>
                                <th class="px-3 py-3 text-right">P. Unit. (Bs)</th>
                                <th class="px-3 py-3 text-right">Parcial (Bs)</th>
                                <th class="px-3 py-3 text-center">Avance</th>
                                <th class="px-3 py-3 text-center">Asignar</th>
                                <th class="px-3 py-3 text-center">Acc.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($modulo->items as $item)
                            @php
                                $ip = $item->porcentaje_avance;
                                $ic = $ip >= 100 ? 'bg-green-500' : ($ip > 0 ? 'bg-blue-400' : 'bg-gray-200');
                                $asigItem = $item->asignaciones->first();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20">
                                <td class="px-4 py-3 text-gray-400 font-medium">{{ $item->numero }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $item->descripcion }}</td>
                                <td class="px-3 py-3 text-center text-gray-500">{{ $item->unidad }}</td>
                                <td class="px-3 py-3 text-right text-gray-600">{{ number_format($item->cantidad, 2) }}</td>
                                <td class="px-3 py-3 text-right text-gray-600">{{ number_format($item->precio_unitario, 2) }}</td>
                                <td class="px-3 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">
                                    {{ number_format($item->parcial, 2) }}
                                </td>
                                <td class="px-3 py-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $ic }} h-2 rounded-full" style="width:{{ $ip }}%"></div>
                                    </div>
                                    <p class="text-center text-xs text-gray-400 mt-0.5">{{ $ip }}%</p>
                                </td>
                                <td class="px-3 py-3 text-center text-xs">
                                    @if($modulo->asignacionModulo)
                                        <span class="text-emerald-600 font-medium">{{ $item->trabajador_asignado->nombre  }}</span>
                                    @else
                                        <a href="{{ route('mano.obra.item.create_asignacion', $proyecto) }}?item_id={{ $item->id }}"
                                           class="text-blue-500 hover:underline">Asignar</a>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <form action="{{ route('mano.obra.modulos.destroy_item', [$proyecto, $item]) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar este ítem?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700/30 font-bold">
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right text-gray-600">SUBTOTAL:</td>
                                <td class="px-3 py-3 text-right text-gray-800 dark:text-gray-100">
                                    Bs {{ number_format($modulo->total_presupuestado, 2) }}
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>