<x-app-layout>
    
    <div class="py-3">
        {{-- ✅ Mensajes de sesión aquí --}}
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900/30 dark:border-red-700 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ✅ Mensajes de sesión aquí --}}
            
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold mb-4 px-8">Compra de Mat.
                                
                                    <a href="#" 
                                    data-modal-target="popup-modal" 
                                    data-modal-toggle="popup-modal"
                                     
                                    class="open-modal-btn text-gray-400 hover:underline">
                                        [Reg. compra]
                                    </a>
                                 
                            </h1> 
            <div class="bg-white dark:bg-gray-800   shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                                        
                    <div class="container">

                            <!-- CONTENEDOR RESPONSIVE -->
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-[600px] w-full text-left">
                                <thead class="bg-gray-50 dark:bg-gray-700/40 text-gray-600 dark:text-gray-300 text-sm">
                                    <tr>
                                        <th class="py-3 px-4 whitespace-nowrap">Descripción</th>
                                        <th class="py-3 px-4 whitespace-nowrap">Unidad</th>
                                        <th class="py-3 px-4 whitespace-nowrap">Cantidad</th>
                                        <th class="py-3 px-4 whitespace-nowrap">P. Unit. (Bs)</th>
                                        <th class="py-3 px-4 whitespace-nowrap">Total (Bs)</th>
                                        <th class="py-3 px-4 whitespace-nowrap">Comprobante</th>
                                        <th class="py-3 px-4 whitespace-nowrap">Accion</th>
                                    </tr>
                                </thead>

                                <tbody class="text-sm text-gray-800 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($materiales as $material)

                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                            <td class="py-3 px-4">{{ $material->descripcion }}</td>
                                            <td class="py-3 px-4">{{ $material->unidad }}</td>
                                            <td class="py-3 px-4">{{ $material->cantidad }}</td>
                                            <td class="py-3 px-4">{{ number_format($material->precio_unit, 2) }}</td>
                                            <td class="py-3 px-4">{{ number_format($material->total, 2) }}</td>
                                            <td class="py-3 px-4 text-center">
                                                @if($material->comprobante)
                                                    <a href="{{ asset('storage/' . $material->comprobante) }}" target="_blank"
                                                        class="text-blue-500 hover:underline">Ver</a>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('materiales.ejecucion.edit', [$proyecto, $material]) }}" 
                                                    class="text-yellow-500 hover:text-yellow-600 mr-3">
                                                    ✏️
                                                </a>
                                                
                                                <form action="{{ route('materiales.ejecucion.destroy', [$proyecto, $material]) }}" 
                                                    method="POST" 
                                                    class="inline"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este material?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-600">
                                                        ❌
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                    
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50 font-bold">
                                    @php
                                        $promedio = $materiales->sum(fn($m) => $m->precio_unit * $m->cantidad) 
                                                    / max($materiales->sum('cantidad'), 1);
                                    @endphp
                                    <tr>
                                        <td colspan="2" class="px-6 py-3  ">TOTAL EJECUTADO:</td>
                                        <td class="px-4 py-3 text-green-700 dark:text-green-400">
                                            {{ number_format($materiales->sum('cantidad'), 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-green-700 dark:text-green-400">
                                            {{ number_format($promedio, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-green-700 dark:text-green-400">
                                            Bs {{ number_format($materiales->sum('total'), 2) }}
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>




<!-- Inio de modal -->
<div id="popup-modal" 
    tabindex="-1" 
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/10 backdrop-blur-sm">
    
    <div class="relative w-full max-w-md p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6">
            <form id="compra-form" action="{{ route('materiales.ejecucion.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Hidden: descripción y unidad (prellenados) -->
                <input type="hidden" name="descripcion" value="{{ $descripcion }}">
                <input type="hidden" name="unidad" value="{{ $unidad }}">

                <!-- Header -->
                <div class="text-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Registrar compra: {{ $descripcion }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        Ingresa los datos de la factura.
                    </p>
                </div>

                <!-- Vista previa de imagen -->
                <div class="flex justify-center mb-6">
                    <div id="image-preview-container"
                        class="w-36 h-36 rounded-lg border border-gray-300 dark:border-gray-600 
                                bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                        
                        <svg id="placeholder-icon" class="w-10 h-10 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <img id="image-preview" class="hidden w-full h-full object-cover" alt="Vista previa">
                    </div>
                    
                </div>
                 <p class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, PNG, PDF. Máx. 5 MB.</p>
                <!-- Selector de archivo -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <label class="flex flex-col items-center justify-center py-3 px-4 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span class="text-sm font-medium">Galería</span>
                        <input type="file" name="comprobante" id="gallery-input" accept="image/*,.pdf" class="hidden">
                    </label>

                    <label class="flex flex-col items-center justify-center py-3 px-4 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm font-medium">Cámara</span>
                        <input type="file"  id="camera-input" accept="image/*" capture="environment" class="hidden">
                    </label>
                </div>

                <!-- Cantidad -->
                <div class="mb-5">
                    <label for="invoice-cantidad" class="block text-sm font-medium mb-2">Cantidad</label>
                    <input type="number" id="invoice-cantidad" name="cantidad" min="0" step="0.01"
                        placeholder="0.00"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
                    @error('cantidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Precio unitario -->
                <div class="mb-6">
                    <label for="invoice-amount" class="block text-sm font-medium mb-2">Precio unitario (Bs)</label>
                    <input type="number" id="invoice-amount" name="precio_unit" min="0" step="0.01"
                        placeholder="0.00"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
                    @error('precio_unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-between space-x-4 mt-6">
                    <button type="submit" id="save-button"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                        Guardar compra
                    </button>
                    <button type="button" data-modal-hide="popup-modal"
                        class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('popup-modal');
    const form = document.getElementById('compra-form');
    const descripcionInput = form.querySelector('input[name="descripcion"]');
    const unidadInput = form.querySelector('input[name="unidad"]');
    const cantidadInput = document.getElementById('invoice-cantidad');
    const precioInput = document.getElementById('invoice-amount');
    const galleryInput = document.getElementById('gallery-input');
    const cameraInput = document.getElementById('camera-input');
    const previewIcon = document.getElementById('placeholder-icon');
    const imagePreview = document.getElementById('image-preview');
    const saveButton = document.getElementById('save-button');

    let selectedFile = null;

    // Abrir modal y prellenar
    document.querySelectorAll('.open-modal-btn').forEach(btn => {
        btn.addEventListener('click', function() {
             
            
            // Reset campos
            cantidadInput.value = '';
            precioInput.value = '';
            selectedFile = null;
            previewIcon.classList.remove('hidden');
            imagePreview.classList.add('hidden');
            imagePreview.src = '';
            galleryInput.value = '';
            cameraInput.value = '';
            saveButton.disabled = false;
            saveButton.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    });

    // Manejar selección de archivo
    function handleFileSelect(event) {


        const file = event.target.files[0];
        if (!file) return;

         // Quitar name a ambos antes
        galleryInput.removeAttribute('name');
        cameraInput.removeAttribute('name');

        // Colocar name SOLO al input que usaron
        event.target.setAttribute('name', 'comprobante');

        selectedFile = file;

        const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!validTypes.includes(file.type)) {
            alert('Por favor selecciona una imagen (JPG, PNG) o PDF.');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('El archivo no debe superar los 5 MB.');
            return;
        }

        selectedFile = file;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewIcon.classList.add('hidden');
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewIcon.classList.add('hidden');
            imagePreview.src = ''; // No preview para PDF
            imagePreview.classList.remove('hidden');
            imagePreview.alt = 'Documento PDF';
        }
    }

    galleryInput.addEventListener('change', handleFileSelect);
    cameraInput.addEventListener('change', handleFileSelect);

    // Validación en tiempo real (opcional)
    function validateForm() {
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const precio = parseFloat(precioInput.value) || 0;
        const isValid = cantidad > 0 && precio > 0;
        saveButton.disabled = !isValid;
        if (!isValid) {
            saveButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            saveButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
     form.addEventListener('submit', function(e) {
    if (!selectedFile) {
            e.preventDefault();
            alert("Debes seleccionar una imagen o tomar una foto.");
        }
    });

    cantidadInput.addEventListener('input', validateForm);
    precioInput.addEventListener('input', validateForm);
});
</script>












</x-app-layout>