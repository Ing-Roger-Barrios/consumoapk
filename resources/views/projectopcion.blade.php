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
                    

                     
                        <div class="container">



@php
    $contratoMat = $comparacion->sum('contrato.total');
    $ejecMat = $comparacion->sum('ejecucion.total');

    $porcentajeMat = $contratoMat > 0 ? ($ejecMat / $contratoMat) * 100 : 0;

    // Colores dinámicos según avance
    if ($ejecMat == 0) {
        $colorMat = 'text-gray-300';
        $bgMat = 'bg-gray-100';
        $barMat = 'bg-gray-300';
    } elseif ($porcentajeMat < 90) {
        $colorMat = 'text-green-600';
        $bgMat = 'bg-green-50';
        $barMat = 'bg-green-500';
    } elseif ($porcentajeMat < 100) {
        $colorMat = 'text-red-400';
        $bgMat = 'bg-red-50';
        $barMat = 'bg-red-400';
    } else {
        $colorMat = 'text-red-700';
        $bgMat = 'bg-red-100';
        $barMat = 'bg-red-700';
    }
@endphp
@php
    $porcentajeMO = $totalManoObraDirecta > 0 ? ($totalEjecutado / $totalManoObraDirecta) * 100 : 0;

    if ($totalEjecutado == 0) {
        $colorMO = 'text-gray-300';
        $bgMO = 'bg-gray-100';
        $barMO = 'bg-gray-300';
    } elseif ($porcentajeMO < 90) {
        $colorMO = 'text-green-600';
        $bgMO = 'bg-green-50';
        $barMO = 'bg-green-500';
    } elseif ($porcentajeMO < 100) {
        $colorMO = 'text-red-400';
        $bgMO = 'bg-red-50';
        $barMO = 'bg-red-400';
    } else {
        $colorMO = 'text-red-700';
        $bgMO = 'bg-red-100';
        $barMO = 'bg-red-700';
    }
@endphp

@php
    // === EQUIPO Y MAQUINARIA ===
    $equipoContrato = $proyecto->equipoMaquinariaContrato->sum('total');
    $equipoEjec     = $proyecto->equipoMaquinariaEjecucion->sum('total');

    $porcentajeEq = $equipoContrato > 0 ? ($equipoEjec / $equipoContrato) * 100 : 0;

    if ($equipoEjec == 0) {
        $colorEq = 'text-gray-300';
        $bgEq    = 'bg-gray-100';
        $barEq   = 'bg-gray-300';
    } elseif ($porcentajeEq < 90) {
        $colorEq = 'text-green-600';
        $bgEq    = 'bg-green-50';
        $barEq   = 'bg-green-500';
    } elseif ($porcentajeEq < 100) {
        $colorEq = 'text-red-400';
        $bgEq    = 'bg-red-50';
        $barEq   = 'bg-red-400';
    } else {
        $colorEq = 'text-red-700';
        $bgEq    = 'bg-red-100';
        $barEq   = 'bg-red-700';
    }

    // === GASTOS GENERALES ===
    $gastosContrato = $proyecto->monto * 0.10;
    $gastosEjec     = $proyecto->gastosGenerales->sum('monto');

    $porcentajeGG = $gastosContrato > 0 ? ($gastosEjec / $gastosContrato) * 100 : 0;

    if ($gastosEjec == 0) {
        $colorGG = 'text-gray-300';
        $bgGG    = 'bg-gray-100';
        $barGG   = 'bg-gray-300';
    } elseif ($porcentajeGG < 90) {
        $colorGG = 'text-green-600';
        $bgGG    = 'bg-green-50';
        $barGG   = 'bg-green-500';
    } elseif ($porcentajeGG < 100) {
        $colorGG = 'text-red-400';
        $bgGG    = 'bg-red-50';
        $barGG   = 'bg-red-400';
    } else {
        $colorGG = 'text-red-700';
        $bgGG    = 'bg-red-100';
        $barGG   = 'bg-red-700';
    }


    // === BENEFICIOS SOCIALES ===
    $porcBen = $proyecto->monto * 0.05;
    $ejecBen = $proyecto->beneficiosSociales->sum('monto');

    $porcentajeBS = $porcBen > 0 ? ($ejecBen / $porcBen) * 100 : 0;

    if ($ejecBen == 0) {
        $colorBS = 'text-gray-300';
        $bgBS    = 'bg-gray-100';
        $barBS   = 'bg-gray-300';
    } elseif ($porcentajeBS < 90) {
        $colorBS = 'text-green-600';
        $bgBS    = 'bg-green-50';
        $barBS   = 'bg-green-500';
    } elseif ($porcentajeBS < 100) {
        $colorBS = 'text-red-400';
        $bgBS    = 'bg-red-50';
        $barBS   = 'bg-red-400';
    } else {
        $colorBS = 'text-red-700';
        $bgBS    = 'bg-red-100';
        $barBS   = 'bg-red-700';
    }


    // === iva ===
    $porcIva = $proyecto->monto * 0.1494;
    $ejecIva = $proyecto->ivaFacturas->sum('monto_factura');

    $porcentajeIva = $porcIva > 0 ? ($ejecIva / $porcIva) * 100 : 0;

    if ($ejecIva == 0) {
        $colorIva = 'text-gray-300';
        $bgIva    = 'bg-gray-100';
        $barIva   = 'bg-gray-300';
    } elseif ($porcentajeIva < 90) {
        $colorIva = 'text-green-600';
        $bgIva    = 'bg-green-50';
        $barIva   = 'bg-green-500';
    } elseif ($porcentajeIva < 100) {
        $colorIva = 'text-red-400';
        $bgIva    = 'bg-red-50';
        $barIva   = 'bg-red-400';
    } else {
        $colorIva = 'text-red-700';
        $bgIva    = 'bg-red-100';
        $barIva   = 'bg-red-700';
    }


    
        $ejecUtil = 0; // ejemplo
        $porcUtil = 0; // ejemplo
