<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {

        $user = Auth::user(); // Equivalente a auth()->user()
        $roles = $user->roles->pluck('name');

        // dd($roles);
        return view('admin.dashboard ', compact('roles'));
    }
}
