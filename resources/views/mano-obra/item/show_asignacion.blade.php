<x-app-layout>
    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @php
                $esModulo  = $asignacion->tipo_asignacion === 'modulo';
                $titulo    = $esModulo
                    ? ($asignacion->modulo ? $asignacion->modulo->codigo.' - '.$asignacion->modulo->nombre : 'Módulo eliminado')
                    : ($asignacion->item   ? $asignacion->item->descripcion : 'Ítem eliminado');
                $unidad    = (!$esModulo && $asignacion->item) ? $asignacion->item->unidad : '—';
                $badgeClass = $esModulo ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700';
                $badgeText  = $esModulo ? 'MÓDULO COMPLETO' : 'ÍTEM';
            @endphp

            <div class="flex items-center gap-3 mb-4 px-2">
                <x-back-button :href="route('mano.obra.item.index', $proyecto)" label="" />
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Historial de Avances</h1>
                    <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            {{-- INFO DE LA ASIGNACIÓN --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase">Ítem / Módulo</p>
                        <span class="text-xs {{ $badgeClass }} font-semibold px-2 py-0.5 rounded-full">{{ $badgeText }}</span>
                        <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm mt-1">{{ $titulo }}</p>
                        <p class="text-xs text-gray-400">{{ $unidad }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase">Trabajador</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm">{{ $asignacion->trabajador->nombre }}</p>
                        <p class="text-xs text-gray-400">{{ $asignacion->trabajador->cargo }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase">Monto Acordado</p>
                        <p class="font-bold text-gray-800 dark:text-gray-100">Bs {{ number_format($asignacion->monto_acordado, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase">Pendiente</p>
                        <p class="font-bold {{ $asignacion->monto_pendiente > 0 ? 'text-orange-600' : 'text-green-600' }}">
                            Bs {{ number_format($asignacion->monto_pendiente, 2) }}
                        </p>
                    </div>
                </div>

                {{-- Barra de progreso --}}
                @php $pct = $asignacion->porcentaje_total; @endphp
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Avance total aprobado</span>
                        <span class="font-bold text-gray-700">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="{{ $pct >= 100 ? 'bg-green-500' : 'bg-blue-500' }} h-3 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-1">
                        <span class="text-gray-400">Pagado: Bs {{ number_format($asignacion->monto_pagado, 2) }}</span>
                        @if($pct >= 100)
                            <span class="text-green-600 font-semibold">Ítem completado</span>
                        @endif
                    </div>
                </div>

                @if($pct < 100)
                <div class="mt-4">
                    <a href="{{ route('mano.obra.item.create_avance', [$proyecto, $asignacion]) }}"
                       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                        + Registrar Nuevo Avance
                    </a>
                </div>
                @endif
            </div>

            {{-- HISTORIAL DE AVANCES --}}
            <h2 class="font-semibold text-gray-700 dark:text-gray-200 mb-3 px-1">
                Registro de Avances ({{ $asignacion->avances->count() }})
            </h2>

            @if($asignacion->avances->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center text-gray-400">
                    <p>No hay avances registrados aún.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($asignacion->avances->sortByDesc('fecha') as $avance)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $avance->porcentaje_avance }}% de avance
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $avance->fecha->format('d/m/Y') }}
                                    · Aprobado por: {{ $avance->aprobadoPor?->name ?? '—' }}
                                </p>
                                @if($avance->observaciones)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $avance->observaciones }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-700 text-lg">Bs {{ number_format($avance->monto_pagar, 2) }}</p>
                                <form action="{{ route('mano.obra.item.destroy_avance', [$proyecto, $avance]) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Eliminar este avance?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 mt-1">Eliminar</button>
                                </form>
                            </div>
                        </div>

                        {{-- FOTOS --}}
                        @if($avance->foto1 || $avance->foto2 || $avance->foto3)
                        <div class="grid grid-cols-3 gap-2 mt-3">
                            @foreach(['foto1','foto2','foto3'] as $foto)
                                @if($avance->$foto)
                                <a href="{{ $avance->$foto }}" target="_blank">
                                    <img src="{{ $avance->$foto }}" alt="Comprobante"
                                         class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-90 transition">
                                </a>
                                @endif
                            @endforeach
                        </div>
                        @else
                            <p class="text-xs text-gray-300 mt-2">Sin fotos adjuntas</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>