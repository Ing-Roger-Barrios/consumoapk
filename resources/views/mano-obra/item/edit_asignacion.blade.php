<x-app-layout>
    <div class="py-3">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-4 px-2">
                <x-back-button :href="route('mano.obra.item.index', $proyecto)" label="" />
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Editar Asignación</h1>
                    <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form action="{{ route('mano.obra.item.update_asignacion', [$proyecto, $asignacion]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4 p-3 rounded-lg bg-gray-50 text-sm text-gray-600">
                        <p>
                            <span class="font-semibold">Tipo:</span>
                            {{ $asignacion->tipo_asignacion === 'modulo' ? 'Módulo completo' : 'Ítem específico' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Responsable *</label>
                        <div class="grid grid-cols-2 gap-3 mb-2">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="responsable_tipo" value="trabajador" onchange="toggleResponsable('trabajador')"
                                    {{ old('responsable_tipo', $asignacion->trabajador_id ? 'trabajador' : 'subcontrato') === 'trabajador' ? 'checked' : '' }}>
                                <span>Trabajador</span>
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="responsable_tipo" value="subcontrato" onchange="toggleResponsable('subcontrato')"
                                    {{ old('responsable_tipo', $asignacion->trabajador_id ? 'trabajador' : 'subcontrato') === 'subcontrato' ? 'checked' : '' }}>
                                <span>Contratista</span>
                            </label>
                        </div>

                        <select name="trabajador_id" id="trabajador-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">— Seleccionar trabajador —</option>
                            @foreach($trabajadores as $t)
                                <option value="{{ $t->id }}" {{ old('trabajador_id', $asignacion->trabajador_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->nombre }}{{ $t->cargo ? ' · '.$t->cargo : '' }}
                                </option>
                            @endforeach
                        </select>

                        <select name="subcontrato_id" id="subcontrato-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mt-2 hidden">
                            <option value="">— Seleccionar contratista —</option>
                            @foreach($subcontratos as $subcontrato)
                                <option value="{{ $subcontrato->id }}" {{ old('subcontrato_id', $asignacion->subcontrato_id) == $subcontrato->id ? 'selected' : '' }}>
                                    {{ $subcontrato->nombre }} · {{ $subcontrato->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Monto acordado (Bs)</label>
                        <input type="number" step="0.01" min="0" name="monto_acordado" value="{{ old('monto_acordado', $asignacion->monto_acordado) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Notas</label>
                        <textarea name="notas" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('notas', $asignacion->notas) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('mano.obra.item.index', $proyecto) }}" class="px-4 py-2 text-sm border rounded-lg">Cancelar</a>
                        <button type="submit" class="px-6 py-2 text-sm text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleResponsable(tipo) {
            document.getElementById('trabajador-select').classList.toggle('hidden', tipo !== 'trabajador');
            document.getElementById('subcontrato-select').classList.toggle('hidden', tipo !== 'subcontrato');
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleResponsable(document.querySelector('input[name="responsable_tipo"]:checked')?.value || 'trabajador');
        });
    </script>
</x-app-layout>