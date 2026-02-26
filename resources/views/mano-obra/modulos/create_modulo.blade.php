{{-- ═══════════════════════════════════════════════════════════
     resources/views/mano_obra/modulos/create_modulo.blade.php
     ═══════════════════════════════════════════════════════════ --}}
<x-app-layout>
    <div class="py-3">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-4">
                <x-back-button :href="route('mano.obra.modulos.index', $proyecto)" label="" />
                <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Nuevo Módulo</h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form action="{{ route('mano.obra.modulos.store_modulo', $proyecto) }}" method="POST">
                    @csrf
                    @if($errors->any())
                        <div class="mb-4 text-red-600 text-sm">
                            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                        </div>
                    @endif
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Código *</label>
                        <input type="text" name="codigo" value="{{ old('codigo', 'M0'.($ultimoOrden+1)) }}"
                               placeholder="Ej: M01, M06"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nombre *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               placeholder="Ej: OBRAS PRELIMINARES"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('mano.obra.modulos.index', $proyecto) }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                            Crear Módulo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>