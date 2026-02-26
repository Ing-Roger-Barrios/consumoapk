<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    
        <div class="flex items-center justify-between p-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    📒 Libro Contable
                </h2>
                <p class="text-gray-500 text-sm mt-1">{{ $proyecto->nombre }} · {{ $proyecto->cliente }}</p>
            </div>
            <a href="{{ route('libro.planillas.index', $proyecto) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                + Registrar Ingreso
            </a>
        </div>
   

    <div class=" mx-auto px-3 py-3">

        {{-- TARJETAS DE RESUMEN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                <p class="text-sm text-green-600 font-medium mb-1">Total Ingresos</p>
                <p class="text-2xl font-bold text-green-700">Bs. {{ number_format($total_ingresos, 2) }}</p>
                <p class="text-xs text-green-500 mt-1">{{ $ingresos['planillas']->count() }} planilla(s) de pago</p>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                <p class="text-sm text-red-600 font-medium mb-1">Total Egresos</p>
                <p class="text-2xl font-bold text-red-700">Bs. {{ number_format($total_egresos, 2) }}</p>
                <p class="text-xs text-red-500 mt-1">Costos consolidados de ejecución</p>
            </div>

            <div class="{{ $saldo >= 0 ? 'bg-blue-50 border-blue-200' : 'bg-orange-50 border-orange-200' }} border rounded-xl p-5">
                <p class="text-sm {{ $saldo >= 0 ? 'text-blue-600' : 'text-orange-600' }} font-medium mb-1">Saldo Disponible</p>
                <p class="text-2xl font-bold {{ $saldo >= 0 ? 'text-blue-700' : 'text-orange-700' }}">Bs. {{ number_format($saldo, 2) }}</p>
                <p class="text-xs {{ $saldo >= 0 ? 'text-blue-500' : 'text-orange-500' }} mt-1">
                    {{ $saldo >= 0 ? 'Saldo positivo ✓' : '⚠ Saldo negativo' }}
                </p>
            </div>

        </div>

        {{-- COMPARACIÓN CONTRATO VS EJECUCIÓN --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-8">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">📊 Contrato vs Ejecución</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Presupuesto Contrato</h3>
                        @foreach($contrato['detalle'] as $item)
                        <div class="flex justify-between py-2 border-b border-gray-50 text-sm">
                            <span class="text-gray-600">{{ $item['concepto'] }}</span>
                            <span class="font-medium text-gray-800">Bs. {{ number_format($item['monto'], 2) }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between py-3 mt-1">
                            <span class="font-bold text-gray-700">Total Contrato</span>
                            <span class="font-bold text-gray-900">Bs. {{ number_format($contrato['total'], 2) }}</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Ejecución Real</h3>
                        @foreach($egresos['detalle'] as $item)
                        <div class="flex justify-between py-2 border-b border-gray-50 text-sm">
                            <span class="text-gray-600">{{ $item['concepto'] }}</span>
                            <span class="font-medium text-gray-800">Bs. {{ number_format($item['monto'], 2) }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between py-3 mt-1">
                            <span class="font-bold text-gray-700">Total Ejecución</span>
                            <span class="font-bold text-gray-900">Bs. {{ number_format($total_egresos, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Barra de progreso --}}
                <div class="mt-6">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Porcentaje ejecutado del presupuesto</span>
                        <span class="font-semibold">{{ $porcentaje_ejecucion }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $porcentaje_ejecucion > 100 ? 'bg-red-500' : 'bg-blue-500' }}"
                             style="width: {{ min($porcentaje_ejecucion, 100) }}%"></div>
                    </div>
                    @if($porcentaje_ejecucion > 100)
                        <p class="text-xs text-red-500 mt-1">⚠ La ejecución superó el presupuesto del contrato.</p>
                    @endif
                </div>

                {{-- Diferencia --}}
                @php $diferencia = $contrato['total'] - $total_egresos; @endphp
                <div class="mt-4 p-4 rounded-lg {{ $diferencia >= 0 ? 'bg-green-50' : 'bg-red-50' }}">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold {{ $diferencia >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            {{ $diferencia >= 0 ? '✓ Ahorro vs Contrato' : '⚠ Sobrecosto vs Contrato' }}
                        </span>
                        <span class="font-bold text-lg {{ $diferencia >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            Bs. {{ number_format(abs($diferencia), 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- PLANILLAS DE PAGO --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">💰 Planillas de Pago Recibidas</h2>
                <a href="{{ route('libro.planillas.index', $proyecto) }}" class="text-blue-600 text-sm hover:underline">
                    Ver todas →
                </a>
            </div>
            <div class="p-6">
                @if($ingresos['planillas']->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-4">No hay planillas de pago registradas aún.</p>
                @else
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="pb-2 font-medium">Planilla</th>
                                <th class="pb-2 font-medium">Concepto</th>
                                <th class="pb-2 font-medium">Fecha</th>
                                <th class="pb-2 font-medium text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ingresos['planillas'] as $planilla)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-2 font-medium text-gray-700">{{ $planilla->numero_planilla }}</td>
                                <td class="py-2 text-gray-600">{{ $planilla->concepto }}</td>
                                <td class="py-2 text-gray-500">{{ $planilla->fecha_pago->format('d/m/Y') }}</td>
                                <td class="py-2 text-right font-semibold text-green-700">
                                    Bs. {{ number_format($planilla->monto, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-3 font-bold text-gray-700">Total Recibido</td>
                                <td class="pt-3 text-right font-bold text-green-700">
                                    Bs. {{ number_format($total_ingresos, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        </div>

    </div>
    </div>
</x-app-layout>