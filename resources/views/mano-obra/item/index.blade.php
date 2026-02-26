<x-app-layout>
    <div class="py-3">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-3">
                    <x-back-button :href="route('mano.obra.hub', $proyecto)" label="" />
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Asignaciones por Ítem / Módulo</h1>
                        <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('mano.obra.modulos.index', $proyecto) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-2 rounded-lg">
                        📐 Ver Módulos
                    </a>
                    <a href="{{ route('mano.obra.item.create_asignacion', $proyecto) }}"
                       class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-3 py-2 rounded-lg">
                        + Nueva Asignación
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            @if($asignaciones->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center text-gray-400">
                    <p class="text-2xl mb-2">🏗️</p>
                    <p>No hay asignaciones registradas aún.</p>
                    <a href="{{ route('mano.obra.item.create_asignacion', $proyecto) }}"
                       class="inline-block mt-2 text-emerald-600 hover:underline text-sm">
                        Crear primera asignación →
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($asignaciones as $asig)
                    @php
                        $pct = $asig->porcentaje_total;
                        $barColor = $pct >= 100 ? 'bg-green-500' : ($pct > 60 ? 'bg-blue-500' : 'bg-yellow-400');
                        $esModulo = $asig->tipo_asignacion === 'modulo';
                        $titulo   = $esModulo
                            ? ($asig->modulo ? $asig->modulo->codigo.' - '.$asig->modulo->nombre : 'Módulo eliminado')
                            : ($asig->item   ? $asig->item->descripcion : 'Ítem eliminado');
                        $subtitulo = $esModulo ? 'Módulo completo' : ($asig->item ? $asig->item->modulo->codigo ?? '' : '');
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    @if($esModulo)
                                        <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2 py-0.5 rounded-full">MÓDULO</span>
                                    @else
                                        <span class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2 py-0.5 rounded-full">ÍTEM</span>
                                    @endif
                                    @if($subtitulo)
                                        <span class="text-xs text-gray-400">{{ $subtitulo }}</span>
                                    @endif
                                </div>
                                <p class="font-bold text-gray-800 dark:text-gray-100">{{ $titulo }}</p>
                                <p class="text-sm text-gray-500">
                                    Contratista: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $asig->trabajador->nombre }}</span>
                                    @if($asig->trabajador->cargo) · {{ $asig->trabajador->cargo }} @endif
                                </p>
                                @if($asig->notas)
                                    <p class="text-xs text-gray-400 mt-1 italic">{{ $asig->notas }}</p>
                                @endif
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <p class="text-xs text-gray-400">Monto acordado</p>
                                <p class="font-bold text-gray-800 dark:text-gray-100">Bs {{ number_format($asig->monto_acordado, 2) }}</p>
                                <p class="text-xs text-emerald-600 font-medium">Pagado: Bs {{ number_format($asig->monto_pagado, 2) }}</p>
                                <p class="text-xs text-orange-500">Pendiente: Bs {{ number_format($asig->monto_pendiente, 2) }}</p>
                            </div>
                        </div>

                        {{-- Barra de avance --}}
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Avance aprobado</span>
                                <span class="font-semibold">{{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="{{ $barColor }} h-2.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 flex-wrap">
                            <a href="{{ route('mano.obra.item.show', [$proyecto, $asig]) }}"
                               class="text-blue-600 text-sm hover:underline">Ver historial →</a>
                            @if($pct < 100)
                                <a href="{{ route('mano.obra.item.create_avance', [$proyecto, $asig]) }}"
                                   class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm px-3 py-1 rounded-lg font-medium">
                                    + Registrar Avance
                                </a>
                            @else
                                <span class="text-green-600 text-sm font-semibold">✅ Completado</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>