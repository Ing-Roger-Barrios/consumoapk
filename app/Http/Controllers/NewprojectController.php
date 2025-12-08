<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // Make sure to import your model

class NewprojectController extends Controller
{
    public function proy() 
    {
        $residentes = Auth::user()->residents; // solo los que él creó
        return view('newproject', [
            'proyecto' => null,
            'residentes' => $residentes
        ]);
    }
}