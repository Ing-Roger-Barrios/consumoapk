<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResidentController extends Controller
{
    public function create()
    {
        return view('residents.create');
    }

    public function store(Request $request)
    {
        $residents = Auth::user()->residents;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'resident',
            'created_by' => Auth::id(), // 👈 clave
        ]);

        return redirect()->route('residents.index', $residents)->with('success', 'Residente creado exitosamente.');
    }

    // En ResidentController.php
    public function index()
    {
        $residents = Auth::user()->residents;
        return view('residents.index', compact('residents'));
    }

    public function edit(User $resident)
    {
        // Verificar que el residente pertenece al contractor actual
        if ($resident->created_by !== Auth::id()) {
            abort(403);
        }
        return view('residents.edit', compact('resident'));
    }

    public function update(Request $request, User $resident)
    {
        if ($resident->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $resident->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $resident->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $resident->password,
        ]);

        return redirect()
            ->route('residents.index')
            ->with('success', 'Residente actualizado exitosamente.');
    }

    public function destroy(User $resident)
    {
        if ($resident->created_by !== Auth::id()) {
            abort(403);
        }

        // Verificar que no tenga proyectos asignados
        if ($resident->proyectosAsignados()->count() > 0) {
            return redirect()
                ->back()
                ->withErrors(['No se puede eliminar: este residente tiene proyectos asignados.']);
        }

        $resident->delete();

        return redirect()
            ->route('residents.index')
            ->with('success', 'Residente eliminado exitosamente.');
    }
}
