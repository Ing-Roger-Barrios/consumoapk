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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-">
            <h1 class="text-2xl font-semibold mb-4 px-8">Proyecto </h1>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">


            <!-- Comparación de totales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <h3 class="font-bold text-blue-800 dark:text-blue-200">Materiales de Contrato</h3>
                    <p class="text-2xl font-semibold">Bs {{ number_format($proyecto->materialesContrato->sum('total'), 2) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $proyecto->materialesContrato->count() }} ítems</p>
                </div>

                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <h3 class="font-bold text-green-800 dark:text-green-200">Materiales Ejecutados</h3>
                    <p class="text-2xl font-semibold">Bs {{ number_format($proyecto->materialesEjecucion->sum('total'), 2) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $proyecto->materialesEjecucion->count() }} ítems</p>
                </div>
            </div>

            <!-- Tabla comparativa (opcional) -->
            <table class="min-w-full mt-6">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Contrato (Bs)</th>
                        <th>Ejecutado (Bs)</th>
                        <th>Diferencia</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contratoMap = $proyecto->materialesContrato->keyBy('descripcion');
                        $ejecucionMap = $proyecto->materialesEjecucion->keyBy('descripcion');
                        $allDesc = $contratoMap->keys()->merge($ejecucionMap->keys())->unique();
                    @endphp

                    @foreach($allDesc as $desc)
                        @php
                            $c = $contratoMap->get($desc);
                            $e = $ejecucionMap->get($desc);
                            $totalC = $c ? $c->total : 0;
                            $totalE = $e ? $e->total : 0;
                            $diff = $totalE - $totalC;
                        @endphp
                        <tr>
                            <td>{{ $desc }}</td>
                            <td>{{ number_format($totalC, 2) }}</td>
                            <td>{{ number_format($totalE, 2) }}</td>
                            <td class="{{ $diff > 0 ? 'text-red-500' : 'text-green-500' }}">
                                {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>






                </div>
            </div>
        </div>
    </div>


</x-app-layout>