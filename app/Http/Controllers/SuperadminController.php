<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
    public function index()
    {

        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        // dd($roles);
        return view('superadmin.dashboard', compact('roles')); // Cambia a la vista que prefieras
    }
}
