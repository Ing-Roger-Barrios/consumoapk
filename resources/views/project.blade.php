<x-app-layout>
    <div class="py-3">

        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ENCABEZADO DEL PROYECTO --}}
            <div class="flex items-center justify-between mb-4 px-2">
                <div class="flex items-center gap-3">
                    <x-back-button :href="route('dashboard', $proyecto)" label="" />
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $proyecto->nombre }}</h1>
                        <p class="text-sm text-gray-500">{{ $proyecto->cliente }} · Bs {{ number_format($proyecto->monto, 2) }}</p>
                    </div>
                </div>
                {{-- BOTÓN LIBRO CONTABLE --}}
                <a href="{{ route('libro.index', $proyecto) }}"
                   class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-3 py-2 rounded-lg shadow-sm transition">
                    📒 <span>Libro Contable</span>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">

                    {{-- CABECERA DE COLUMNAS --}}
                    <div class="grid grid-cols-12 gap-2 px-3 py-2 mb-1 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-500 uppercase">
                        <span class="col-span-4">Concepto</span>
                        <span class="col-span-3 text-right">Contrato</span>
                        <span class="col-span-3 text-right">Ejecución</span>
                        <span class="col-span-2 text-right">Avance</span>
                    </div>

                    <ul class="space-y-2">

                        {{-- MACRO para no repetir código en cada ítem --}}
                        @php
                        function itemRow($label, $route, $contrato, $ejecucion, $monto) {
                            $pct = $contrato > 0 ? min(($ejecucion / $contrato) * 100, 100) : 0;
                            if ($ejecucion == 0)             { $bar = 'bg-gray-300';  $pctColor = 'text-gray-400'; }
                            elseif ($ejecucion < $contrato * 0.9) { $bar = 'bg-green-500'; $pctColor = 'text-green-600'; }
                            elseif ($ejecucion < $contrato)  { $bar = 'bg-yellow-400'; $pctColor = 'text-yellow-600'; }
                            else                             { $bar = 'bg-red-500';   $pctColor = 'text-red-600'; }
                            return compact('pct', 'bar', 'pctColor');
                        }
                        @endphp

                        {{-- MATERIALES --}}
                        @php
                            $ejMat = $comparacion->sum('ejecucion.total');
                            $r = itemRow('', '', $matCont, $ejMat, $proyecto->monto);
                        @endphp
                        <li>
                            <a href="{{ route('mat.list', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">🧱</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">Materiales</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($matCont / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($matCont, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($ejMat, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- MANO DE OBRA --}}
                        @php $r = itemRow('', '', $manodeobra, $totalEjecutado, $proyecto->monto); @endphp
                        <li>
                            <a href="{{ route('mano.obra.hub', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">👷</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">Mano de Obra</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($manodeobra / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($manodeobra, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($totalEjecutado, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- EQUIPO Y MAQUINARIA --}}
                        @php
                            $ejEquipo = $proyecto->equipoMaquinariaEjecucion->sum('total');
                            $r = itemRow('', '', $totalHerrEquipo, $ejEquipo, $proyecto->monto);
                        @endphp
                        <li>
                            <a href="{{ route('equipo.contrato.index', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">🚜</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">Equipo y Maq.</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($totalHerrEquipo / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($totalHerrEquipo, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($ejEquipo, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- SUBCONTRATOS --}}
                        @php
                            $subContrato = $proyecto->subcontratos->sum('monto_acordado');
                            $subEjec = $proyecto->subcontratos->sum(fn($s) => $s->pagos->sum('monto_pagado'));
                            $r = itemRow('', '', $subContrato, $subEjec, $proyecto->monto);
                        @endphp
                        <li>
                            <a href="{{ route('subcontratos.index', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">📋</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">Subcontratos</p>
                                        <p class="text-xs text-gray-400">{{ number_format($subContrato > 0 ? ($subContrato / $proyecto->monto) * 100 : 0, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($subContrato, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($subEjec, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- GASTOS GENERALES --}}
                        @php $r = itemRow('', '', $gastosgral, $proyecto->gastosGenerales->sum('monto'), $proyecto->monto); @endphp
                        <li>
                            <a href="{{ route('gastos.index', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">🧾</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">Gastos Grales.</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($gastosgral / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($gastosgral, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    @php $ejGastos = $proyecto->gastosGenerales->sum('monto'); @endphp
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($ejGastos, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- BENEFICIOS SOCIALES --}}
                        @php $r = itemRow('', '', $beneficiosSoc, $proyecto->beneficiosSociales->sum('monto'), $proyecto->monto); @endphp
                        <li>
                            <a href="{{ route('beneficios.index', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">👥</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">Beneficios Soc.</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($beneficiosSoc / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($beneficiosSoc, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    @php $ejBen = $proyecto->beneficiosSociales->sum('monto'); @endphp
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($ejBen, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- IT --}}
                        @php
                            $ejIT = $proyecto->it ? $proyecto->it->monto : $proyecto->monto * 0.0309;
                            $r = itemRow('', '', $it, $ejIT, $proyecto->monto);
                        @endphp
                        <li>
                            <a href="{{ route('it.edit', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">🏛️</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">IT</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($it / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($it, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($ejIT, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- IVA --}}
                        @php
                            $ejIva = $proyecto->ivaFacturas->sum('monto_iva');
                            $r = itemRow('', '', $iva, $ejIva, $proyecto->monto);
                        @endphp
                        <li>
                            <a href="{{ route('iva.index', $proyecto) }}"
                               class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">🧮</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">IVA</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($iva / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($iva, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium {{ $r['pctColor'] }}">Bs {{ number_format($ejIva, 2) }}</p>
                                </div>
                                <div class="col-span-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="{{ $r['bar'] }} h-2 rounded-full" style="width:{{ $r['pct'] }}%"></div>
                                        </div>
                                        <span class="text-xs {{ $r['pctColor'] }} w-8 text-right">{{ number_format($r['pct'], 0) }}%</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        {{-- UTILIDADES --}}
                        <li>
                            <div class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-white dark:bg-gray-800 rounded-xl shadow opacity-60 cursor-default">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">💹</span>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800 dark:text-gray-100">Utilidades</p>
                                        <p class="text-xs text-gray-400">{{ number_format(($utilidad / $proyecto->monto) * 100, 1) }}% del contrato</p>
                                    </div>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-700">Bs {{ number_format($utilidad, 2) }}</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-medium text-gray-400">—</p>
                                </div>
                                <div class="col-span-2"></div>
                            </div>
                        </li>

                        {{-- TOTAL --}}
                        <li>
                            <div class="grid grid-cols-12 gap-2 items-center px-3 py-3 bg-gray-100 dark:bg-gray-700 rounded-xl shadow border border-gray-200 dark:border-gray-600">
                                <div class="col-span-4 flex items-center gap-2">
                                    <span class="text-lg">📊</span>
                                    <p class="font-bold text-sm text-gray-900 dark:text-gray-100">TOTAL</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Bs {{ number_format($proytotal, 2) }}</p>
                                    <p class="text-xs text-gray-400">100%</p>
                                </div>
                                <div class="col-span-3 text-right">
                                    @php
                                        $totalEjecucionGlobal = $ejMat + $totalEjecutado + $ejEquipo + $subEjec + $ejGastos + $ejBen + $ejIT + $ejIva;
                                    @endphp
                                    <p class="text-sm font-bold text-blue-600">Bs {{ number_format($totalEjecucionGlobal, 2) }}</p>
                                    <p class="text-xs text-gray-400">Ejecutado</p>
                                </div>
                                <div class="col-span-2">
                                    @php $pctGlobal = $proytotal > 0 ? min(($totalEjecucionGlobal / $proytotal) * 100, 100) : 0; @endphp
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 bg-gray-300 rounded-full h-2.5">
                                            <div class="bg-blue-500 h-2.5 rounded-full" style="width:{{ $pctGlobal }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-blue-600 w-8 text-right">{{ number_format($pctGlobal, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </li>

                    </ul>

                    {{-- LEYENDA DE COLORES --}}
                    <div class="flex items-center gap-4 mt-4 px-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Bajo presupuesto</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span> Cerca del límite</span>
                        <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Superado</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>