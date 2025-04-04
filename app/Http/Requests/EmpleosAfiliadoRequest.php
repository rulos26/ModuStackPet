<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleosAfiliadoRequest extends FormRequest
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
			'afiliado_trabaja' => 'required',
			'empresa' => 'required|string',
			'cargo' => 'required|string',
			'tiempo' => 'required|string',
			'salario' => 'required',
			'no_telefonico' => 'required|string',
        ];
    }
}
