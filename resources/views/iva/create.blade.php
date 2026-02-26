<x-app-layout>
    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <h1 class="text-2xl font-semibold mb-6">
                <x-back-button :href="route('iva.index', $proyecto)" label=""/>
                
                Registrar Factura IVA
            </h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form action="{{ route('iva.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Número de factura -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número de Factura</label>
                        <input type="text" name="numero_factura" value="{{ old('numero_factura') }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Ej: F001-000123, INV-2025-001" required>
                        @error('numero_factura') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Monto de la factura -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto de la Factura (Bs)</label>
                        <input type="number" name="monto_factura" value="{{ old('monto_factura') }}" step="0.01" min="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="0.00" required>
                        @error('monto_factura') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Porcentaje IVA -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Porcentaje de IVA (%)
                            <span class="text-gray-500 ml-1">(Tasa impositiva - Bolivia: 14.94%)</span>
                        </label>
                        <div class="relative mt-1">
                            <input type="number" 
                                   name="porcentaje_iva" 
                                   value="{{ old('porcentaje_iva', 14.94) }}" 
                                   step="0.01" 
                                   min="0" 
                                   max="100"
                                   class="block w-full pl-3 pr-10 py-2.5 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Monto IVA calculado: Bs <span id="iva-calculado">0.00</span>
                        </p>
                        @error('porcentaje_iva') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Fecha de la factura -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de la Factura</label>
                        <input type="date" name="fecha_factura" value="{{ old('fecha_factura', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               required>
                        @error('fecha_factura') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Comprobante -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Comprobante de la Factura (obligatorio)
                        </label>
                        <input type="file" name="comprobante" accept=".pdf,.jpg,.jpeg,.png"
                            class="mt-1 block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100" required>
                        @error('comprobante') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Formatos: PDF, JPG, PNG (máx. 5 MB)</p>
                    </div>

                    <!-- Notas -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notas (Opcional)</label>
                        <textarea name="notas" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Proveedor, descripción de servicios, etc.">{{ old('notas') }}</textarea>
                        @error('notas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('iva.index', $proyecto) }}" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Registrar Factura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para cálculo en tiempo real -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const montoInput = document.querySelector('input[name="monto_factura"]');
        const porcentajeInput = document.querySelector('input[name="porcentaje_iva"]');
        const ivaCalculado = document.getElementById('iva-calculado');
        
        function calcularIVA() {
            const monto = parseFloat(montoInput.value) || 0;
            const porcentaje = parseFloat(porcentajeInput.value) || 0;
            const iva = (monto * porcentaje / 100).toFixed(2);
            ivaCalculado.textContent = iva;
        }
        
        if (montoInput && porcentajeInput && ivaCalculado) {
            montoInput.addEventListener('input', calcularIVA);
            porcentajeInput.addEventListener('input', calcularIVA);
            // Calcular inicial
            calcularIVA();
        }
    });
    </script>
</x-app-layout>