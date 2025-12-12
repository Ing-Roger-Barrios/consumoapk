<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                
            </div>
            <h1 class="text-2xl font-semibold mb-6">Impuesto a las Transferencias (IT)</h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Información del Proyecto</h3>
                    <p><strong>Monto del Proyecto:</strong> Bs {{ number_format($proyecto->monto, 2) }}</p>
                    <p><strong>Tasa IT actual:</strong> {{ number_format($it->porcentaje, 2) }}%</p>
                    <p><strong>Monto IT calculado:</strong> Bs {{ number_format($it->monto_calculado, 2) }}</p>
                </div>

                <form action="{{ route('it.update', $proyecto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Porcentaje IT -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Porcentaje de IT (%)
                            <span class="text-gray-500 ml-1">(Tasa impositiva actual)</span>
                        </label>
                        <div class="relative mt-1">
                            <input type="number" 
                                   name="porcentaje" 
                                   value="{{ old('porcentaje', $it->porcentaje) }}" 
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
                            El monto se calculará automáticamente: Bs {{ number_format($proyecto->monto * (old('porcentaje', $it->porcentaje) / 100), 2) }}
                        </p>
                        @error('porcentaje') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Comprobante -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Comprobante de Pago (opcional)
                        </label>
                        <input type="file" name="comprobante" accept=".pdf,.jpg,.jpeg,.png"
                            class="mt-1 block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100">
                        @error('comprobante') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Formatos: PDF, JPG, PNG (máx. 5 MB)</p>
                    </div>

                    <!-- Comprobante actual -->
                    @if($it->comprobante)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comprobante actual</label>
                            @if(pathinfo($it->comprobante, PATHINFO_EXTENSION) === 'pdf')
                                <a href="{{ $it->comprobante }}" target="_blank"
                                   class="text-blue-500 hover:underline">
                                    Ver documento PDF
                                </a>
                            @else
                                <a href="{{ $it->comprobante }}" target="_blank">
                                    <img src="{{ $it->comprobante }}" 
                                         alt="Comprobante" class="w-32 h-32 object-cover rounded border mt-2">
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Notas -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notas (Opcional)</label>
                        <textarea name="notas" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Detalles sobre el pago del IT...">{{ old('notas', $it->notas) }}</textarea>
                        @error('notas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('proy', $proyecto) }}" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Actualizar IT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para actualización en tiempo real del cálculo -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const porcentajeInput = document.querySelector('input[name="porcentaje"]');
        const proyectoMonto = {{ $proyecto->monto }};
        
        function actualizarCalculo() {
            const porcentaje = parseFloat(porcentajeInput.value) || 0;
            const montoCalculado = (proyectoMonto * porcentaje / 100).toFixed(2);
            const mensaje = document.querySelector('.text-gray-500.mt-1');
            if (mensaje) {
                mensaje.textContent = `El monto se calculará automáticamente: Bs ${montoCalculado}`;
            }
        }
        
        if (porcentajeInput) {
            porcentajeInput.addEventListener('input', actualizarCalculo);
        }
    });
    </script>
</x-app-layout>