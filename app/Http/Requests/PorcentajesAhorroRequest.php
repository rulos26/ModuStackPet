<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PorcentajesAhorroRequest extends FormRequest
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
			'metodo_id' => 'required',
			'porcentaje_1' => 'required',
			'porcentaje_2' => 'required',
			'porcentaje_3' => 'required',
			'porcentaje_4' => 'required',
        ];
    }
}
