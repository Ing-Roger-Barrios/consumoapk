<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center mb-6">
                
                <h1 class="text-2xl font-semibold">Equipo y Maquinaria Ejecutados - {{ $proyecto->nombre }}</h1>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Registro de Equipos Ejecutados</h2>
                <a href="{{ route('equipo.ejecucion.create', $proyecto) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Registrar Equipo
                </a>
            </div>

            @if($equipos->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 mb-4">No hay equipos y maquinaria ejecutados registrados para este proyecto.</p>
                    <a href="{{ route('equipo.ejecucion.create', $proyecto) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Registrar primer equipo
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
                    <table class="min-w-[600px] w-full text-left dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">P. Unit. (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Comprobante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($equipos as $equipo)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 font-medium">{{ $equipo->descripcion }}</td>
                                    <td class="px-6 py-4">{{ $equipo->unidad }}</td>
                                    <td class="px-6 py-4">{{ number_format($equipo->cantidad, 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($equipo->precio_unit, 2) }}</td>
                                    <td class="px-6 py-4 font-semibold text-green-600 dark:text-green-400">
                                        {{ number_format($equipo->total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($equipo->comprobante)
                                            @if(pathinfo($equipo->comprobante, PATHINFO_EXTENSION) === 'pdf')
                                                <a href="{{ $equipo->comprobante }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver PDF</a>
                                            @else
                                                <a href="{{ $equipo->comprobante }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver imagen</a>
                                            @endif
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('equipo.ejecucion.edit', [$proyecto, $equipo]) }}" 
                                               class="text-yellow-500 hover:text-yellow-600">
                                                Editar
                                            </a>
                                            <form action="{{ route('equipo.ejecucion.destroy', [$proyecto, $equipo]) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este equipo ejecutado?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50 font-bold">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right">TOTAL EJECUTADO:</td>
                                <td class="px-6 py-3 text-green-700 dark:text-green-400">
                                    Bs {{ number_format($equipos->sum('total'), 2) }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Resumen estadístico -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Total Equipos</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                            {{ $equipos->count() }}
                        </p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Monto Total</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">
                            Bs {{ number_format($equipos->sum('total'), 2) }}
                        </p>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Porcentaje del Proyecto</p>
                        <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                            {{ $proyecto->monto > 0 ? number_format(($equipos->sum('total') / $proyecto->monto) * 100, 2) : 0 }}%
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>