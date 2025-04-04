<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatosBasicosHijoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'cedula_numero' => 'required|string',
			'numero_hijos' => 'required',
			'nombre' => 'required|string',
			'tipo_documento' => 'required',
			'documento' => 'required|string',
			'edad' => 'required',
        ];
    }
}
