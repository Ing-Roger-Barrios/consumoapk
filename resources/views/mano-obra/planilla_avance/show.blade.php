<x-app-layout>
<div class="py-3">
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

<div class="flex justify-between mb-4 px-2 print:hidden">
    <div>
        <h1 class="text-xl font-bold">
            Planilla Avance
        </h1>
        <p class="text-sm text-gray-500">
            {{ $planilla->semana_inicio->format('d/m/Y') }}
            -
            {{ $planilla->semana_fin->format('d/m/Y') }}
        </p>
    </div>

    <button onclick="window.print()"
            class="bg-gray-700 text-white px-4 py-2 rounded-lg">
        🖨️ Imprimir
    </button>
</div>

<div class="bg-white rounded-xl shadow overflow-x-auto" id="planilla-print">

<table class="min-w-[1000px] w-full text-sm">
<thead class="bg-gray-50">
<tr class="text-xs uppercase text-gray-500">
    <th class="px-4 py-3 text-left">Trabajador</th>
    <th class="px-4 py-3 text-left">Item</th>
    <th class="px-4 py-3 text-center">% Avance</th>
    <th class="px-4 py-3 text-right">Monto</th>
</tr>
</thead>

<tbody class="divide-y">

@foreach($planilla->detalles as $detalle)

<tr class="bg-gray-100 font-semibold">
    <td class="px-4 py-3">
        {{ $detalle->trabajador->nombre }}
    </td>
    <td colspan="2"></td>
    <td class="px-4 py-3 text-right text-green-700">
        Bs {{ number_format($detalle->total_monto, 2) }}
    </td>
</tr>

@foreach($detalle->avances as $avance)
<tr>
    <td></td>
    <td class="px-4 py-2">
        {{ $avance->mano_obra_item->descripcion }}
    </td>
    <td class="px-4 py-2 text-center">
        {{ $avance->porcentaje_avance }} %
    </td>
    <td class="px-4 py-2 text-right">
        Bs {{ number_format($avance->monto_pagar, 2) }}
    </td>
</tr>
@endforeach

@endforeach

</tbody>

<tfoot class="bg-gray-50 border-t-2">
<tr>
    <td colspan="3" class="px-4 py-3 text-right font-bold">
        TOTAL A PAGAR:
    </td>
    <td class="px-4 py-3 text-right font-bold text-xl text-green-700">
        Bs {{ number_format($planilla->total_pagar, 2) }}
    </td>
</tr>
</tfoot>

</table>
</div>

{{-- SUBIR CONSTANCIA --}}
@if(!$planilla->archivo_constancia)
<div class="mt-6 print:hidden">
    <form action="{{ route('planilla_avance.subirConstancia', $planilla) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white p-4 rounded-xl shadow">
        @csrf

        <label class="block text-sm font-medium mb-2">
            Subir planilla firmada (PDF o imagen)
        </label>

        <input type="file" name="archivo"
               class="border rounded px-3 py-2 w-full mb-3"
               required>

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Guardar constancia
        </button>
    </form>
</div>
@else
<div class="mt-6 bg-green-50 border border-green-200 p-4 rounded print:hidden">
    <strong>Planilla pagada.</strong><br>
    <a href="{{ $planilla->archivo_constancia }}"
       target="_blank"
       class="text-blue-600 underline">
        Ver constancia
    </a>
</div>
@endif

</div>
</div>

<style>
@media print {
    nav, header, .print\:hidden { display: none !important; }
    #planilla-print { box-shadow: none; border: none; }
    body { background: white; }
}
</style>

</x-app-layout>