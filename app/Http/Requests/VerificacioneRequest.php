<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificacioneRequest extends FormRequest
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
			'cedula_afiliado' => 'string',
			'registro_civil_nacimiento_afiliado' => 'string',
			'registro_defuncion_afiliado' => 'string',
			'cedula_reclamante' => 'string',
			'registro_civil_nacimiento_descendiente' => 'string',
        ];
    }
}
