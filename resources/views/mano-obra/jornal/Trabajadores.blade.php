<x-app-layout>
    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6 px-2">
                <div class="flex items-center gap-3">
                    <x-back-button :href="route('mano.obra.hub', $proyecto)" label="" />
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">👷 Trabajadores Jornal</h1>
                        <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                    </div>
                </div>
                <a href="{{ route('jornal.planillas', $proyecto) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
                    📋 Ver Planillas
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <div class="grid md:grid-cols-2 gap-6">

                {{-- FORMULARIO AGREGAR --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                    <h2 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">Agregar Trabajador</h2>

                    <form action="{{ route('jornal.asignar', $proyecto) }}" method="POST">
                        @csrf

                        {{-- Trabajador existente --}}
                        @if($disponibles->isNotEmpty())
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Reutilizar trabajador existente</label>
                            <select name="trabajador_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">— Nuevo trabajador —</option>
                                @foreach($disponibles as $t)
                                    <option value="{{ $t->id }}">{{ $t->nombre }} ({{ $t->cargo }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Si seleccionas uno existente, el nombre no es necesario.</p>
                        </div>
                        <div class="border-t border-dashed border-gray-200 my-3"></div>
                        @endif

                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nombre *</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                       placeholder="Ej: Juan Pérez">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">C.I.</label>
                                <input type="text" name="ci" value="{{ old('ci') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Cargo</label>
                                <input type="text" name="cargo" value="{{ old('cargo') }}"
                                       placeholder="Albañil, Ayudante..."
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Salario/Día (Bs) *</label>
                                <input type="number" name="salario_dia" step="0.01" min="0"
                                       value="{{ old('salario_dia') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Valor Hora Extra (Bs) *</label>
                                <input type="number" name="hora_extra" step="0.01" min="0"
                                       value="{{ old('hora_extra') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>

                        <button type="submit"
                                class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg">
                            + Agregar al Proyecto
                        </button>
                    </form>
                </div>

                {{-- LISTA DE ASIGNADOS --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
                    <h2 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">
                        Trabajadores Asignados ({{ $asignados->count() }})
                    </h2>

                    @if($asignados->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-6">No hay trabajadores asignados aún.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($asignados as $pt)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <p class="font-medium text-sm text-gray-800 dark:text-gray-100">
                                        {{ $pt->nombre }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $pt->cargo }} · CI: {{ $pt->ci ?? '—' }}
                                    </p>
                                    <p class="text-xs text-blue-600 font-medium">
                                        Bs {{ number_format($pt->salario_dia, 2) }}/día ·
                                        HS extra: Bs {{ number_format($pt->hora_extra, 2) }}
                                    </p>
                                </div>
                                <form action="{{ route('jornal.desasignar', [$proyecto, $pt]) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Quitar trabajador del proyecto?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>