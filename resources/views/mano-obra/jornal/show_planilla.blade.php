<x-app-layout>
    <div class="py-3">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-3">
                    <x-back-button :href="route('jornal.planillas', $proyecto)" label="" />
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            Planilla Semana {{ $planilla->semana_inicio->format('d/m') }} – {{ $planilla->semana_fin->format('d/m/Y') }}
                        </h1>
                        <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                    </div>
                </div>
                <button onclick="window.print()"
                        class="bg-gray-700 hover:bg-gray-800 text-white text-sm px-4 py-2 rounded-lg">
                    🖨️ Imprimir
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-x-auto" id="planilla-print">

                {{-- ENCABEZADO IMPRIMIBLE --}}
                <div class="p-5 border-b border-gray-100 print:block">
                    <div class="flex justify-between">
                        <div>
                            <h2 class="font-bold text-lg">{{ $proyecto->nombre }}</h2>
                            <p class="text-sm text-gray-500">Cliente: {{ $proyecto->cliente }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">Planilla de Pago Semanal</p>
                            <p class="text-sm text-gray-500">
                                Del {{ $planilla->semana_inicio->format('d/m/Y') }}
                                al {{ $planilla->semana_fin->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <table class="min-w-[900px] w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="text-xs text-gray-500 uppercase">
                            <th class="px-4 py-3 text-left">Trabajador</th>
                            <th class="px-2 py-3 text-center">Lun</th>
                            <th class="px-2 py-3 text-center">Mar</th>
                            <th class="px-2 py-3 text-center">Mié</th>
                            <th class="px-2 py-3 text-center">Jue</th>
                            <th class="px-2 py-3 text-center">Vie</th>
                            <th class="px-2 py-3 text-center">Sáb</th>
                            <th class="px-2 py-3 text-center">Total Días</th>
                            <th class="px-2 py-3 text-center">HS Extra</th>
                            <th class="px-2 py-3 text-right">Bruto</th>
                            <th class="px-2 py-3 text-right">Desc.</th>
                            <th class="px-3 py-3 text-right font-bold">Neto</th>
                            <th class="px-3 py-3 text-center print:hidden">Firma</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($planilla->detalles as $detalle)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $detalle->trabajador->nombre }}</p>
                                <p class="text-xs text-gray-400">{{ $detalle->trabajador->cargo }}</p>
                                <p class="text-xs text-blue-500">Bs {{ number_format($detalle->salario_dia_snapshot, 2) }}/día</p>
                            </td>
                            @foreach(['lunes','martes','miercoles','jueves','viernes','sabado'] as $d)
                            <td class="px-2 py-3 text-center">
                                <span class="{{ $detalle->$d == 0 ? 'text-gray-300' : 'text-gray-700 font-medium' }}">
                                    {{ $detalle->$d == 0 ? '—' : $detalle->$d }}
                                </span>
                                @php $hsKey = "hs_extra_{$d}"; @endphp
                                @if($detalle->$hsKey > 0)
                                    <br><span class="text-xs text-orange-500">+{{ $detalle->$hsKey }}h</span>
                                @endif
                            </td>
                            @endforeach
                            <td class="px-2 py-3 text-center font-bold text-gray-800">{{ $detalle->total_dias }}</td>
                            <td class="px-2 py-3 text-center text-orange-600">{{ $detalle->total_hs_extra }}h</td>
                            <td class="px-2 py-3 text-right text-gray-700">Bs {{ number_format($detalle->total_bruto, 2) }}</td>
                            <td class="px-2 py-3 text-right text-red-500">
                                @if($detalle->total_descuentos > 0)
                                    - Bs {{ number_format($detalle->total_descuentos, 2) }}
                                    @if($detalle->descuento_notas)
                                        <br><span class="text-xs text-gray-400">{{ $detalle->descuento_notas }}</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-3 py-3 text-right font-bold text-green-700 text-base">
                                Bs {{ number_format($detalle->total_neto, 2) }}
                            </td>
                            {{-- Espacio para firma impresa --}}
                            <td class="px-3 py-3 print:border-b print:border-gray-400 print:w-32 print:hidden"></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-300">
                        <tr>
                            <td colspan="10" class="px-4 py-3 text-right font-bold text-gray-700">TOTAL A PAGAR:</td>
                            <td class="px-3 py-3 text-right font-bold text-green-700 text-xl">
                                Bs {{ number_format($planilla->total_pagar, 2) }}
                            </td>
                            <td class="print:hidden"></td>
                        </tr>
                    </tfoot>
                </table>

                @if($planilla->observaciones)
                <div class="p-4 border-t border-gray-100 text-sm text-gray-500">
                    <strong>Observaciones:</strong> {{ $planilla->observaciones }}
                </div>
                @endif
            </div>

            {{-- Eliminar --}}
            <div class="mt-4 flex justify-end print:hidden">
                <form action="{{ route('jornal.destroy_planilla', [$proyecto, $planilla]) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar esta planilla? Esta acción no se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 hover:text-red-700 text-sm">🗑 Eliminar planilla</button>
                </form>
            </div>

        </div>
    </div>

<style>
@media print {
    nav, header, .print\:hidden { display: none !important; }
    #planilla-print { box-shadow: none; border: none; }
    body { background: white; }
}
</style>
</x-app-layout>