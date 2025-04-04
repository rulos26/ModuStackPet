<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HechosOcurrenciaRequest extends FormRequest
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
			'dia' => 'required',
			'horas' => 'required',
			'lugar' => 'required|string',
			'motivo_muerte' => 'required',
			'otros' => 'string',
			'deceso_se_origna' => 'string',
			'donde_fallese' => 'string',
			'funeraria' => 'string',
			'fallecido' => 'string',
			'cuerpo_fue' => 'string',
			'servicos_funerarios' => 'string',
        ];
    }
}
