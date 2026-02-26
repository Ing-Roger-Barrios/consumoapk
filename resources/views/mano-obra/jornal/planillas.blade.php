{{-- ═══════════════════════════════════════════════════════════
     resources/views/mano_obra/jornal/planillas.blade.php
     ═══════════════════════════════════════════════════════════ --}}
<x-app-layout>
    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-3">
                    <x-back-button :href="route('mano.obra.hub', $proyecto)" label="" />
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">📋 Planillas Semanales</h1>
                        <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                    </div>
                </div>
                <a href="{{ route('jornal.create_planilla', $proyecto) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
                    + Nueva Planilla
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            @if($planillas->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center text-gray-400">
                    <p class="text-lg">No hay planillas registradas</p>
                    <a href="{{ route('jornal.create_planilla', $proyecto) }}" class="text-blue-500 hover:underline text-sm mt-2 inline-block">
                        Crear la primera planilla →
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($planillas as $planilla)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">
                                Semana {{ $planilla->semana_inicio->format('d/m') }} – {{ $planilla->semana_fin->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $planilla->detalles->count() }} trabajadores ·
                                Registrado por {{ $planilla->registradoPor?->name ?? '—' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="font-bold text-green-700 text-lg">
                                Bs {{ number_format($planilla->total_pagar, 2) }}
                            </p>
                            <a href="{{ route('jornal.show_planilla', [$proyecto, $planilla]) }}"
                               class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-sm px-3 py-1.5 rounded-lg">
                                Ver / Imprimir
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Total acumulado --}}
                <div class="mt-4 bg-gray-800 rounded-xl p-4 text-white flex justify-between items-center">
                    <p class="text-sm text-gray-400">Total pagado por jornal (todas las semanas)</p>
                    <p class="text-xl font-bold">
                        Bs {{ number_format($planillas->sum(fn($p) => $p->total_pagar), 2) }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>