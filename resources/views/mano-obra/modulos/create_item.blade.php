<x-app-layout>
    <div class="py-3">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-4">
                <x-back-button :href="route('mano.obra.modulos.show', [$proyecto, $modulo])" label="" />
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Nuevo Ítem</h1>
                    <p class="text-sm text-gray-500">
                        <span class="font-semibold text-blue-600">{{ $modulo->codigo }}</span>
                        — {{ $modulo->nombre }}
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form action="{{ route('mano.obra.modulos.store_item', [$proyecto, $modulo]) }}" method="POST">
                    @csrf
                    @if($errors->any())
                        <div class="mb-4 text-red-600 text-sm">
                            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Descripción *</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion') }}"
                               placeholder="Ej: Excavación manual (0-2)m"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Unidad *</label>
                            <input type="text" name="unidad" value="{{ old('unidad') }}"
                                   placeholder="m², m³, glb..."
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Cantidad *</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad') }}"
                                   step="0.01" min="0" id="cantidad"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                   oninput="calcParcial()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">P. Unitario (Bs) *</label>
                            <input type="number" name="precio_unitario" value="{{ old('precio_unitario') }}"
                                   step="0.01" min="0" id="precio"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                   oninput="calcParcial()">
                        </div>
                    </div>

                    {{-- Parcial calculado en tiempo real --}}
                    <div class="mb-6 bg-blue-50 rounded-lg px-4 py-3">
                        <p class="text-xs text-blue-500 font-medium">Parcial calculado</p>
                        <p class="text-xl font-bold text-blue-700" id="parcial-display">Bs 0.00</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('mano.obra.modulos.show', [$proyecto, $modulo]) }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Agregar Ítem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
function calcParcial() {
    const c = parseFloat(document.getElementById('cantidad').value) || 0;
    const p = parseFloat(document.getElementById('precio').value) || 0;
    document.getElementById('parcial-display').textContent = 'Bs ' + (c * p).toFixed(2);
}
</script>
</x-app-layout>