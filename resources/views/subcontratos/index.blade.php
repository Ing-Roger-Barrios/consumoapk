<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold" >
                    <x-back-button :href="route('mano.obra.contrato.index', $proyecto)" label=""/>
               Subcontratos 

            </h1>
                <a href="{{ route('subcontratos.create', $proyecto) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    + Nuevo Subcontrato
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900/30 dark:border-red-700 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @if($subcontratos->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500">No hay subcontratos registrados.</p>
                    <a href="{{ route('subcontratos.create', $proyecto) }}" 
                       class="mt-2 text-blue-500 hover:underline inline-block">
                        Registrar el primero
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($subcontratos as $subcontrato)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $subcontrato->nombre }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300">{{ $subcontrato->descripcion }}</p>
                                    <!-- Enlace al contrato -->
                                    @if($subcontrato->contrato)
                                        <p class="text-sm mt-2">
                                            <a href="{{ $subcontrato->contrato }}" target="_blank"
                                            class="text-green-600 hover:text-green-800 font-medium inline-flex items-center">
                                                📄 Ver contrato
                                            </a>
                                        </p>
                                    @endif
                                    @if($subcontrato->notas)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $subcontrato->notas }}</p>
                                    @endif
                                </div>
                                <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-medium">
                                    Acordado: Bs {{ number_format($subcontrato->monto_acordado, 2) }}
                                </span>
                            </div>

                            <!-- Progreso de pagos -->
                            <div class="mt-4">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                                    <span>Pagado: Bs {{ number_format($subcontrato->monto_pagado, 2) }}</span>
                                    <span>Pendiente: Bs {{ number_format($subcontrato->saldo_pendiente, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" 
                                         style="width: {{ $subcontrato->porcentaje_completado }}%"></div>
                                </div>
                                <div class="text-right text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ number_format($subcontrato->porcentaje_completado, 1) }}% completado
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('pagos.create', $subcontrato) }}" 
                                   class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                    Registrar Pago
                                </a>
                                <a href="{{ route('pagos.index', $subcontrato) }}" 
                                   class="px-3 py-1.5 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                                    Ver Pagos ({{ $subcontrato->pagos->count() }})
                                </a>
                                <a href="{{ route('subcontratos.edit', [$proyecto, $subcontrato]) }}" 
                                   class="px-3 py-1.5 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">
                                    Editar
                                </a>
                                <form action="{{ route('subcontratos.destroy', [$proyecto, $subcontrato]) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este subcontrato?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700"
                                            {{ $subcontrato->pagos->count() > 0 ? 'disabled' : '' }}>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>