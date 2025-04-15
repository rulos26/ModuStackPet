<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartamentoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departamentos')->ignore($this->departamento, 'id_departamento')
            ],
            'estado' => 'required|boolean'
        ];

        return $rules;
    }

    /**
     * Get the validation messages for the rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del departamento es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de :max caracteres.',
            'nombre.unique' => 'Ya existe un departamento con este nombre.',
            'estado.required' => 'El estado del departamento es obligatorio.',
            'estado.boolean' => 'El estado debe ser un valor booleano.'
        ];
    }

    /**
     * Get the custom attributes for the validator.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del departamento',
            'estado' => 'estado del departamento'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'estado' => $this->has('estado') ? 1 : 0
        ]);
    }
}
