<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\BeneficioSocial;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeneficioSocialController extends Controller
{
    public function index(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        $beneficios = $proyecto->beneficiosSociales()->latest()->get();
        return view('beneficios.index', compact('proyecto', 'beneficios'));
    }

    public function create(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        $tipos = BeneficioSocial::tiposBeneficios();
        return view('beneficios.create', compact('proyecto', 'tipos'));
    }

    public function store(Request $request, Proyecto $proyecto,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($proyecto);

        $request->validate([
            'tipo_beneficio' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $comprobantePath = null;
        /*if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes/beneficios', 'public');
        }*/
            if ($request->hasFile('comprobante')) {

            $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/beneficios/' . $proyecto->id );

            $comprobantePath = $url;
        }

        BeneficioSocial::create([
            'proyecto_id' => $proyecto->id,
            'tipo_beneficio' => $request->tipo_beneficio,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('beneficios.index', $proyecto)
            ->with('success', 'Beneficio social registrado exitosamente.');
    }

    public function edit(Proyecto $proyecto, BeneficioSocial $beneficio)
    {
        $this->authorizeAccess($proyecto);
        if ($beneficio->proyecto_id !== $proyecto->id) {
            abort(403);
        }
        $tipos = BeneficioSocial::tiposBeneficios();
        return view('beneficios.edit', compact('proyecto', 'beneficio', 'tipos'));
    }

    public function update(Request $request, Proyecto $proyecto, BeneficioSocial $beneficio,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($proyecto);
        if ($beneficio->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        $request->validate([
            'tipo_beneficio' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $comprobantePath = $beneficio->comprobante;
        /*if ($request->hasFile('comprobante')) {
            if ($beneficio->comprobante) {
                Storage::disk('public')->delete($beneficio->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes/beneficios', 'public');
        }*/
        if ($request->hasFile('comprobante')) {

                 $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/beneficios/' . $proyecto->id );

            $comprobantePath = $url;
        }

        $beneficio->update([
            'tipo_beneficio' => $request->tipo_beneficio,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('beneficios.index', $proyecto)
            ->with('success', 'Beneficio social actualizado exitosamente.');
    }

    public function destroy(Proyecto $proyecto, BeneficioSocial $beneficio)
    {
        $this->authorizeAccess($proyecto);
        if ($beneficio->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        if ($beneficio->comprobante) {
            Storage::disk('public')->delete($beneficio->comprobante);
        }

        $beneficio->delete();

        return redirect()
            ->route('beneficios.index', $proyecto)
            ->with('success', 'Beneficio social eliminado exitosamente.');
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