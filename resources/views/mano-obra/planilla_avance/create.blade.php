<x-app-layout>
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif
<div class="py-4">
<div class="max-w-xl mx-auto sm:px-6 lg:px-8">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

        <h1 class="text-xl font-bold mb-4">
            Generar Planilla de Avance
        </h1>

        <form action="{{ route('planilla_avance.store', $proyecto) }}"
              method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Semana Inicio
                </label>
                <input type="date" name="semana_inicio"
                       class="w-full border rounded-lg px-3 py-2"
                       required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Semana Fin
                </label>
                <input type="date" name="semana_fin"
                       class="w-full border rounded-lg px-3 py-2"
                       required>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Generar
            </button>
        </form>

    </div>

</div>
</div>
</x-app-layout>