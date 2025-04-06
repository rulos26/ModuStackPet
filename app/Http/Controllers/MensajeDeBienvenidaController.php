<?php

namespace App\Http\Controllers;

use App\Models\MensajeDeBienvenida;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MensajeDeBienvenidaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MensajeDeBienvenidaController extends Controller
{
    /**
     * Muestra una lista de todos los mensajes de bienvenida.
     */
    public function index(Request $request): View
    {
        // Obtener los mensajes de bienvenida paginados
        $mensajeDeBienvenidas = MensajeDeBienvenida::paginate();

        // Retornar la vista con los mensajes y el índice de la página actual
        return view('mensaje-de-bienvenida.index', compact('mensajeDeBienvenidas'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajeDeBienvenidas->perPage());
    }

    /**
     * Muestra el formulario para crear un nuevo mensaje de bienvenida.
     */
    public function create(): View
    {
        // Crear una nueva instancia vacía de MensajeDeBienvenida
        $mensajeDeBienvenida = new MensajeDeBienvenida();

        // Retornar la vista del formulario de creación
        return view('mensaje-de-bienvenida.create', compact('mensajeDeBienvenida'));
    }

    /**
     * Almacena un nuevo mensaje de bienvenida en la base de datos.
     */
    public function store(MensajeDeBienvenidaRequest $request): RedirectResponse
    {
        // Validar los datos del formulario
        $validatedData = $request->validated();

        // Asignar un valor predeterminado al campo 'logo'
        $validatedData['logo'] = 'public/storage/img/logo.jpg';

        // Crear un nuevo mensaje de bienvenida con los datos validados
        MensajeDeBienvenida::create($validatedData);

        // Redirigir al índice con un mensaje de éxito
        return Redirect::route('mensaje-de-bienvenidas.index')
            ->with('success', 'El mensaje de bienvenida se creó correctamente.');
    }

    /**
     * Muestra un mensaje de bienvenida específico.
     */
    public function show($id): View
    {
        // Buscar el mensaje de bienvenida por su ID
        $mensajeDeBienvenida = MensajeDeBienvenida::find($id);

        // Retornar la vista con los detalles del mensaje
        return view('mensaje-de-bienvenida.show', compact('mensajeDeBienvenida'));
    }

    /**
     * Muestra el formulario para editar un mensaje de bienvenida específico.
     */
    public function edit($id): View
    {
        // Buscar el mensaje de bienvenida por su ID
        $mensajeDeBienvenida = MensajeDeBienvenida::find($id);

        // Retornar la vista del formulario de edición
        return view('mensaje-de-bienvenida.edit', compact('mensajeDeBienvenida'));
    }

    /**
     * Actualiza un mensaje de bienvenida específico en la base de datos.
     */
    public function update(MensajeDeBienvenidaRequest $request, MensajeDeBienvenida $mensajeDeBienvenida): RedirectResponse
    {
        // Actualizar el mensaje de bienvenida con los datos validados
        $mensajeDeBienvenida->update($request->validated());

        // Redirigir al índice con un mensaje de éxito
        return Redirect::route('mensaje-de-bienvenidas.index')
            ->with('success', 'El mensaje de bienvenida se actualizó correctamente.');
    }

    /**
     * Elimina un mensaje de bienvenida específico de la base de datos.
     */
    public function destroy($id): RedirectResponse
    {
        // Buscar y eliminar el mensaje de bienvenida por su ID
        MensajeDeBienvenida::find($id)->delete();

        // Redirigir al índice con un mensaje de éxito
        return Redirect::route('mensaje-de-bienvenidas.index')
            ->with('success', 'El mensaje de bienvenida se eliminó correctamente.');
    }
}
