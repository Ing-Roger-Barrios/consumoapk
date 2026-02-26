<?php

namespace App\Http\Controllers;

use App\Models\Subcontrato;
use App\Models\PagoSubcontrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudinaryService;


class PagoSubcontratoController extends Controller
{
    /**
     * Lista todos los pagos de un subcontrato.
     */
    public function index(Subcontrato $subcontrato)
    {
        $this->authorizeAccess($subcontrato);
        $pagos = $subcontrato->pagos()->latest()->get();
        return view('pagos.index', compact('subcontrato', 'pagos'));
    }

    /**
     * Muestra el formulario para crear un nuevo pago.
     */
    public function create(Subcontrato $subcontrato)
    {
        $this->authorizeAccess($subcontrato);
        return view('pagos.create', compact('subcontrato'));
    }

    /**
     * Guarda un nuevo pago.
     */
    public function store(Request $request, Subcontrato $subcontrato,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($subcontrato);

        $request->validate([
            'monto_pagado' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $subcontrato->saldo_pendiente
            ],
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ], [
            'monto_pagado.max' => 'El monto no puede exceder el saldo pendiente de Bs ' . number_format($subcontrato->saldo_pendiente, 2),
        ]);

        $comprobantePath = null;
        /*if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes/pagos', 'public');
        }*/
        if ($request->hasFile('comprobante')) {

            $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/pagos/' . $subcontrato->id );

            $comprobantePath = $url;
        }

        PagoSubcontrato::create([
            'subcontrato_id' => $subcontrato->id,
            'monto_pagado' => $request->monto_pagado,
            'fecha_pago' => $request->fecha_pago,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('pagos.index', $subcontrato)
            ->with('success', 'Pago registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un pago.
     */
    public function edit(Subcontrato $subcontrato, PagoSubcontrato $pago)
    {
        $this->authorizeAccess($subcontrato);
        if ($pago->subcontrato_id !== $subcontrato->id) {
            abort(403);
        }
        return view('pagos.edit', compact('subcontrato', 'pago'));
    }

    /**
     * Actualiza un pago existente.
     */
    public function update(Request $request, Subcontrato $subcontrato, PagoSubcontrato $pago,  CloudinaryService $cloudinary)
    {
        $this->authorizeAccess($subcontrato);
        if ($pago->subcontrato_id !== $subcontrato->id) {
            abort(403);
        }

        // Calcular el monto que se liberaría al editar
        $montoLiberado = $pago->monto_pagado;
        $nuevoMonto = $request->monto_pagado;
        $nuevoSaldoPendiente = $subcontrato->saldo_pendiente + $montoLiberado;

        $request->validate([
            'monto_pagado' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $nuevoSaldoPendiente
            ],
            'fecha_pago' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ], [
            'monto_pagado.max' => 'El monto no puede exceder el saldo disponible de Bs ' . number_format($nuevoSaldoPendiente, 2),
        ]);

        $comprobantePath = $pago->comprobante;
        /*if ($request->hasFile('comprobante')) {
            if ($pago->comprobante) {
                Storage::disk('public')->delete($pago->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes/pagos', 'public');
        }*/
        if ($request->hasFile('comprobante')) {

            $url = $cloudinary->upload($request->file('comprobante'),'comprobantes/pagos/' . $subcontrato->id );

            $comprobantePath = $url;
        }

        $pago->update([
            'monto_pagado' => $nuevoMonto,
            'fecha_pago' => $request->fecha_pago,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
        ]);

        return redirect()
            ->route('pagos.index', $subcontrato)
            ->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Elimina un pago.
     */
    public function destroy(Subcontrato $subcontrato, PagoSubcontrato $pago)
    {
        $this->authorizeAccess($subcontrato);
        if ($pago->subcontrato_id !== $subcontrato->id) {
            abort(403);
        }

        if ($pago->comprobante) {
            Storage::disk('public')->delete($pago->comprobante);
        }

        $pago->delete();

        return redirect()
            ->route('pagos.index', $subcontrato)
            ->with('success', 'Pago eliminado exitosamente.');
    }

    /**
     * Helper: Verifica que el contractor sea dueño del proyecto del subcontrato.
     */
    private function authorizeAccess(Subcontrato $subcontrato)
    {
        $user = Auth::user();
        // Verificar si el usuario tiene acceso al proyecto
        if ($user->role === 'contractor') {
            // Solo puede ver sus propios proyectos
            if ($subcontrato->proyecto->user_id !== $user->id) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } elseif ($user->role === 'resident') {
            // Solo puede ver proyectos en los que está asignado
            if (!$subcontrato->proyecto->residentes()->where('users.id', $user->id)->exists()) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } else {
            abort(403, 'Acceso denegado');
        }

        
    }
}