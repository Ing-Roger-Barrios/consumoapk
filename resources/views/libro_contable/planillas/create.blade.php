<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
     
        <div class="p-3">
            <a href="{{ route('libro.planillas.index', $proyecto) }}" class="text-sm text-blue-600 hover:underline">
                ← Volver a Planillas
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mt-1">
                Nueva Planilla de Pago
            </h2>
            <p class="text-gray-500 text-sm">{{ $proyecto->nombre }}</p>
        </div>
     

    <div class=" mx-auto px-3 py-3">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <form action="{{ route('libro.planillas.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">N° de Planilla *</label>
                        <input type="text" name="numero_planilla"
                               value="{{ old('numero_planilla') }}"
                               placeholder="Ej: Planilla #1, Anticipo"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('numero_planilla') border-red-400 @enderror">
                        @error('numero_planilla')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monto (Bs.) *</label>
                        <input type="number" name="monto" step="0.01" min="0.01"
                               value="{{ old('monto') }}" placeholder="0.00"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('monto') border-red-400 @enderror">
                        @error('monto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Concepto *</label>
                        <input type="text" name="concepto"
                               value="{{ old('concepto') }}"
                               placeholder="Ej: Pago por avance de obra - Mes de Enero"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('concepto') border-red-400 @enderror">
                        @error('concepto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Pago *</label>
                        <input type="date" name="fecha_pago"
                               value="{{ old('fecha_pago') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('fecha_pago') border-red-400 @enderror">
                        @error('fecha_pago')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comprobante</label>
                        <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG o PDF. Máx. 5MB.</p>
                        @error('comprobante')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea name="notas" rows="3" placeholder="Observaciones adicionales..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notas') }}</textarea>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('libro.planillas.index', $proyecto) }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">
                        Guardar Planilla
                    </button>
                </div>

            </form>
        </div>
    </div>
    </div>
</x-app-layout>