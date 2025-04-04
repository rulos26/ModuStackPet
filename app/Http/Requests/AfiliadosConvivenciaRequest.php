<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AfiliadosConvivenciaRequest extends FormRequest
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
			'estado_civil_al_siniestro' => 'required',
			'desde_estado_civil' => 'required',
			'hasta_estado_civil' => 'required',
			'relacion_con' => 'required|string',
			'quien_convivía' => 'required|string',
			'tiempo_convivencia' => 'required|string',
			'desde_convivencia' => 'required',
			'hasta_convivencia' => 'required',
        ];
    }
}
