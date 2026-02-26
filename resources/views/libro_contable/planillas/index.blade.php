<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
     
        <div class="flex items-center justify-between p-3">
            <div>
                <a href="{{ route('libro.index', $proyecto) }}" class="text-sm text-blue-600 hover:underline">
                    ← Libro Contable
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mt-1">
                    Planillas de Pago
                </h2>
                <p class="text-gray-500 text-sm">{{ $proyecto->nombre }}</p>
            </div>
            <a href="{{ route('libro.planillas.create', $proyecto) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                + Nueva Planilla
            </a>
        </div>
     

    <div class=" mx-auto px-3 py-3">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            @if($planillas->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <p class="text-lg">No hay planillas registradas</p>
                    <p class="text-sm mt-1">Registra el primer pago del cliente para comenzar.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-3 font-medium">N° Planilla</th>
                            <th class="px-4 py-3 font-medium">Concepto</th>
                            <th class="px-4 py-3 font-medium">Fecha</th>
                            <th class="px-4 py-3 font-medium text-right">Monto</th>
                            <th class="px-4 py-3 font-medium">Comprobante</th>
                            <th class="px-4 py-3 font-medium text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($planillas as $planilla)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $planilla->numero_planilla }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $planilla->concepto }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $planilla->fecha_pago->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-green-700">
                                Bs. {{ number_format($planilla->monto, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                @if($planilla->comprobante)
                                    <a href="{{ $planilla->comprobante }}" target="_blank"
                                       class="text-blue-600 hover:underline text-xs">Ver</a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('libro.planillas.edit', [$proyecto, $planilla]) }}"
                                   class="text-blue-600 hover:text-blue-800 mr-3 text-xs font-medium">Editar</a>
                                <form action="{{ route('libro.planillas.destroy', [$proyecto, $planilla]) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('¿Eliminar esta planilla?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-4 py-3 font-bold text-gray-700">Total Recibido</td>
                            <td class="px-4 py-3 text-right font-bold text-green-700 text-base">
                                Bs. {{ number_format($total, 2) }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>
    </div>
</x-app-layout>