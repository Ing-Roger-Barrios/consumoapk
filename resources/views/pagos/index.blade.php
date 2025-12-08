<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center mb-6">
                <a href="{{ route('subcontratos.index', $subcontrato->proyecto) }}" 
                   class="text-blue-600 hover:text-blue-800 mx-4">
                    ← Volver 
                </a>
                <h1 class="text-2xl font-semibold">Pagos - {{ $subcontrato->nombre }}</h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Monto Acordado</p>
                        <p class="text-xl font-bold text-blue-700 dark:text-blue-300">
                            Bs {{ number_format($subcontrato->monto_acordado, 2) }}
                        </p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Total Pagado</p>
                        <p class="text-xl font-bold text-green-700 dark:text-green-300">
                            Bs {{ number_format($subcontrato->monto_pagado, 2) }}
                        </p>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Saldo Pendiente</p>
                        <p class="text-xl font-bold text-orange-700 dark:text-orange-300">
                            Bs {{ number_format($subcontrato->saldo_pendiente, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Historial de Pagos</h2>
                <a href="{{ route('pagos.create', $subcontrato) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    + Registrar Pago
                </a>
            </div>

            @if($pagos->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500">No hay pagos registrados para este subcontrato.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
                    <table class="min-w-[600px] w-full text-left dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Comprobante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Notas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pagos as $pago)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600 dark:text-green-400">
                                        {{ number_format($pago->monto_pagado, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pago->comprobante)
                                            @if(pathinfo($pago->comprobante, PATHINFO_EXTENSION) === 'pdf')
                                                <a href="{{ asset('storage/' . $pago->comprobante) }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver PDF</a>
                                            @else
                                                <a href="{{ asset('storage/' . $pago->comprobante) }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver imagen</a>
                                            @endif
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $pago->notas ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('pagos.edit', [$subcontrato, $pago]) }}" 
                                           class="text-yellow-500 hover:text-yellow-600 mr-3">
                                            ✏️
                                        </a>
                                        <form action="{{ route('pagos.destroy', [$subcontrato, $pago]) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este pago?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600">
                                                ❌
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>