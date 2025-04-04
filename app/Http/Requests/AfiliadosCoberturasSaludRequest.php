<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AfiliadosCoberturasSaludRequest extends FormRequest
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
			'cobertura_salud' => 'required|string',
			'tipo_afiliacion' => 'required|string',
			'regimen' => 'required|string',
			'desde' => 'required',
			'quien_reclama_prestaciones_sociales' => 'string',
        ];
    }
}
