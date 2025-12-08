<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Mano de Obra Pres. - {{ $proyecto->nombre }}</h1>
                <a href="{{ route('subcontratos.index', $proyecto) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-green-700">
                    Subcontrato
                </a>
                <a href="{{ route('mano.obra.contrato.create', $proyecto) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    + Nuevo Ítem
                </a>
            </div>

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if($manoObra->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500">No hay ítems de mano de obra registrados.</p>
                    <a href="{{ route('mano.obra.contrato.create', $proyecto) }}" 
                       class="mt-2 text-blue-500 hover:underline inline-block">
                        Registrar el primero
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
                    <table class="min-w-[600px] w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">P. Unit. (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($manoObra as $item)
                                <tr>
                                    <td class="px-6 py-4 font-medium">{{ $item->descripcion }}</td>
                                    <td class="px-6 py-4">{{ $item->unidad }}</td>
                                    <td class="px-6 py-4">{{ number_format($item->cantidad, 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($item->precio_unit, 2) }}</td>
                                    <td class="px-6 py-4 font-semibold text-green-600 dark:text-green-400">
                                        {{ number_format($item->monto_presupuestado, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('mano.obra.contrato.edit', [$proyecto, $item]) }}" 
                                           class="text-yellow-500 hover:text-yellow-600 mr-3">
                                            ✏️
                                        </a>
                                        <form action="{{ route('mano.obra.contrato.destroy', [$proyecto, $item]) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este ítem?')">
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
                                <td colspan="4" class="px-6 py-3 text-right">TOTAL PRESUPUESTADO:</td>
                                <td class="px-6 py-3 text-green-700 dark:text-green-400">
                                    Bs {{ number_format($manoObra->sum('monto_presupuestado'), 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>