<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">
                    <x-back-button :href="route('proy', $proyecto)" label="" />
                    Equipo y Maq. de contrato
                    <a href="{{ route('equipo.contrato.create', $proyecto) }}" 
                       class="mt-2 text-blue-500 hover:underline inline-block">
                        ➕
                    </a>
                </h1>
                
                
            </div>

            {{-- Mensajes --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if($equipos->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500">No hay ítems de equipo y maquinaria registrados.</p>
                    <a href="{{ route('equipo.contrato.create', $proyecto) }}" 
                       class="mt-2 text-blue-500 hover:underline inline-block">
                        Registrar el primero
                    </a>
                </div>
            @else

                        <div class="container">

                            
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class=" ">
                                <input 
                                    type="text" 
                                    id="filtroDescripcion"
                                    placeholder="Filtrar por descripción..."
                                    class="w-full px-4 py-2 border rounded-lg shadow-sm  "
                                >
                            </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
                        <table class="min-w-[600px] w-full text-left dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">P. Unit. (Bs)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total (Bs)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
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
                                                        onclick="window.location='{{ route('equipo.ejecucion.index', ['proyecto' => $proyecto, 'descripcion' => $item['descripcion'], 'unidad' => $item['unidad']]) }}'">

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
                                                            <a href="{{ route('equipo.contrato.edit', [$proyecto, $item['descripcion']]) }}" 
                                                                class="text-yellow-500 hover:text-yellow-600 mr-3">
                                                                ✏️
                                                            </a>
                                                            
                                                            <form action="{{ route('equipo.contrato.destroy', [$proyecto, $item['descripcion']]) }}" 
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
                                                    <tr class="fila-item {{ $bg }}"  onclick="window.location='{{ route('equipo.ejecucion.index', ['proyecto' => $proyecto, 'descripcion' => $item['descripcion'], 'unidad' => $item['unidad']]) }}'">
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
                                        Bs {{ number_format($equipos->sum('total'), 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>









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