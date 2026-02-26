<x-app-layout>
    <div class="py-3">
        {{-- ✅ Mensajes de sesión aquí --}}
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900/30 dark:border-red-700 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ✅ Mensajes de sesión aquí --}}
            
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">

                    <h1 class="text-2xl font-semibold">
                        <a href="{{ url()->previous()  }}"
                        class="inline-flex items-center gap-2 px-4 py-2  
                                text-sm font-medium text-white
                                bg-gray-500 rounded-lg
                                hover:bg-gray-600 
                                transition-all duration-200 shadow-sm">
                            
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>

                            Volver
                    </a>
                    Comparación: Contrato vs Ejecución
                </h1>
                
            </div>

            @if($comparacion->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500">No hay materiales registrados en contrato ni en ejecución.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-[600px] w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th rowspan="2" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descripción</th>
                                    <th rowspan="2" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unidad</th>
                                    
                                    <th colspan="3" class="px-4 py-2 text-center text-xs font-medium text-blue-600 dark:text-blue-400 uppercase">Contrato</th>
                                    <th colspan="4" class="px-4 py-2 text-center text-xs font-medium text-green-600 dark:text-green-400 uppercase">Ejecución</th>
                                    <th colspan="3" class="px-4 py-2 text-center text-xs font-medium text-orange-600 dark:text-orange-400 uppercase">Diferencia</th>
                                </tr>
                                <tr>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Cant.</th>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">P. Unit.</th>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Total</th>
                                    
                                    <!-- Dentro de la fila de encabezado, en la sección de Ejecución -->
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300"># Compras</th>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Cant.</th>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">P. Unit.</th>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Total</th>
                                    
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Cant.</th>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">P. Unit.</th>
                                    <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($comparacion as $item)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $item['descripcion'] }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $item['unidad'] }}</td>

                                        <!-- Contrato -->
                                        <td class="px-2 py-3 text-right">{{ number_format($item['contrato']['cantidad'], 2) }}</td>
                                        <td class="px-2 py-3 text-right">{{ number_format($item['contrato']['precio'], 2) }}</td>
                                        <td class="px-2 py-3 text-right font-semibold text-blue-600 dark:text-blue-400">
                                            {{ number_format($item['contrato']['total'], 2) }}
                                        </td>

                                        <!-- Ejecución -->
                                        <!-- Dentro del <tr>, en la sección de Ejecución -->
                                        <td class="px-2 py-3 text-center text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item['ejecucion']['compras'] }}
                                        </td>
                                        <td class="px-2 py-3 text-right">{{ number_format($item['ejecucion']['cantidad'], 2) }}</td>
                                        <td class="px-2 py-3 text-right">{{ number_format($item['ejecucion']['precio'], 2) }}</td>
                                        <td class="px-2 py-3 text-right font-semibold text-purple-700 dark:text-green-400">
                                            {{ number_format($item['ejecucion']['total'], 2) }}
                                        </td>

                                        <!-- Diferencias -->
                                        <td class="px-2 py-3 text-right {{ $item['diferencias']['cantidad'] > 0 ?  'text-green-500':'text-red-500'  }}">
                                            {{ number_format($item['diferencias']['cantidad'], 2) }}
                                        </td>
                                        <td class="px-2 py-3 text-right {{ $item['diferencias']['precio'] > 0 ? 'text-green-500':'text-red-500' }}">
                                            {{ number_format($item['diferencias']['precio'], 2) }}
                                        </td>
                                        <td class="px-2 py-3 text-right font-semibold {{ $item['diferencias']['total'] > 0 ? 'text-green-500':'text-red-500' }}">
                                            {{ number_format($item['diferencias']['total'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700 font-bold">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-right">TOTAL GENERAL</td>
                                    <td class="px-2 py-3 text-right"></td>
                                    <td></td>
                                    <td class="px-2 py-3 text-right text-blue-700 dark:text-blue-400">
                                        Bs {{ number_format($comparacion->sum('contrato.total'), 2) }}
                                    </td>
                                    <td></td>
                                    <td class="px-2 py-3 text-right"></td>
                                    <td></td>
                                    <td class="px-2 py-3 text-right text-purple-700 dark:text-purple-700">
                                        Bs {{ number_format($comparacion->sum('ejecucion.total'), 2) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td class="px-2 py-3 text-right {{ $comparacion->sum('diferencias.total') > 0 ? 'text-green-600' :'text-red-600'   }}">
                                        Bs {{ number_format($comparacion->sum('diferencias.total'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Leyenda -->
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm">
                        <p class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span> Ahorro (ejecución ≤ contrato)
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span> Sobre-costeo (ejecución > contrato)
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>