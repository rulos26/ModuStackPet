<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RazaRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'requiere_cuidado_especial' => $this->boolean('requiere_cuidado_especial'),
        ]);
    }

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
			'tipo_mascota' => 'required|string',
			'nombre' => 'required|string',
            'requiere_cuidado_especial' => 'required|boolean',
            'cuidados_especiales' => 'nullable|required_if:requiere_cuidado_especial,true|string|max:1000',
        ];
    }
}
