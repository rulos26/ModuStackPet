<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConclusioneRequest extends FormRequest
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
			'documentos' => 'required',
			'nexos' => 'required',
			'muerte_origen' => 'required',
			'reclamante' => 'required',
			'nombre_reclamante' => 'required|string',
			'afiliado_deja_descendiente' => 'required',
			'descendientes_relacion' => 'required',
			'descendientes_afiliado' => 'required',
			'presenta_condicion_discapacidad' => 'required',
			'observaciones' => 'required|string',
        ];
    }
}
