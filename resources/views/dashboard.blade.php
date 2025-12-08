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
                        @if(auth()->user()->role === 'contractor')
                            Mis Proyectos 
                            <span class="text-xs font-semibold uppercase text-gray-400">
                                <a href="{{ route('newproy') }}"> - [CREAR NUEVO]</a>
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
                        <div class="space-y-4 grid  md:grid-cols-2 gap-4">
                            @foreach($proy as $proyecto)
                                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out p-4 mb-2">
                                    <h2 class="text-lg font-bold"><strong>{{ $proyecto->nombre }}</strong></h2>
                                    <p><strong>Cliente:</strong> {{ $proyecto->cliente }}</p>
                                    <p><strong>Monto:</strong> {{ number_format($proyecto->monto, 2) }} Bs</p>
                                    
                                    @if(auth()->user()->role === 'contractor')
                                        <p><strong>Residentes asignados:</strong>
                                            @if($proyecto->residentes->count())
                                                {{ $proyecto->residentes->pluck('name')->join(', ') }}
                                            @else
                                                <span class="text-gray-500">Ninguno</span>
                                            @endif
                                        </p>
                                    @endif
                                        <hr>
                                        <div class="flex items-center justify-between mt-3">
                                            <a href="{{ route('proy', $proyecto) }}" class="text-blue-500 hover:underline text-sm">Ver detalles</a>

                                            
                                                
                                            
                                            
                                            @if(auth()->user()->role === 'contractor')
                                                <a href="{{ route('proy.comparacion', $proyecto) }}" 
                                                    class="inline-block px-4 py-2  text-white rounded-md hover:bg-purple-700 text-sm">
                                                    📊 
                                                </a>
                                                <a href="{{ route('proy.edit', $proyecto) }}" class="inline-block px-4 py-2  text-white rounded-md hover:bg-purple-700 text-sm">✏️</a>
                                                <form action="{{ route('proy.destroy', $proyecto) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-block px-4 py-2  text-white rounded-md hover:bg-purple-700 text-sm"
                                                            onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">
                                                        ❌
                                                    </button>
                                                </form>
                                            @endif
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
