<?php

namespace App\Http\Controllers;

use App\Models\Autorizacion;
use Illuminate\Http\Request;

class AutorizacionController extends Controller
{
    public function create()
    {
        return view('autorizacion.create');
    }

    public function store(Request $request)
    {
       /*  $request->validate([
            'cedula' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'foto_cedula' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_firma' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'direccion' => 'required|string|max:255',
            'barrio' => 'required|string|max:100',
            'localidad' => 'required|string|max:100',
            'telefono_fijo' => 'nullable|string|max:15',
            'celular' => 'nullable|string|max:15',
            'correo_electronico' => 'nullable|email|max:255',
        ]); */
        
        session([
            'cedula' => $request->cedula,
            'nombre_afiliado' => $request->nombre_afiliado
        ]);

        //$cedula = session('cedula');
        //*dd($cedula);
        $fotoPerfilPath =$request->file('foto_cedula')->store('public/fotos_cedula');
        $fotoCedulaPath = $request->file('foto_cedula')->store('public/fotos_cedula');
        $fotoFirmaPath = $request->file('foto_firma')->store('public/fotos_firma');

        Autorizacion::create([
            'cedula' => $request->cedula,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'foto_perfil_path' => $fotoPerfilPath,
            'foto_cedula_path' => $fotoCedulaPath,
            'foto_firma_path' => $fotoFirmaPath,
            'direccion' => $request->direccion,
            'barrio' => $request->barrio,
            'localidad' => $request->localidad,
            'telefono_fijo' => $request->telefono_fijo,
            'celular' => $request->celular,
            'correo_electronico' => $request->correo_electronico,
        ]);;

        return redirect()->route('reporte.carta');
    }
}
