<x-app-layout>
    <div class="py-3">

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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-xl sm:text-2xl font-semibold mb-6">
                @if(auth()->user()->role === 'contractor')
                    Mis Proyectos
                    <span class="block sm:inline text-xs font-semibold uppercase text-gray-400 mt-1 sm:mt-0">
                        <a href="{{ route('newproy') }}" class="hover:text-indigo-600">[CREAR NUEVO]</a>
                        <a href="{{ route('residents.index') }}" class="ml-2 hover:text-indigo-600">[ADM RESIDENTES]</a>
                    </span>
                @else
                    Proyectos Asignados
                @endif
            </h1>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($proy->isEmpty())
                        <p class="text-gray-500">
                            @if(auth()->user()->role === 'contractor')
                                Aún no has creado proyectos.
                                <a href="{{ route('proy.create') }}" class="text-blue-500 underline">Crear uno</a>.
                            @else
                                No tienes proyectos asignados.
                            @endif
                        </p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($proy as $proyecto)
                                <div class="group relative bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 
                                            rounded-2xl p-5 sm:p-6 shadow-sm hover:shadow-xl 
                                            transition-all duration-300">

                                    {{-- Header Responsive --}}
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                                        <div class="flex-1">
                                            <h2 class="text-base sm:text-lg font-semibold 
                                                text-gray-800 dark:text-white 
                                                group-hover:text-indigo-600 
                                                transition leading-snug break-words">
                                                {{ $proyecto->nombre }}
                                            </h2>

                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $proyecto->cliente }}
                                            </p>
                                        </div>

                                        {{-- Monto --}}
                                        <div class="sm:text-right">
                                            <span class="inline-block text-xs uppercase text-gray-400 tracking-wide">
                                                Monto
                                            </span>
                                            <p class="text-base sm:text-lg font-bold text-emerald-600">
                                                {{ number_format($proyecto->monto, 2) }} Bs
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Residentes --}}
                                    @if(auth()->user()->role === 'contractor')
                                        <div class="mt-4">
                                            <p class="text-xs uppercase text-gray-400 tracking-wide mb-1">
                                                Residentes
                                            </p>

                                            @if($proyecto->residentes->count())
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $proyecto->residentes->pluck('name')->join(', ') }}
                                                </p>
                                            @else
                                                <p class="text-sm text-gray-400">
                                                    Ninguno asignado
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>

                                    {{-- Actions Responsive --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                                        <a href="{{ route('proy', $proyecto) }}"
                                        class="whitespace-nowrap w-full sm:w-auto text-center px-3 py-2 text-xs font-medium rounded-lg 
                                                bg-indigo-500/10 text-indigo-600 
                                                hover:bg-indigo-500 hover:text-white transition">
                                            Ver Detalles →
                                        </a>

                                        <div class="flex flex-wrap gap-2 w-full sm:w-auto">

                                            <a href="{{ route('libro.index', $proyecto) }}"
                                            class="whitespace-nowrap text-center px-3 py-2 text-xs font-medium rounded-lg 
                                                    bg-emerald-500/10 text-emerald-600 
                                                    hover:bg-emerald-500 hover:text-white transition">
                                                Libro
                                            </a>

                                            @if(auth()->user()->role === 'contractor')

                                                <a href="{{ route('proy.comparacion', $proyecto) }}"
                                                class="whitespace-nowrap text-center px-3 py-2 text-xs font-medium rounded-lg 
                                                        bg-purple-500/10 text-purple-600 
                                                        hover:bg-purple-600 hover:text-white transition">
                                                    Comparar
                                                </a>

                                                <a href="{{ route('proy.edit', $proyecto) }}"
                                                class="whitespace-nowrap text-center px-3 py-2 text-xs font-medium rounded-lg 
                                                        bg-amber-500/10 text-amber-600 
                                                        hover:bg-amber-500 hover:text-white transition">
                                                    Editar
                                                </a>

                                                <form action="{{ route('proy.destroy', $proyecto) }}" method="POST" class="col-span-2 sm:col-auto">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('¿Estás seguro de eliminar este proyecto?')"
                                                            class="whitespace-nowrap w-full text-center px-3 py-2 text-xs font-medium rounded-lg 
                                                                bg-red-500/10 text-red-600 
                                                                hover:bg-red-600 hover:text-white transition">
                                                        Eliminar
                                                    </button>
                                                </form>

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>