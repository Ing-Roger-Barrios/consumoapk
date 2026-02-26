<x-app-layout>
    <div class="py-3">
        @php
            $esModulo   = $asignacion->tipo_asignacion === 'modulo';
            $titulo     = $esModulo
                ? ($asignacion->modulo ? $asignacion->modulo->codigo.' - '.$asignacion->modulo->nombre : 'Módulo eliminado')
                : ($asignacion->item   ? $asignacion->item->descripcion : 'Ítem eliminado');
        @endphp

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-4 px-2">
                <x-back-button :href="route('mano.obra.item.index', $proyecto)" label="" />
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Registrar Avance</h1>
                    <p class="text-sm text-gray-500">{{ $titulo }}</p>
                </div>
            </div>

            {{-- INFO ASIGNACIÓN --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 rounded-xl p-4 mb-4 text-sm">
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <p class="text-xs text-blue-500 font-medium">Trabajador</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $asignacion->trabajador->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-500 font-medium">Monto Acordado</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">Bs {{ number_format($asignacion->monto_acordado, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-500 font-medium">Avance acumulado</p>
                        <p class="font-bold text-blue-700">{{ $porcentajeAcumulado }}% completado</p>
                    </div>
                </div>
                <div class="w-full bg-blue-200 rounded-full h-2 mt-3">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $porcentajeAcumulado }}%"></div>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- ═══════════════════════════════════════════════
                 MODO ÍTEM: formulario simple
                 ═══════════════════════════════════════════════ --}}
            @if(!$esModulo)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form action="{{ route('mano.obra.item.store_avance', [$proyecto, $asignacion]) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="modo" value="item">

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Fecha *</label>
                            <input type="date" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                % de Avance * (máx. {{ $maxPorcentaje }}%)
                            </label>
                            <input type="number" name="porcentaje_avance" id="pct-input"
                                   min="1" max="{{ $maxPorcentaje }}" step="0.01"
                                   value="{{ old('porcentaje_avance') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                   oninput="calcMonto(this.value, {{ $asignacion->monto_acordado }}, 'monto-display')">
                        </div>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 mb-4">
                        <p class="text-xs text-emerald-600 font-medium mb-1">Monto a pagar</p>
                        <p class="text-xl font-bold text-emerald-700" id="monto-display">Bs 0.00</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-500 mb-2">Fotos del avance (máx. 3)</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach([1,2,3] as $n)
                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Foto {{ $n }}</label>
                                <input type="file" name="foto{{ $n }}" accept=".jpg,.jpeg,.png"
                                       class="w-full border border-gray-300 rounded-lg px-2 py-1 text-xs"
                                       onchange="previewFoto(this, 'prev-{{ $n }}')">
                                <img id="prev-{{ $n }}" src="" class="hidden mt-1 w-full h-20 object-cover rounded-lg border">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Observaciones</label>
                        <textarea name="observaciones" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                  placeholder="Descripción del avance verificado...">{{ old('observaciones') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('mano.obra.item.index', $proyecto) }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                                class="px-6 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg font-medium">
                            Aprobar y Registrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- ═══════════════════════════════════════════════
                 MODO MÓDULO: un panel por ítem del módulo
                 ═══════════════════════════════════════════════ --}}
            @else
            <form action="{{ route('mano.obra.item.store_avance', [$proyecto, $asignacion]) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="modo" value="modulo">

                {{-- Fecha global --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-4 flex items-center gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Fecha de registro *</label>
                        <input type="date" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}"
                               class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="text-xs text-gray-400 mt-4">
                        Completa solo los ítems con avance. Los que dejes en 0% se omitirán.
                    </div>
                </div>

                {{-- UN PANEL POR ÍTEM --}}
                @foreach($asignacion->modulo->items as $item)
                @php
                    $avancePrevItem = $avancesPorItem[$item->id] ?? 0;
                    $maxItem = max(0, 100 - $avancePrevItem);
                    $pctBar  = $avancePrevItem;
                    $barCol  = $pctBar >= 100 ? 'bg-green-500' : ($pctBar > 0 ? 'bg-blue-400' : 'bg-gray-200');
                    $montoItem = $asignacion->monto_acordado > 0 && $asignacion->modulo->total_presupuestado > 0
                        ? ($asignacion->monto_acordado * ($item->parcial / $asignacion->modulo->total_presupuestado))
                        : $item->parcial;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow mb-3 overflow-hidden
                            {{ $maxItem <= 0 ? 'opacity-60' : '' }}">
                    {{-- Cabecera ítem --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400 font-medium w-6">{{ $item->numero }}</span>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $item->descripcion }}</p>
                            <span class="text-xs text-gray-400">{{ $item->unidad }}</span>
                        </div>
                        <div class="text-right shrink-0 ml-2">
                            <p class="text-xs text-gray-500">Bs {{ number_format($item->parcial, 2) }}</p>
                            <p class="text-xs {{ $pctBar >= 100 ? 'text-green-600 font-bold' : 'text-gray-400' }}">
                                {{ $pctBar >= 100 ? '✅ Completado' : $pctBar.'% previo' }}
                            </p>
                        </div>
                    </div>

                    @if($maxItem <= 0)
                        <div class="px-4 py-3 text-xs text-green-600 font-medium">Ítem al 100% — ya completado</div>
                    @else
                    <div class="p-4">
                        {{-- Barra de avance previo --}}
                        @if($pctBar > 0)
                        <div class="mb-3">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="{{ $barCol }} h-1.5 rounded-full" style="width:{{ $pctBar }}%"></div>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">% de avance (máx. {{ $maxItem }}%)</label>
                                <input type="number" name="items[{{ $item->id }}][porcentaje]"
                                       min="0" max="{{ $maxItem }}" step="0.01" value="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                       oninput="calcMontoItem(this, {{ $montoItem }}, 'monto-item-{{ $item->id }}')">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Monto a pagar</label>
                                <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                                    <p class="text-sm font-bold text-emerald-700" id="monto-item-{{ $item->id }}">Bs 0.00</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="block text-xs text-gray-500 mb-1">Observaciones del ítem</label>
                            <input type="text" name="items[{{ $item->id }}][observaciones]"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm"
                                   placeholder="Opcional...">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Fotos (máx. 3)</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach([1,2,3] as $n)
                                <div>
                                    <input type="file" name="items[{{ $item->id }}][foto{{ $n }}]"
                                           accept=".jpg,.jpeg,.png"
                                           class="w-full border border-gray-200 rounded px-1 py-1 text-xs"
                                           onchange="previewFoto(this, 'prev-{{ $item->id }}-{{ $n }}')">
                                    <img id="prev-{{ $item->id }}-{{ $n }}" src=""
                                         class="hidden mt-1 w-full h-16 object-cover rounded border">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach

                {{-- Total del módulo en este avance --}}
                <div class="bg-gray-800 dark:bg-gray-900 rounded-xl p-4 mb-4 flex justify-between items-center text-white">
                    <p class="font-semibold">Total a pagar en este avance:</p>
                    <p class="text-2xl font-bold" id="total-modulo">Bs 0.00</p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('mano.obra.item.index', $proyecto) }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                    <button type="submit"
                            class="px-6 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg font-medium">
                        Aprobar y Registrar Avance del Módulo
                    </button>
                </div>
            </form>
            @endif

        </div>
    </div>

<script>
function calcMonto(pct, monto, displayId) {
    const m = monto * (parseFloat(pct) || 0) / 100;
    document.getElementById(displayId).textContent = 'Bs ' + m.toFixed(2);
}

function calcMontoItem(input, montoItem, displayId) {
    const pct = parseFloat(input.value) || 0;
    const m = montoItem * pct / 100;
    document.getElementById(displayId).textContent = 'Bs ' + m.toFixed(2);
    actualizarTotal();
}

function actualizarTotal() {
    let total = 0;
    document.querySelectorAll('[id^="monto-item-"]').forEach(el => {
        const val = parseFloat(el.textContent.replace('Bs ', '')) || 0;
        total += val;
    });
    const tot = document.getElementById('total-modulo');
    if (tot) tot.textContent = 'Bs ' + total.toFixed(2);
}

function previewFoto(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</x-app-layout>