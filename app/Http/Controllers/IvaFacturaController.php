<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\IvaFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IvaFacturaController extends Controller
{
    public function index(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        $facturas = $proyecto->ivaFacturas()->latest()->get();
        return view('iva.index', compact('proyecto', 'facturas'));
    }

    public function create(Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);
        return view('iva.create', compact('proyecto'));
    }

    public function store(Request $request, Proyecto $proyecto)
    {
        $this->authorizeAccess($proyecto);

        $request->validate([
            'numero_factura' => 'required|string|max:100',
            'monto_factura' => 'required|numeric|min:0.01',
            'porcentaje_iva' => 'required|numeric|min:0|max:100',
            'fecha_factura' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $comprobantePath = null;
        if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes/iva', 'public');
        }

        IvaFactura::create([
            'proyecto_id' => $proyecto->id,
            'numero_factura' => $request->numero_factura,
            'monto_factura' => $request->monto_factura,
            'porcentaje_iva' => $request->porcentaje_iva,
            'fecha_factura' => $request->fecha_factura,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
            // monto_iva se calcula automáticamente
        ]);

        return redirect()
            ->route('iva.index', $proyecto)
            ->with('success', 'Factura IVA registrada exitosamente.');
    }

    public function edit(Proyecto $proyecto, IvaFactura $factura)
    {
        $this->authorizeAccess($proyecto);
        if ($factura->proyecto_id !== $proyecto->id) {
            abort(403);
        }
        return view('iva.edit', compact('proyecto', 'factura'));
    }

    public function update(Request $request, Proyecto $proyecto, IvaFactura $factura)
    {
        $this->authorizeAccess($proyecto);
        if ($factura->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        $request->validate([
            'numero_factura' => 'required|string|max:100',
            'monto_factura' => 'required|numeric|min:0.01',
            'porcentaje_iva' => 'required|numeric|min:0|max:100',
            'fecha_factura' => 'required|date|before_or_equal:today',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notas' => 'nullable|string|max:500',
        ]);

        $comprobantePath = $factura->comprobante;
        if ($request->hasFile('comprobante')) {
            if ($factura->comprobante) {
                Storage::disk('public')->delete($factura->comprobante);
            }
            $comprobantePath = $request->file('comprobante')->store('comprobantes/iva', 'public');
        }

        $factura->update([
            'numero_factura' => $request->numero_factura,
            'monto_factura' => $request->monto_factura,
            'porcentaje_iva' => $request->porcentaje_iva,
            'fecha_factura' => $request->fecha_factura,
            'comprobante' => $comprobantePath,
            'notas' => $request->notas,
            // monto_iva se recalcula automáticamente
        ]);

        return redirect()
            ->route('iva.index', $proyecto)
            ->with('success', 'Factura IVA actualizada exitosamente.');
    }

    public function destroy(Proyecto $proyecto, IvaFactura $factura)
    {
        $this->authorizeAccess($proyecto);
        if ($factura->proyecto_id !== $proyecto->id) {
            abort(403);
        }

        if ($factura->comprobante) {
            Storage::disk('public')->delete($factura->comprobante);
        }

        $factura->delete();

        return redirect()
            ->route('iva.index', $proyecto)
            ->with('success', 'Factura IVA eliminada exitosamente.');
    }

    private function authorizeAccess(Proyecto $proyecto)
    {
        $user = Auth::user();
    
        if ($user->role === 'contractor') {
            if ($proyecto->user_id !== $user->id) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } elseif ($user->role === 'resident') {
            if (!$proyecto->residentes()->where('users.id', $user->id)->exists()) {
                abort(403, 'No tienes acceso a este proyecto');
            }
        } else {
            abort(403, 'Acceso denegado');
        }
    }
}