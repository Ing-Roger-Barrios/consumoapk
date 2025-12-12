<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Mis Residentes</h1>
                <a href="{{ route('residents.create') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    + Nuevo Residente
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if($residents->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500">Aún no has creado residentes.</p>
                    <a href="{{ route('residents.create') }}" 
                       class="mt-2 text-blue-500 hover:underline inline-block">
                        Crear tu primer residente
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Creado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($residents as $resident)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $resident->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        {{ $resident->email }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $resident->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('residents.edit', $resident) }}" 
                                           class="text-yellow-500 hover:text-yellow-600 mr-3">
                                            Editar
                                        </a>
                                        <form action="{{ route('residents.destroy', $resident) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este residente? No podrás deshacer esta acción.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600">
                                                Eliminar
                                            </button>
                                        </form>
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