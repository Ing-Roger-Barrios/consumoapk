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

                <a href="{{ url()->previous()  }}"
                    class="inline-flex items-center gap-2 px-4 py-2  
                            text-sm font-medium text-white
                            bg-gray-500 rounded-lg
                            hover:bg-gray-600 
                            transition-all duration-200 shadow-sm">
                        
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>

                        Volver
                </a>


                {{ isset($proyecto) ? 'Editar Proyecto' : 'Crear Nuevo Proyecto' }}
            </h1>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">

                            @if(isset($proyecto))
                                <form action="{{ route('proy.update', $proyecto) }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                @else
                                    <form action="{{ route('proy.store') }}" method="POST">
                                        @csrf
                            @endif

                            <!-- Nombre del Proyecto -->
                            <div class="mb-6">
                                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nombre del Proyecto
                                </label>
                                <input 
                                    type="text" 
                                    id="nombre" 
                                    name="nombre"
                                    value="{{ old('nombre', $proyecto->nombre ?? '') }}"
                                    class="w-full text-gray-900 dark:text-gray-100 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                    placeholder="Ej: Edificio Central" 
                                    required
                                >
                                @error('nombre')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cliente -->
                            <div class="mb-6">
                                <label for="cliente" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Cliente
                                </label>
                                <input 
                                    type="text" 
                                    id="cliente" 
                                    name="cliente"
                                    value="{{ old('cliente', $proyecto->cliente ?? '') }}"
                                    class="w-full text-gray-900 dark:text-gray-100 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                    placeholder="Ej: Constructora GAMW" 
                                    required
                                >
                                @error('cliente')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ubicación -->
                            <div class="mb-6">
                                <label for="ubicacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Ubicación del Proyecto
                                </label>
                                <input 
                                    type="text" 
                                    id="ubicacion" 
                                    name="ubicacion"
                                    value="{{ old('ubicacion', $proyecto->ubicacion ?? '') }}"
                                    class="w-full text-gray-900 dark:text-gray-100 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                    placeholder="Ej: Santa Cruz, Warnes"
                                >
                                @error('ubicacion')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Monto -->
                            <div class="mb-6">
                                <label for="monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Monto (Bs)
                                </label>
                                <input 
                                    type="number" 
                                    id="monto" 
                                    name="monto"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('monto', $proyecto->monto ?? '') }}"
                                    class="w-full text-gray-900 dark:text-gray-100 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                    placeholder="123456.78" 
                                    required
                                >
                                @error('monto')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Residentes (Select múltiple) -->
                            <div class="mb-6">
                                <label for="residentes_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Residentes de Obra (asignar uno o más)
                                </label>
                                <select 
                                    id="residentes_ids" 
                                    name="residentes_ids[]" 
                                    multiple
                                    class="w-full text-gray-900 dark:text-gray-100 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                >
                                    @foreach($residentes as $residente)
                                        <option value="{{ $residente->id }}" 
                                            {{ in_array($residente->id, old('residentes_ids', $proyecto?->residentes->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                            {{ $residente->name }} ({{ $residente->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('residentes_ids')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @if($residentes->isEmpty())
                                    <p class="text-yellow-500 text-xs mt-1">
                                        ⚠️ No tienes residentes creados. 
                                        <a href="{{ route('residents.create') }}" class="text-blue-500 underline">Crea uno primero</a>.
                                    </p>
                                @endif
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-between items-center">
                                <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                                    {{ isset($proyecto) ? 'Actualizar Proyecto' : 'Guardar Proyecto' }}
                                </button>
                                <a href="{{ url()->previous() }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                                    Cancelar
                                </a>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>