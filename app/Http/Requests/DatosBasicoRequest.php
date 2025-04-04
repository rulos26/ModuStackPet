<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatosBasicoRequest extends FormRequest
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
			'nombre_afiliado' => 'required|string',
			'caso' => 'required|string',
			'fecha' => 'required',
			'estado_civil' => 'required',
			'amparo' => 'required',
			'tipo_de_convivencia' => 'required',
			'otro' => 'string',
        ];
    }
}
