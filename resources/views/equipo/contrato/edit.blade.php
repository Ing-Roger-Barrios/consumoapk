<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold mb-6">Editar Ítem de Equipo y Maquinaria</h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form action="{{ route('equipo.contrato.update', [$proyecto, $equipo]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Descripción -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion', $equipo->descripcion) }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Unidad -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidad</label>
                        <input type="text" name="unidad" value="{{ old('unidad', $equipo->unidad) }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('unidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Cantidad -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                        <input type="number" name="cantidad" value="{{ old('cantidad', $equipo->cantidad) }}" step="0.01" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('cantidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Precio unitario -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precio Unitario (Bs)</label>
                        <input type="number" name="precio_unit" value="{{ old('precio_unit', $equipo->precio_unit) }}" step="0.01" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('precio_unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Total preview -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <strong>Total:</strong> 
                            <span id="total-preview" class="font-semibold">
                                Bs {{ number_format($equipo->total, 2) }}
                            </span>
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('equipo.contrato.index', $proyecto) }}" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Actualizar Ítem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const cantidad = document.querySelector('input[name="cantidad"]');
        const precio = document.querySelector('input[name="precio_unit"]');
        const totalPreview = document.getElementById('total-preview');

        function calcularTotal() {
            const c = parseFloat(cantidad.value) || 0;
            const p = parseFloat(precio.value) || 0;
            const total = c * p;
            totalPreview.textContent = 'Bs ' + total.toFixed(2);
        }

        if (cantidad && precio && totalPreview) {
            cantidad.addEventListener('input', calcularTotal);
            precio.addEventListener('input', calcularTotal);
        }
    });
    </script>
</x-app-layout>