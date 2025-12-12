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
                           
                            
                            

                            <div class=" ">
                                <ul role="list" class="space-y-4 ">

                                    <div class="bg-gray-100 dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2 mb-2">
                                        
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

                                    



                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2 mb-2">
                                        <a href="{{ route('mat.list', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body text-gray-900">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">MATERIALES</span>
                                                </div>
                                                 
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right truncate">
                                                    {{ number_format(($matCont / $proyecto->monto) * 100, 2) }}%
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right truncate">Bs {{ number_format($matCont, 2) }}</span>
                                                
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> Materiales Ejec.</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right truncate"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right truncate">Bs {{ number_format($comparacion->sum('ejecucion.total'), 2) }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2 mb-2">
                                        <a href="{{ route('mano.obra.contrato.index', $proyecto) }}">  
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">MANO DE OBRA</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right truncate">
                                                    {{ number_format(($manodeobra / $proyecto->monto) * 100, 2) }}%
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{number_format($manodeobra, 2)}}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> Mano de Obra Ejec.</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">Bs {{number_format($totalEjecutado, 2)}}</span>
                                                </div>
                                            </div>
                                        </a>  
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2  mb-2">
                                        <a href="{{ route('equipo.contrato.index', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">EQUIPO Y MAQ.</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right truncate">
                                                    {{ number_format(($totalHerrEquipo / $proyecto->monto) * 100, 2) }}%
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{number_format($totalHerrEquipo, 2)}}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> Equipo y Maq. Ejec.</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">Bs {{number_format($proyecto->equipoMaquinariaEjecucion->sum('total'), 2)}}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2  mb-2">
                                        <a href="{{ route('gastos.index', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">GASTOS GRAL.</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">
                                                    {{number_format(($gastosgral / $proyecto->monto) * 100, 2)}}%
                                                </span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{number_format($gastosgral, 2)}}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> Gastos Gral. Ejec.</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">Bs {{number_format($proyecto->gastosGenerales->sum('monto'), 2)}}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2  mb-2">
                                        <a href="{{ route('beneficios.index', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">BENEFICIOS SOC.</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">{{number_format(($beneficiosSoc / $proyecto->monto) * 100, 2)}}%</span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{number_format($beneficiosSoc, 2)}}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> Beneficios Soc. Ejec.</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">Bs {{ number_format($proyecto->beneficiosSociales->sum('monto'), 2) }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2  mb-2">
                                        <a href="#">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">UTILIDADES</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">{{number_format(($utilidad / $proyecto->monto) * 100, 2)}}%</span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{number_format($utilidad, 2)}}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> Utilidades</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"> </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2  mb-2">
                                        <a href="{{ route('it.edit', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">IT</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">{{  number_format(($it / $proyecto->monto) * 100, 2) }}%</span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{ number_format($it, 2)}}</span>
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

                                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2  mb-2">
                                        <a href="{{ route('iva.index', $proyecto) }}">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">IVA</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">{{ number_format(($iva / $proyecto->monto) * 100, 2) }}%</span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{number_format($iva,2) }}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> Iva Ejec.</span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right">Bs {{ number_format($proyecto->ivaFacturas->sum('monto_iva'), 2) }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="bg-gray-100 dark:bg-gray-800 shadow-xl rounded-lg  hover:bg-gray-50 transition duration-150 ease-in-out px-4 py-2  mb-2">
                                        <a href="#">
                                            <li class="grid grid-cols-4 gap-2 items-center">
                                                <div class="col-span-2 flex items-center text-body">
                                                    <svg class="w-5 h-5 shrink-0 text-fg-brand me-1.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                    <span class="text-body text-gray-900">Total</span>
                                                </div>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">100%</span>
                                                <span class="col-span-1 text-body font-medium text-gray-600 text-right">Bs {{number_format($proytotal,2) }}</span>
                                            </li>
                                            <div class=" ">
                                                <div class="grid grid-cols-4 gap-2 items-center">
                                                    <span class="col-span-2 text-body text-gray-300"> </span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"><!--porcentaje --></span>
                                                    <span class="col-span-1 text-body font-medium text-gray-300 text-right"> </span>
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