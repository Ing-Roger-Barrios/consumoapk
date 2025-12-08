<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('beneficios.index', $proyecto) }}" 
                   class="text-blue-600 hover:text-blue-800">
                    ← Volver a beneficios
                </a>
            </div>
            <h1 class="text-2xl font-semibold mb-6">Editar Beneficio Social</h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form action="{{ route('beneficios.update', [$proyecto, $beneficio]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Tipo de beneficio -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Beneficio</label>
                        <select name="tipo_beneficio" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            <option value="">Selecciona un tipo de beneficio</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo }}" 
                                        {{ (old('tipo_beneficio') ?? $beneficio->tipo_beneficio) == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_beneficio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Monto -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto (Bs)</label>
                        <input type="number" name="monto" value="{{ old('monto', $beneficio->monto) }}" step="0.01" min="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('monto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Fecha de pago -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Pago</label>
                        <input type="date" name="fecha_pago" value="{{ old('fecha_pago', $beneficio->fecha_pago->format('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               required>
                        @error('fecha_pago') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Comprobante actual -->
                    @if($beneficio->comprobante)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comprobante actual</label>
                            @if(pathinfo($beneficio->comprobante, PATHINFO_EXTENSION) === 'pdf')
                                <a href="{{ asset('storage/' . $beneficio->comprobante) }}" target="_blank"
                                   class="text-blue-500 hover:underline">
                                    Ver documento PDF
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $beneficio->comprobante) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $beneficio->comprobante) }}" 
                                         alt="Comprobante" class="w-32 h-32 object-cover rounded border mt-2">
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Reemplazar comprobante -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $beneficio->comprobante ? 'Reemplazar comprobante' : 'Subir comprobante' }}
                            <span class="text-gray-500">(Opcional)</span>
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

                    <!-- Notas -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notas (Opcional)</label>
                        <textarea name="notas" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Detalles adicionales...">{{ old('notas', $beneficio->notas) }}</textarea>
                        @error('notas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('beneficios.index', $proyecto) }}" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Actualizar Beneficio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>