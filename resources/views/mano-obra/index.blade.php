<x-app-layout>
    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-6 px-2">
                <x-back-button :href="route('proy', $proyecto)" label="" />
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Mano de Obra</h1>
                    <p class="text-sm text-gray-500">{{ $proyecto->nombre }}</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">

                {{-- JORNAL --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">📅</span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Por Jornal</h2>
                            <p class="text-sm text-gray-500">Control diario Lun-Sáb, horas extra y planilla semanal</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('jornal.trabajadores', $proyecto) }}"
                           class="flex items-center justify-between w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 rounded-xl transition text-sm font-medium text-blue-700 dark:text-blue-300">
                            <span>👷 Gestionar Trabajadores</span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('jornal.planillas', $proyecto) }}"
                           class="flex items-center justify-between w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 rounded-xl transition text-sm font-medium text-blue-700 dark:text-blue-300">
                            <span>📋 Planillas Semanales</span>
                            <span>→</span>
                        </a>
                    </div>

                    @php
                        $totalJornal = $proyecto->planillasJornal()
                            ->with('detalles')
                            ->get()
                            ->sum(fn($p) => $p->detalles->sum(fn($d) => $d->total_neto));
                    @endphp
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-500">Total pagado por jornal</p>
                        <p class="text-xl font-bold text-blue-700">Bs {{ number_format($totalJornal, 2) }}</p>
                    </div>
                </div>

                {{-- POR ÍTEM --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">🏗️</span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">Por Ítem</h2>
                            <p class="text-sm text-gray-500">Pago por avance verificado con fotos</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('mano.obra.modulos.index', $proyecto) }}"
                           class="flex items-center justify-between w-full px-4 py-3 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 rounded-xl transition text-sm font-medium text-emerald-700 dark:text-emerald-300">
                            <span>📊 Ver Ítems Asignados</span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('mano.obra.item.index', $proyecto) }}"
                           class="flex items-center justify-between w-full px-4 py-3 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 rounded-xl transition text-sm font-medium text-emerald-700 dark:text-emerald-300">
                            <span>➕ Registrar Avance</span>
                            <span>→</span>
                        </a>
                        {{-- 🔵 NUEVO BOTÓN --}}
                        <a href="{{ route('planilla_avance.index', $proyecto) }}"
                        class="flex items-center justify-between w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 rounded-xl transition text-sm font-medium text-blue-700 dark:text-blue-300">
                            <span>🧾 Ver Planillas</span>
                            <span>→</span>
                        </a>
                    </div>

                    @php
                        $totalItem = \App\Models\ManoObraItemAvance::whereHas('asignacion', fn($q) => $q->where('proyecto_id', $proyecto->id))->sum('monto_pagar');
                    @endphp
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-500">Total pagado por ítem</p>
                        <p class="text-xl font-bold text-emerald-700">Bs {{ number_format($totalItem, 2) }}</p>
                    </div>
                </div>

            </div>

            {{-- TOTAL CONSOLIDADO --}}
            <div class="mt-6 bg-gray-800 dark:bg-gray-900 rounded-2xl p-5 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-400">Total Mano de Obra Ejecutada</p>
                        <p class="text-2xl font-bold">Bs {{ number_format($totalJornal + $totalItem, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Presupuestado en contrato</p>
                        <p class="text-lg font-semibold text-yellow-400">
                            Bs {{ number_format($proyecto->manoObraContrato->sum('monto_presupuestado'), 2) }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>