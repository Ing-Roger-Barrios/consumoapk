
<x-app-layout>
        
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Presupuesto -->
        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
            <h3 class="font-bold text-blue-800 dark:text-blue-200">Presupuesto Mano de Obra</h3>
            <p class="text-2xl font-semibold">Bs {{ number_format($proyecto->manoObraContrato->sum('monto_presupuestado'), 2) }}</p>
        </div>
        
        <!-- Ejecutado -->
        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
            <h3 class="font-bold text-green-800 dark:text-green-200">Pagos Realizados</h3>
            <p class="text-2xl font-semibold">Bs {{ number_format($proyecto->subcontratos->sum('monto_pagado'), 2) }}</p>
        </div>
    </div>

    <!-- Subcontratos -->
    @foreach($proyecto->subcontratos as $subcontrato)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold">{{ $subcontrato->nombre }}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $subcontrato->descripcion }}</p>
                </div>
                <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 px-2 py-1 rounded text-sm">
                    Acordado: Bs {{ number_format($subcontrato->monto_acordado, 2) }}
                </span>
            </div>
            
            <!-- Progreso de pagos -->
            <div class="mt-3">
                <div class="flex justify-between text-sm mb-1">
                    <span>Pagado: Bs {{ number_format($subcontrato->monto_pagado, 2) }}</span>
                    <span>Pendiente: Bs {{ number_format($subcontrato->saldo_pendiente, 2) }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" 
                        style="width: {{ $subcontrato->monto_acordado > 0 ? ($subcontrato->monto_pagado / $subcontrato->monto_acordado * 100) : 0 }}%">
                    </div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="mt-3 flex space-x-2">
                <a href="{{ route('pagos.create', $subcontrato) }}" 
                class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                    Registrar Pago
                </a>
                <a href="{{ route('pagos.index', $subcontrato) }}" 
                class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                    Ver Pagos
                </a>
            </div>
        </div>
    @endforeach
</x-app-layout>