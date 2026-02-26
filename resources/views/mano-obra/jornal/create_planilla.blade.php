<x-app-layout>
    <div class="py-3">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-4 px-2">
                <x-back-button :href="route('jornal.planillas', $proyecto)" label="" />
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Nueva Planilla Semanal</h1>
                    <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('jornal.store_planilla', $proyecto) }}" method="POST" id="form-planilla">
                @csrf

                {{-- SEMANA --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Semana inicio (Lunes) *</label>
                            <input type="date" name="semana_inicio" value="{{ old('semana_inicio', $lunes) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Semana fin (Sábado) *</label>
                            <input type="date" name="semana_fin" value="{{ old('semana_fin', $sabado) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Observaciones</label>
                            <input type="text" name="observaciones" value="{{ old('observaciones') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                   placeholder="Opcional">
                        </div>
                    </div>
                </div>

                {{-- TABLA DE ASISTENCIA --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-x-auto mb-4">
                    <table class="min-w-[900px] w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-40">Trabajador</th>
                                @foreach(['Lun','Mar','Mié','Jue','Vie','Sáb'] as $dia)
                                <th class="px-2 py-3 text-center text-xs font-semibold text-gray-500 uppercase w-20">
                                    {{ $dia }}
                                    <div class="text-gray-400 font-normal normal-case">días / hs.ext</div>
                                </th>
                                @endforeach
                                <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Descuentos</th>
                                <th class="px-3 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($trabajadores as $t)
                            @php $tid = $t->id; @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50" data-salario="{{ $t->salario_dia }}" data-hora-extra="{{ $t->hora_extra }}" data-tid="{{ $tid }}">
                                <td class="px-3 py-3">
                                    <p class="font-semibold text-gray-800 dark:text-gray-100 text-xs">{{ $t->nombre }}</p>
                                    <p class="text-gray-400 text-xs">{{ $t->cargo }}</p>
                                    <p class="text-blue-500 text-xs font-medium">Bs {{ number_format($t->salario_dia, 2) }}/día</p>
                                </td>

                                @foreach(['lunes','martes','miercoles','jueves','viernes','sabado'] as $dia)
                                <td class="px-1 py-2 text-center">
                                    <select name="trabajadores[{{ $tid }}][{{ $dia }}]"
                                            class="dia-select w-16 border border-gray-300 rounded px-1 py-1 text-xs text-center"
                                            data-tid="{{ $tid }}">
                                        <option value="0">—</option>
                                        <option value="0.5">½</option>
                                        <option value="1" selected>1</option>
                                    </select>
                                    <input type="number" name="trabajadores[{{ $tid }}][hs_extra_{{ $dia }}]"
                                           class="hs-input mt-1 w-16 border border-gray-300 rounded px-1 py-1 text-xs text-center"
                                           placeholder="hs" step="0.5" min="0" value="0"
                                           data-tid="{{ $tid }}">
                                </td>
                                @endforeach

                                <td class="px-2 py-2">
                                    <input type="number" name="trabajadores[{{ $tid }}][descuento_anticipo]"
                                           class="desc-input w-full border border-gray-300 rounded px-2 py-1 text-xs mb-1"
                                           placeholder="Anticipo" step="0.01" min="0" value="0"
                                           data-tid="{{ $tid }}">
                                    <input type="number" name="trabajadores[{{ $tid }}][descuento_otros]"
                                           class="desc-input w-full border border-gray-300 rounded px-2 py-1 text-xs mb-1"
                                           placeholder="Otros desc." step="0.01" min="0" value="0"
                                           data-tid="{{ $tid }}">
                                    <input type="text" name="trabajadores[{{ $tid }}][descuento_notas]"
                                           class="w-full border border-gray-300 rounded px-2 py-1 text-xs"
                                           placeholder="Nota desc.">
                                </td>

                                <td class="px-3 py-2 text-right">
                                    <p class="font-bold text-gray-800 dark:text-gray-100 total-bruto" data-tid="{{ $tid }}">Bs 0.00</p>
                                    <p class="text-xs text-red-500 total-desc" data-tid="{{ $tid }}">- Bs 0.00</p>
                                    <p class="text-sm font-bold text-green-600 total-neto" data-tid="{{ $tid }}">= Bs 0.00</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-300">
                            <tr>
                                <td colspan="8" class="px-3 py-3 text-right font-bold text-gray-700 dark:text-gray-200">
                                    TOTAL PLANILLA
                                </td>
                                <td class="px-3 py-3 text-right font-bold text-green-700 text-lg" id="total-planilla">
                                    Bs 0.00
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('jornal.planillas', $proyecto) }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">
                        Guardar Planilla
                    </button>
                </div>

            </form>
        </div>
    </div>

<script>
function calcularFila(tid) {
    const fila = document.querySelector(`tr[data-tid="${tid}"]`);
    const salarioDia  = parseFloat(fila.dataset.salario)    || 0;
    const valorHsExt  = parseFloat(fila.dataset.horaExtra)  || 0;

    let totalDias = 0;
    fila.querySelectorAll('.dia-select').forEach(s => totalDias += parseFloat(s.value) || 0);

    let totalHsExt = 0;
    fila.querySelectorAll('.hs-input').forEach(i => totalHsExt += parseFloat(i.value) || 0);

    let totalDesc = 0;
    fila.querySelectorAll('.desc-input').forEach(i => totalDesc += parseFloat(i.value) || 0);

    const bruto = (totalDias * salarioDia) + (totalHsExt * valorHsExt);
    const neto  = bruto - totalDesc;

    document.querySelector(`.total-bruto[data-tid="${tid}"]`).textContent = `Bs ${bruto.toFixed(2)}`;
    document.querySelector(`.total-desc[data-tid="${tid}"]`).textContent  = `- Bs ${totalDesc.toFixed(2)}`;
    document.querySelector(`.total-neto[data-tid="${tid}"]`).textContent  = `= Bs ${neto.toFixed(2)}`;

    // Recalcular total general
    let totalPlanilla = 0;
    document.querySelectorAll('.total-neto').forEach(el => {
        totalPlanilla += parseFloat(el.textContent.replace('= Bs ', '')) || 0;
    });
    document.getElementById('total-planilla').textContent = `Bs ${totalPlanilla.toFixed(2)}`;
}

// Escuchar cambios en toda la tabla
document.querySelectorAll('.dia-select, .hs-input, .desc-input').forEach(el => {
    el.addEventListener('change', () => calcularFila(el.dataset.tid));
    el.addEventListener('input',  () => calcularFila(el.dataset.tid));
});

// Calcular al cargar
document.querySelectorAll('tr[data-tid]').forEach(fila => calcularFila(fila.dataset.tid));
</script>
</x-app-layout>