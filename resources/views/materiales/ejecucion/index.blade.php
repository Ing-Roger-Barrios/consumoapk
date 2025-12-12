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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Materiales en Ejecución - {{ $proyecto->nombre }}</h1>
                <a href="{{ route('materiales.ejecucion.create', $proyecto) }}" 
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    + Nuevo Material
                </a>
            </div>

            @if($materiales->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p>No hay materiales registrados en ejecución.</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">P. Unit. (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total (Bs)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Comprobante</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($materiales as $material)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $material->descripcion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $material->unidad }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $material->cantidad }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($material->precio_unit, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ number_format($material->total, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($material->comprobante)
                                            <a href="{{ $material->comprobante }}" target="_blank"
                                                class="text-blue-500 hover:underline">Ver</a>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>