<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
     
        <div class="p-3">
            <a href="{{ route('libro.planillas.index', $proyecto) }}" class="text-sm text-blue-600 hover:underline">
                ← Volver a Planillas
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mt-1">
                Editar Planilla
            </h2>
            <p class="text-gray-500 text-sm">{{ $proyecto->nombre }}</p>
        </div>
     

    <div class=" mx-auto px-3 py-3">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <form action="{{ route('libro.planillas.update', [$proyecto, $planilla]) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">N° de Planilla *</label>
                        <input type="text" name="numero_planilla"
                               value="{{ old('numero_planilla', $planilla->numero_planilla) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('numero_planilla') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monto (Bs.) *</label>
                        <input type="number" name="monto" step="0.01" min="0.01"
                               value="{{ old('monto', $planilla->monto) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('monto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Concepto *</label>
                        <input type="text" name="concepto"
                               value="{{ old('concepto', $planilla->concepto) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('concepto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Pago *</label>
                        <input type="date" name="fecha_pago"
                               value="{{ old('fecha_pago', $planilla->fecha_pago->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('fecha_pago') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comprobante</label>
                        @if($planilla->comprobante)
                            <p class="text-xs text-gray-500 mb-1">
                                Actual: <a href="{{ $planilla->comprobante }}" target="_blank"
                                           class="text-blue-600 hover:underline">Ver comprobante</a>
                            </p>
                        @endif
                        <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Dejar vacío para mantener el actual.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea name="notas" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notas', $planilla->notas) }}</textarea>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('libro.planillas.index', $proyecto) }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">
                        Actualizar
                    </button>
                </div>

            </form>
        </div>
    </div>
    </div>
</x-app-layout>