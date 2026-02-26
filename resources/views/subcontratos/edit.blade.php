<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold mb-6">
                <x-back-button :href="route('subcontratos.index', $proyecto)" label=""/>
                Editar Subcontrato
            </h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form action="{{ route('subcontratos.update', [$proyecto, $subcontrato]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre del subcontratista -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Subcontratista</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $subcontrato->nombre) }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Descripción del trabajo -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción del Trabajo</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion', $subcontrato->descripcion) }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Monto acordado -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto Acordado (Bs)</label>
                        <input type="number" name="monto_acordado" value="{{ old('monto_acordado', $subcontrato->monto_acordado) }}" step="0.01" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        @error('monto_acordado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Contrato actual -->
                    @if($subcontrato->contrato)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contrato actual</label>
                            @if(pathinfo($subcontrato->contrato, PATHINFO_EXTENSION) === 'pdf')
                                <a href="{{ asset('storage/' . $subcontrato->contrato) }}" target="_blank"
                                    class="text-blue-500 hover:underline">
                                    Ver documento PDF
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $subcontrato->contrato) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $subcontrato->contrato) }}" 
                                        alt="Contrato" class="w-32 h-32 object-cover rounded border">
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Reemplazar contrato -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $subcontrato->contrato ? 'Reemplazar contrato' : 'Subir contrato' }} 
                            <span class="text-gray-500">(Opcional)</span>
                        </label>
                        <input type="file" name="contrato" accept=".pdf,.jpg,.jpeg,.png"
                            class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        @error('contrato') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Formatos: PDF, JPG, PNG (máx. 10 MB)</p>
                    </div>


                    <!-- Notas -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notas (Opcional)</label>
                        <textarea name="notas" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Información adicional...">{{ old('notas', $subcontrato->notas) }}</textarea>
                        @error('notas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('subcontratos.index', $proyecto) }}" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Actualizar Subcontrato
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>