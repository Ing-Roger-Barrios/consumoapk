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
                            <h1 class="text-2xl font-semibold mb-4 px-8">Lista de Mat. 
                                <a href="{{ route('materiales.contrato.create', $proyecto) }}" class="text-purple-600 hover:bg-purple-700 hover:underline">➕</a>
                                <a href="{{ route('materiales.contrato.index', $proyecto) }}" class="text-purple-600 hover:bg-purple-700 hover:underline">✏️</a>
                                <!-- Botón de importación -->
                            <button type="button" 
                                    onclick="document.getElementById('import-form').classList.toggle('hidden')"
                                    class="text-purple-600 hover:bg-purple-700 hover:underline">
                                📤Imp. Excel.
                            </button>
                            
                                    
                            
                                    
                                 
                            </h1> 

                                <!-- Formulario de importación (oculto por defecto) -->
                            <div id="import-form" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                <h3 class="font-medium mb-3">Importar desde Excel</h3>
                                <form action="{{ route('materiales.contrato.import', $proyecto) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex items-end space-x-3">
                                        <div class="flex-1">
                                            <input type="file" name="archivo_excel" accept=".xlsx,.xls,.csv"
                                                class="block w-full text-sm text-gray-500
                                                    file:mr-4 file:py-2 file:px-4
                                                    file:rounded file:border-0
                                                    file:text-sm file:font-semibold
                                                    file:bg-purple-50 file:text-purple-700
                                                    hover:file:bg-purple-100" required>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Formatos: XLSX, XLS, CSV (máx. 10 MB). 
                                                <a href="#" onclick="mostrarEjemplo()" class="text-purple-600 hover:underline"> <strong>Ver formato de ejemplo</strong> </a>
                                            </p>
                                        </div>
                                        <button type="submit" 
                                                class="px-4 py-2 mx-8 bg-blue-600 text-white rounded-md hover:bg-purple-700">
                                                Importar
                                        </button>
                                        <button type="button" 
                                                onclick="document.getElementById('import-form').classList.add('hidden')"
                                                class="px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>

                            



            

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($comparacion->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                            <p class="text-gray-500">No hay materiales registrados en contrato ni en ejecución.</p>
                        </div>
                    @else

                     
                        <div class="container">
                            

                     
                            <div class="  bg-white p-4 rounded-lg shadow-xl rounded-base ">
                                <ul role="list" class="space-y-4 ">
                                        <li class="grid grid-cols-5 gap-2 items-center mb-1">
                                            <div class="col-span-2 flex items-center text-body">
                                                 <span class="text-body text-gray-900">Descripcion</span>
                                            </div>
                                            <span class="col-span-1 text-body font-medium text-gray-600 text-right">Uni.</span>
                                            <span class="col-span-1 text-body font-medium text-gray-600 text-right">Cant.</span>
                                            <span class="col-span-1 text-body font-medium text-gray-600 text-right">Total</span>
                                             
                                            
                                            
                                        </li>
                                        <hr>
                                        @foreach($comparacion as $item)
                                            <div  class="overflow-x-auto hover:bg-gray-50 transition duration-150 ease-in-out mb-0">
                                                
                                                    <a class="grid  " href="{{ route('mat.compra',['proyecto' => $proyecto, 'descripcion' => $item['descripcion'], 'unidad' => $item['unidad']]) }}">
                                                                        
                                                        <li class="grid grid-cols-5 gap-2 items-center">
                                                            
                                                                <div class="col-span-2 flex items-center text-body">
                                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                                    <span class="text-body text-gray-900">
                                                                        {{ $item['descripcion'] }}</span>
                                                                        
                                                                </div>
                                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                                    
                                                                    {{ $item['unidad'] }}
                                                                    
                                                                </span>
                                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                                    
                                                                    {{ number_format($item['contrato']['cantidad'], 2) }}
                                                                    
                                                                </span>
                                                                
                                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                                    
                                                                    {{ number_format($item['contrato']['total'], 2) }}
                                                                    
                                                                </span>
                                                            
                                                            
                                                                
                                                            
                                                             
                                                            
                                                        </li>
                                                        
                                                            <div class=" ">
                                                                <div class="grid grid-cols-5 gap-2 items-center">
                                                                    <span class="col-span-2 text-body text-gray-300"> {{ $item['descripcion'] }} Ejec.</span>
                                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">{{ $item['unidad'] }}</span>
                                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">{{ number_format($item['ejecucion']['cantidad'], 2) }}</span>
                                                                    
                                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">{{ number_format($item['ejecucion']['total'], 2) }}</span>
                                                                    
                                                                    
                                                                </div>
                                                            </div>
                                                    </a>
                                                    
                                                
                                            </div>
                                            <hr> 
                                        @endforeach
                                      

                                    
                                </ul>
                            </div>
                            

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



                            <!-- Modal de ejemplo (opcional) -->
                            <div id="ejemplo-modal" class="hidden fixed inset-0 bg-black/10 flex items-center justify-center z-50">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-md">
                                    <h3 class="font-bold mb-3">Formato de Excel</h3>
                                    <div class="text-sm">
                                        <p class="mb-2">Tu archivo debe tener estas columnas (en este orden):</p>
                                        <div class="font-mono text-xs bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                            descripcion    | unidad | cantidad | precio_unit
                                        </div>
                                        <p class="mt-2">Ejemplo:</p>
                                        <div class="font-mono text-xs bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                            Cemento Tipo I | Bolsa  | 100      | 50.00<br>
                                            Arena fina     | m³     | 50       | 85.50
                                        </div>
                                    </div>
                                    <button onclick="document.getElementById('ejemplo-modal').classList.add('hidden')"
                                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md">
                                        Entendido
                                    </button>
                                    <a onclick="document.getElementById('ejemplo-modal').classList.add('hidden')" href="{{ asset('docs/ejemplo_materiales.xlsx') }}" class="text-purple-600 hover:bg-purple-700 hover:underline">📥Descargar Ejemplo</a>
                                </div>
                            </div>




<script>
function mostrarEjemplo() {
    document.getElementById('ejemplo-modal').classList.remove('hidden');
}
// Opcional: cerrar modal al hacer clic fuera del contenido
document.getElementById('ejemplo-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>
</x-app-layout>