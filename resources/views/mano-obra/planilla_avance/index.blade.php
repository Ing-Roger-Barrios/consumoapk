<x-app-layout>
<div class="py-4">
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                Planillas de Avance
            </h1>
            <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
        </div>

        <a href="{{ route('planilla_avance.create', $proyecto) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Nueva Planilla
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr class="text-xs uppercase text-gray-500">
                    <th class="px-4 py-3 text-left">Semana</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Estado</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($planillas as $planilla)
                <tr>
                    <td class="px-4 py-3">
                        {{ $planilla->semana_inicio->format('d/m/Y') }}
                        -
                        {{ $planilla->semana_fin->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold">
                        Bs {{ number_format($planilla->total_pagar, 2) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($planilla->archivo_constancia)
                            <span class="text-green-600 font-semibold">
                                Pagada
                            </span>
                        @else
                            <span class="text-yellow-600 font-semibold">
                                Pendiente
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('planilla_avance.show', $planilla) }}"
                           class="text-blue-600 hover:underline">
                            Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                        No hay planillas registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</div>
</x-app-layout>