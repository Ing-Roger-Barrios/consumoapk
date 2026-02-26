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
                            <h1 class="text-2xl font-semibold mb-4 px-8">
                                <x-back-button :href="route('proy', $proyecto)" label="" />
                                Lista de Mat. 
                                <a href="{{ route('materiales.contrato.create', $proyecto) }}" class="text-purple-600 hover:bg-purple-700 hover:underline">➕</a>
                                
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

                            <div class=" mx-4">
                                <input 
                                    type="text" 
                                    id="filtroDescripcion"
                                    placeholder="Filtrar por descripción..."
                                    class="w-full px-4 py-2 border rounded-lg shadow-sm  "
                                >
                            </div>

                            

                     
                            <div class="bg-white p-4 rounded-lg shadow-xl">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full   w-full text-left dark:divide-gray-700 border-collapse">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-gray-900 dark:text-gray-200">Descripción</th>
                                                <th class="px-4 py-2 text-right text-gray-900 dark:text-gray-200">Uni.</th>
                                                <th class="px-4 py-2 text-right text-gray-900 dark:text-gray-200">Cant.</th>
                                                <th class="px-4 py-2 text-right text-gray-900 dark:text-gray-200">Precio_unit. (Bs)</th>
                                                <th class="px-4 py-2 text-right text-gray-900 dark:text-gray-200">Total (Bs)</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                            @foreach($comparacion as $item)

                                                @php
                                                    $totalContrato = $item['contrato']['total'];
                                                    $totalEjec = $item['ejecucion']['total'];

                                                    // Porcentaje de avance
                                                    $porcentaje = $totalContrato > 0 ? ($totalEjec / $totalContrato) * 100 : 0;
                                                    $porcentaje = min($porcentaje, 100); // límite 100%

                                                    if ($totalEjec == 0) {
                                                        $color = 'text-gray-300';
                                                        $bg   = 'bg-gray-50';
                                                        $bar  = 'bg-gray-300';
                                                    } elseif ($totalEjec < $totalContrato * 0.9) {
                                                        $color = 'text-green-600';
                                                        $bg   = 'bg-green-50';
                                                        $bar  = 'bg-green-500';
                                                    } elseif ($totalEjec < $totalContrato) {
                                                        $color = 'text-red-400';
                                                        $bg   = 'bg-red-50';
                                                        $bar  = 'bg-red-400';
                                                    } else {
                                                        $color = 'text-red-700';
                                                        $bg   = 'bg-red-100';
                                                        $bar  = 'bg-red-700';
                                                    }
                                                @endphp

                                                <!-- Fila principal: Contrato -->
                                                <tr class="fila-item bg-white dark:bg-gray-800 hover:bg-gray-50 cursor-pointer"
                                                    onclick="window.location='{{ route('mat.compra', ['proyecto' => $proyecto, 'descripcion' => $item['descripcion'], 'unidad' => $item['unidad']]) }}'">

                                                    <td class="px-4 py-2 flex items-center text-gray-900">
                                                        <span class="w-6 h-6 mr-2 flex items-center justify-center 
                                                                    rounded-full bg-blue-600 text-white font-bold text-sm">
                                                            {{ $loop->iteration }}
                                                        </span>
                                                        {{ $item['descripcion'] }}
                                                    </td>

                                                    <td class="px-4 py-2 text-right text-gray-600">{{ $item['unidad'] }}</td>
                                                    <td class="px-4 py-2 text-right text-gray-600">{{ number_format($item['contrato']['cantidad'], 2) }}</td>
                                                    <td class="px-4 py-2 text-right text-gray-600">{{ number_format($item['contrato']['precio'], 2) }}</td>
                                                    <td class="px-4 py-2 text-right text-gray-600">{{ number_format($item['contrato']['total'], 2) }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap  text-sm font-medium">
                                                        <a href="{{ route('materiales.contrato.edit', [$proyecto, $item['descripcion']]) }}" 
                                                            class="text-yellow-500 hover:text-yellow-600 mr-3">
                                                            ✏️
                                                        </a>
                                                        
                                                        <form action="{{ route('materiales.contrato.destroy', [$proyecto, $item['descripcion']]) }}" 
                                                            method="POST" 
                                                            class="inline"
                                                            onsubmit="return confirm('¿Estás seguro de eliminar este material de contrato?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-600">
                                                                ❌
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- Fila secundaria: Ejecución con colores dinámicos -->
                                                <tr class="fila-item {{ $bg }}"  onclick="window.location='{{ route('mat.compra', ['proyecto' => $proyecto, 'descripcion' => $item['descripcion'], 'unidad' => $item['unidad']]) }}'">
                                                    <td class="px-4 py-1 {{ $color }}">
                                                        {{ $item['descripcion'] }} Ejec.
                                                    </td>

                                                    <td class="px-4 py-1 text-right {{ $color }}">
                                                        {{ $item['unidad'] }}
                                                    </td>

                                                    <td class="px-4 py-1 text-right {{ $color }}">
                                                        {{ number_format($item['ejecucion']['cantidad'], 2) }}
                                                    </td>

                                                    <td class="px-4 py-1 text-right {{ $color }}">
                                                        {{ number_format($item['ejecucion']['precio'], 2) }}
                                                    </td>

                                                    <td class="px-4 py-1 text-right {{ $color }}">
                                                        <div class="w-full">
                                                            <div class="text-right font-semibold">
                                                                {{ number_format($item['ejecucion']['total'], 2) }}
                                                            </div>

                                                            <!-- Barra de progreso -->
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <div class="flex-1 bg-gray-200 rounded h-2">
                                                                    <div class="{{ $bar }} h-2 rounded"
                                                                        style="width: {{ $porcentaje }}%">
                                                                    </div>
                                                                </div>
                                                                <div class="text-xs font-medium whitespace-nowrap {{ $color }}">
                                                                    {{ number_format($porcentaje, 0) }}%
                                                                </div>
                                                            </div>

                                                            
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>

                                            @endforeach

                                        </tbody>
                                        <tfoot class="bg-gray-50 dark:bg-gray-700/50 font-bold">
                                            <tr>
                                                <td colspan="4" class="px-6 py-3 text-right">TOTAL PRESUPUESTADO:</td>
                                                <td class="px-6 py-3 text-green-700 dark:text-green-400">
                                                    Bs {{ number_format($proyecto->materialesContrato->sum('total'), 2) }}
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
<script>
document.getElementById('filtroDescripcion').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('.fila-item');

    filas.forEach((fila) => {
        const texto = fila.innerText.toLowerCase();

        if (texto.includes(filtro)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
});
</script>

</x-app-layout>