<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectoreRequest extends FormRequest
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
            'nombre' => 'required|string|max:100|unique:sectores,nombre,' . ($this->sectore ? $this->sectore->id : 'NULL') . ',id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del sector es obligatorio.',
            'nombre.string' => 'El nombre del sector debe ser texto.',
            'nombre.max' => 'El nombre del sector no puede tener más de 100 caracteres.',
            'nombre.unique' => 'Este nombre de sector ya existe.',
        ];
    }
}