@endphp











                            
                           
                            
                            

                            <div class="overflow-x-auto">
                                <ul role="list" class="min-w-[450px] w-full text-left  ">

                                    <div class="bg-gray-100 dark:bg-gray-700 shadow-xl   hover:bg-gray-50 transition duration-150 ease-in-out px-2 py-2 ">
                                        
                                            <li class="grid grid-cols-4 gap-2 items-center ">
                                                <span class="col-span-2 text-body text-gray-900 truncate">
                                                    DESCRIPCION
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                     (%)
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    TOTAL
                                                </span>
                                            </li>
                                    </div>

                                    



                                    <div class="bg-white dark:bg-gray-800  shadow-xl  hover:bg-gray-50 transition duration-150 ease-in-out py-1  px-2 border-t border-gray-500">

                                        <a href="{{ route('mat.list', $proyecto) }}">
                                            
                                            <!-- FILA CONTRATO -->
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-gray-900">
                                                    <span class="font-semibold">MATERIALES</span>
                                                </div>

                                                <span class="col-span-1 text-right text-gray-600 truncate">
                                                    {{ number_format(($contratoMat / $proyecto->monto) * 100, 2) }}%
                                                </span>

                                                <span class="col-span-1 text-right text-gray-600 truncate">
                                                    Bs {{ number_format($contratoMat, 2) }}
                                                </span>
                                            </li>

                                            <!-- FILA EJECUCIÓN -->
                                            <div class="{{ $bgMat }} px-1 py-1 rounded-md mt-1">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    
                                                    <span class="col-span-2 {{ $colorMat }}">
                                                        Materiales Ejec.
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorMat }}">
                                                        {{ number_format($porcentajeMat, 2) }}%
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorMat }}">
                                                        Bs {{ number_format($ejecMat, 2) }}
                                                    </span>
                                                </div>

                                                <!-- PROGRESS BAR -->
                                                <div class="w-full h-2 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-2 {{ $barMat }}" style="width: {{ min($porcentajeMat, 100) }}%"></div>
                                                </div>
                                            </div>

                                        </a>

                                    </div>

                                    
                                    <div class="bg-white shadow-xl  hover:bg-gray-50 transition duration-150 ease-in-out  border-t py-1 px-2 border-gray-500">

                                        <a href="{{ route('mano.obra.contrato.index', $proyecto) }}">  

                                            <!-- CONTRATO -->
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-gray-900">
                                                    <span class="font-semibold">MANO DE OBRA</span>
                                                </div>

                                                <span class="col-span-1 text-right text-gray-600 truncate">
                                                    {{ number_format(($totalManoObraDirecta / $proyecto->monto) * 100, 2) }}%
                                                </span>

                                                <span class="col-span-1 text-right text-gray-600">
                                                    Bs {{ number_format($totalManoObraDirecta, 2) }}
                                                </span>
                                            </li>

                                            <!-- EJECUCION -->
                                            <div class="{{ $bgMO }} px-1 py-1 rounded-md mt-1">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    
                                                    <span class="col-span-2 {{ $colorMO }}">
                                                        Mano de Obra Ejec.
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorMO }}">
                                                        {{ number_format($porcentajeMO, 2) }}%
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorMO }}">
                                                        Bs {{ number_format($totalEjecutado, 2) }}
                                                    </span>

                                                </div>

                                                <!-- PROGRESS BAR -->
                                                <div class="w-full h-2 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-2 {{ $barMO }}" style="width: {{ min($porcentajeMO, 100) }}%"></div>
                                                </div>            
                                            </div>

                                        </a>  

                                    </div>


                                    <div class="bg-white dark:bg-gray-800   hover:bg-gray-50 transition duration-150 ease-in-out py-1  px-2 border-t border-gray-500">

                                        <a href="{{ route('equipo.contrato.index', $proyecto) }}">
                                            
                                            <!-- CONTRATO -->
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-gray-900">
                                                    <span class="font-semibold">EQUIPO Y MAQ.</span>
                                                </div>

                                                <span class="col-span-1 text-right text-gray-600 truncate">
                                                    {{ number_format(($equipoContrato / $proyecto->monto) * 100, 2) }}%
                                                </span>

                                                <span class="col-span-1 text-right text-gray-600">
                                                    Bs {{ number_format($equipoContrato, 2) }}
                                                </span>
                                            </li>

                                            <!-- EJECUCION -->
                                            <div class="{{ $bgEq }} px-1 py-1 rounded-md mt-1">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    
                                                    <span class="col-span-2 {{ $colorEq }}">
                                                        Equipo y Maq. Ejec.
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorEq }}">
                                                        {{ number_format($porcentajeEq, 2) }}%
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorEq }}">
                                                        Bs {{ number_format($equipoEjec, 2) }}
                                                    </span>
                                                </div>

                                                <!-- PROGRESS BAR -->
                                                <div class="w-full h-2 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-2 {{ $barEq }} transition-all duration-500" style="width: {{ min($porcentajeEq, 100) }}%"></div>
                                                </div>
                                            </div>

                                        </a>

                                    </div>


                                    <div class="bg-white dark:bg-gray-800   hover:bg-gray-50 transition duration-150 ease-in-out px-1 py-2 border-t border-gray-500">

                                        <a href="{{ route('gastos.index', $proyecto) }}">
                                            
                                            <!-- CONTRATO -->
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <span class="font-semibold">GASTOS GRAL.</span>
                                                </div>

                                                <span class="col-span-1 text-right text-gray-600 truncate">
                                                    {{ number_format(($gastosContrato / $proyecto->monto) * 100, 2) }}%
                                                </span>

                                                <span class="col-span-1 text-right text-gray-600">
                                                    Bs {{ number_format($gastosContrato, 2) }}
                                                </span>
                                            </li>

                                            <!-- EJECUCION -->
                                            <div class="{{ $bgGG }} px-1 py-1 rounded-md mt-1">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    
                                                    <span class="col-span-2 {{ $colorGG }}">
                                                        Gastos Gral. Ejec.
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorGG }}">
                                                        {{ number_format($porcentajeGG, 2) }}%
                                                    </span>

                                                    <span class="col-span-1 text-right {{ $colorGG }}">
                                                        Bs {{ number_format($gastosEjec, 2) }}
                                                    </span>

                                                </div>

                                                <!-- PROGRESS BAR -->
                                                <div class="w-full h-2 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-2 {{ $barGG }} transition-all duration-500" style="width: {{ min($porcentajeGG, 100) }}%"></div>
                                                </div>
                                            </div>

                                        </a>

                                    </div>


                                    <div class="bg-white dark:bg-gray-800    hover:bg-gray-50 transition duration-150 ease-in-out px-2 py-1 border-t border-gray-500 ">
                                        <a href="{{ route('beneficios.index', $proyecto) }}">
                                            <!-- CONTRATO -->
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <span class="font-semibold">BENEFICIOS SOC.</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    5.00%
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    Bs {{number_format($proyecto->monto * 0.05, 2)}}
                                                </span>
                                            </li>
                                            <!-- EJECUCION -->
                                            <div class="{{ $bgBS }} px-1 py-1 rounded-md mt-1">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 {{ $colorBS }}"> 
                                                        Beneficios Soc. Ejec.
                                                    </span>
                                                    <span class="col-span-1 text-right {{ $colorBS }}">
                                                        {{ number_format($porcentajeBS, 2) }}%
                                                    </span>
                                                    <span class="col-span-1 text-right {{ $colorBS }}">
                                                        Bs {{ number_format($proyecto->beneficiosSociales->sum('monto'), 2) }}
                                                    </span>
                                                </div>
                                                <!-- PROGRESS BAR -->
                                                <div class="w-full h-2 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-2 {{ $barBS }} transition-all duration-500" style="width: {{ min($porcentajeBS, 100) }}%"></div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800   hover:bg-gray-50 transition duration-150 ease-in-out py-1 px-2 border-t border-gray-500">
                                        <a href="#">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="font-semibold">UTILIDADES</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    10.00%</span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    Bs {{number_format($proyecto->monto * 0.10, 2)}}
                                                </span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> 
                                                        Utilidades
                                                    </span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">
                                                        <!--porcentaje -->
                                                    </span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">
                                                        Bs 10%
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800   hover:bg-gray-50 transition duration-150 ease-in-out  py-1 px-2 border-t border-gray-500">
                                        <a href="{{ route('it.edit', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="font-semibold">IT</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">{{ $proyecto->it ? number_format($proyecto->it->porcentaje, 2) : '3.09' }}%</span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{ $proyecto->it ? number_format($proyecto->it->monto, 2) : number_format($proyecto->monto * 0.0309, 2) }}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> It Ejec.</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">Bs {{ $proyecto->it ? number_format($proyecto->it->monto, 2) : number_format($proyecto->monto * 0.0309, 2) }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800   hover:bg-gray-50 transition duration-150 ease-in-out  py-1 px-2 border-t border-gray-500">
                                        <a href="{{ route('iva.index', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <span class="font-semibold">IVA</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    {{ $proyecto->ivaFacturas->sum('porcentaje_iva') > 0 ? number_format($proyecto->ivaFacturas->avg('porcentaje_iva'), 2) : '14.94' }}%
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    Bs {{number_format($proyecto->monto * (( $proyecto->ivaFacturas->sum('porcentaje_iva') > 0 ? number_format($proyecto->ivaFacturas->avg('porcentaje_iva'), 2) : '14.94')/100),2) }}
                                                </span>
                                            </li>
                                            <!-- EJECUCION -->
                                            <div class="{{ $bgIva }} px-1 py-1 rounded-md mt-1">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 {{ $colorIva }}"> 
                                                        Iva Ejec.
                                                    </span>
                                                    <span class="col-span-1 text-right {{ $colorIva }}">
                                                        {{ number_format($porcentajeIva, 2) }}%
                                                    </span>
                                                    <span class="col-span-1 text-right {{ $colorGG }}">
                                                        Bs {{ number_format($proyecto->ivaFacturas->sum('monto_iva'), 2) }}
                                                    </span>
                                                </div>
                                                <!-- PROGRESS BAR -->
                                                <div class="w-full h-2 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                    <div class="h-2 {{ $barIva }} transition-all duration-500" style="width: {{ min($porcentajeIva, 100) }}%"></div>
                                                </div>
                                            </div>
                                            
                                        </a>
                                    </div>

                                </ul>
                            </div>










                            
                        </div>



                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>