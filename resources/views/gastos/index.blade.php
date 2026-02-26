<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">
                    <x-back-button :href="route('proy', $proyecto)" label=""/>
                    Registro de Gastos
                </h2>
                <a href="{{ route('gastos.create', $proyecto) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    + Registrar Gasto
                </a>
            </div>

            @if($gastos->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500">No hay gastos generales registrados para este proyecto.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
                    <table class="min-w-[600px] w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Monto (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Comprobante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($gastos as $gasto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $gasto->fecha_gasto->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                            {{ $gasto->categoria }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $gasto->descripcion }}</td>
                                    <td class="px-6 py-4 font-semibold text-red-600 dark:text-red-400">
                                        {{ number_format($gasto->monto, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($gasto->comprobante)
                                            @if(pathinfo($gasto->comprobante, PATHINFO_EXTENSION) === 'pdf')
                                                <a href="{{ $gasto->comprobante }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver PDF</a>
                                            @else
                                                <a href="{{$gasto->comprobante }}" target="_blank"
                                                   class="text-blue-500 hover:underline">Ver imagen</a>
                                            @endif
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('gastos.edit', [$proyecto, $gasto]) }}" 
                                           class="text-yellow-500 hover:text-yellow-600 mr-3">
                                            ✏️
                                        </a>
                                        <form action="{{ route('gastos.destroy', [$proyecto, $gasto]) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este gasto?')">
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
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50 font-bold">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right">TOTAL GASTOS:</td>
                                <td class="px-6 py-3 text-red-700 dark:text-red-400">
                                    Bs {{ number_format($gastos->sum('monto'), 2) }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>