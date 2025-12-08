<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center mb-6">
                <a href="{{ route('proy', $proyecto) }}" 
                   class="text-blue-600 hover:text-blue-800 mx-4">
                    ← Volver 
                </a>
                <h1 class="text-2xl font-semibold">Facturas IVA - {{ $proyecto->nombre }}</h1>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Registro de Facturas IVA</h2>
                <a href="{{ route('iva.create', $proyecto) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Registrar Factura
                </a>
            </div>

            @if($facturas->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 mb-4">No hay facturas IVA registradas para este proyecto.</p>
                    <a href="{{ route('iva.create', $proyecto) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Registrar primera factura
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
                    <table class="min-w-[600px] w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">N° Factura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto Factura</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tasa IVA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto IVA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Comprobante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($facturas as $factura)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $factura->fecha_factura->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $factura->numero_factura }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        Bs {{ number_format($factura->monto_factura, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ number_format($factura->porcentaje_iva, 2) }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-purple-600 dark:text-purple-400">
                                        Bs {{ number_format($factura->monto_iva, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($factura->comprobante)
                                            @if(pathinfo($factura->comprobante, PATHINFO_EXTENSION) === 'pdf')
                                                <a href="{{ asset('storage/' . $factura->comprobante) }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver PDF</a>
                                            @else
                                                <a href="{{ asset('storage/' . $factura->comprobante) }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver imagen</a>
                                            @endif
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- <a href="{{ route('iva.edit', [$proyecto, $factura]) }}" 
                                               class="text-yellow-500 hover:text-yellow-600">
                                                ✏️
                                            </a>
                                            <form action="{{ route('iva.destroy', [$proyecto, $factura]) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar esta factura IVA?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600">
                                                    ❌
                                                </button>
                                            </form>-->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50 font-bold">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-gray-700 dark:text-gray-300">TOTAL IVA:</td>
                                <td class="px-6 py-3 text-purple-700 dark:text-purple-400">
                                    Bs {{ number_format($facturas->sum('monto_iva'), 2) }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Resumen estadístico 
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Total Facturas</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                            {{ $facturas->count() }}
                        </p>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Monto Total IVA</p>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                            Bs {{ number_format($facturas->sum('monto_iva'), 2) }}
                        </p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Porcentaje del Proyecto</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">
                            {{ $proyecto->monto > 0 ? number_format(($facturas->sum('monto_iva') / $proyecto->monto) * 100, 2) : 0 }}%
                        </p>
                    </div>
                </div>-->
            @endif
        </div>
    </div>
</x-app-layout>