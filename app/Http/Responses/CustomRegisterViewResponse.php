<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterViewResponse;

class CustomRegisterViewResponse implements RegisterViewResponse
{
    /**
     * Handle the HTTP response for the register view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function toResponse($request)
    {
        return view('auth.register'); // Indica la vista que quieres cargar
    }
}
