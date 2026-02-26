<x-app-layout>
    <div class="py-3">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-4 px-2">
                <x-back-button :href="route('mano.obra.item.index', $proyecto)" label="" />
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Nueva Asignación</h1>
                    <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            @if($modulos->isEmpty())
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-700 rounded-xl p-5 text-sm">
                    <p class="font-semibold mb-1">No hay módulos registrados</p>
                    <p>Primero importa el Excel del presupuesto o crea un módulo manualmente.</p>
                    <a href="{{ route('mano.obra.modulos.index', $proyecto) }}" class="inline-block mt-2 text-blue-600 hover:underline">
                        Ir a Módulos de Obra
                    </a>
                </div>
            @else

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form action="{{ route('mano.obra.item.store_asignacion', $proyecto) }}" method="POST">
                    @csrf

                    {{-- TIPO DE ASIGNACIÓN --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tipo de asignación *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 p-3 border-2 border-emerald-500 bg-emerald-50 rounded-xl cursor-pointer" id="label-item">
                                <input type="radio" name="tipo_asignacion" value="item" checked onchange="toggleTipo('item')" class="accent-emerald-600">
                                <div>
                                    <p class="font-medium text-sm text-gray-800">Por Ítem</p>
                                    <p class="text-xs text-gray-400">Un ítem específico</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-2 p-3 border-2 border-gray-200 rounded-xl cursor-pointer" id="label-modulo">
                                <input type="radio" name="tipo_asignacion" value="modulo" onchange="toggleTipo('modulo')" class="accent-blue-600">
                                <div>
                                    <p class="font-medium text-sm text-gray-800">Módulo completo</p>
                                    <p class="text-xs text-gray-400">Todos los ítems del módulo</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- SELECTOR DE ÍTEM --}}
                    <div id="seccion-item" class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Ítem *</label>
                        <select name="mano_obra_item_id" id="item-select"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                onchange="actualizarMonto(this)">
                            <option value="">— Seleccionar ítem —</option>
                            @foreach($modulos as $modulo)
                                <optgroup label="{{ $modulo->codigo }} - {{ $modulo->nombre }}">
                                    @foreach($modulo->items as $item)
                                        <option value="{{ $item->id }}"
                                                data-monto="{{ $item->parcial }}"
                                                {{ (old('mano_obra_item_id') == $item->id || $preItemId == $item->id) ? 'selected' : '' }}>
                                            {{ $item->numero }}. {{ $item->descripcion }}
                                            ({{ $item->unidad }} · Bs {{ number_format($item->parcial, 2) }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    {{-- SELECTOR DE MÓDULO --}}
                    <div id="seccion-modulo" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Módulo *</label>
                        <select name="modulo_id" id="modulo-select"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                onchange="actualizarMonto(this)">
                            <option value="">— Seleccionar módulo —</option>
                            @foreach($modulos as $modulo)
                                <option value="{{ $modulo->id }}"
                                        data-monto="{{ $modulo->total_presupuestado }}"
                                        {{ (old('modulo_id') == $modulo->id || $preModuloId == $modulo->id) ? 'selected' : '' }}>
                                    {{ $modulo->codigo }} - {{ $modulo->nombre }}
                                    ({{ $modulo->items->count() }} ítems · Bs {{ number_format($modulo->total_presupuestado, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TRABAJADOR --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Trabajador / Contratista *</label>
                        <select name="trabajador_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">— Seleccionar —</option>
                            @foreach($trabajadores as $t)
                                <option value="{{ $t->id }}" {{ old('trabajador_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->nombre }}{{ $t->cargo ? ' · '.$t->cargo : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">
                            No está en la lista?
                            <a href="{{ route('jornal.trabajadores', $proyecto) }}" class="text-blue-500 hover:underline" target="_blank">Agregar trabajador</a>
                        </p>
                    </div>

                    {{-- MONTO ACORDADO --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Monto Acordado (Bs) *</label>
                        <input type="number" name="monto_acordado" id="monto-input"
                               step="0.01" min="0" value="{{ old('monto_acordado') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                               placeholder="Se llena automáticamente al seleccionar">
                        <p class="text-xs text-gray-400 mt-1">Puedes ajustar si el trato es diferente al presupuesto.</p>
                    </div>

                    {{-- NOTAS --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Notas / Condiciones</label>
                        <textarea name="notas" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                  placeholder="Plazos, materiales incluidos...">{{ old('notas') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('mano.obra.item.index', $proyecto) }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                                class="px-6 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg font-medium">
                            Crear Asignación
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

<script>
function toggleTipo(tipo) {
    const secItem   = document.getElementById('seccion-item');
    const secModulo = document.getElementById('seccion-modulo');
    const labelItem   = document.getElementById('label-item');
    const labelModulo = document.getElementById('label-modulo');
    if (tipo === 'item') {
        secItem.classList.remove('hidden');
        secModulo.classList.add('hidden');
        labelItem.className = labelItem.className.replace('border-gray-200','border-emerald-500 bg-emerald-50');
        labelModulo.className = labelModulo.className.replace('border-blue-500 bg-blue-50','border-gray-200');
    } else {
        secItem.classList.add('hidden');
        secModulo.classList.remove('hidden');
        labelModulo.className = labelModulo.className.replace('border-gray-200','border-blue-500 bg-blue-50');
        labelItem.className = labelItem.className.replace('border-emerald-500 bg-emerald-50','border-gray-200');
    }
    document.getElementById('monto-input').value = '';
}
function actualizarMonto(select) {
    const opt = select.options[select.selectedIndex];
    if (opt.dataset.monto) {
        document.getElementById('monto-input').value = parseFloat(opt.dataset.monto).toFixed(2);
    }
}
</script>
</x-app-layout>