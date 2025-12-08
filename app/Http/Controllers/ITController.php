<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\IT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ITController extends Controller
{
    public function edit(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        
        // Crear registro IT si no existe
        $it = $proyecto->it()->firstOrCreate(
            ['proyecto_id' => $proyecto->id],
            ['porcentaje' => 3.09]
        );

        return view('it.edit', compact('proyecto', 'it'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);

        $request->validate([
            'porcentaje' => 'required|numeric|min:0|max:100',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $it = $proyecto->it()->firstOrCreate(
            ['proyecto_id' => $proyecto->id],
            ['porcentaje' => 3.09]
        );

        $comprobantePath = $it->comprobante;
        if ($request->hasFile('comprobante')) {
            if ($it->comprobante) {
                Storage::disk('public')->delete($it->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes/it', 'public');
        }

        $it->update([
            'porcentaje' => $request->porcentaje,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
            // El monto se calcula automáticamente en el modelo
        ]);

        return redirect()
            ->route('proy', $proyecto)
            ->with('success', 'Impuesto a las Transferencias actualizado exitosamente.');
    }

    private function authorizeAccess(Proyecto $proyecto)
    {
        $user = Auth::user();
        // Verificar si el usuario tiene acceso al proyecto
        if ($user->role === 'contractor') {
            // Solo puede ver sus propios proyectos
            if ($proyecto->user_id !== $user->id) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } elseif ($user->role === 'resident') {
            // Solo puede ver proyectos en los que está asignado
            if (!$proyecto->residentes()->where('users.id', $user->id)->exists()) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } else {
            abort(403, 'Acceso denegado');
        }
    }
}