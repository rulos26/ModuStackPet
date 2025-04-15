<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpresaRequest extends FormRequest
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
        $empresaId = $this->route('empresa');

        return [
            'nombre_legal' => 'required|string|max:255',
            'representante_legal' => 'required|string|max:255',
            'nit' => 'required|string|regex:/^[0-9]{9,10}$/|unique:empresas,nit,' . $empresaId,
            'tipo_empresa_id' => 'required|exists:tipos_empresas,id',
            'sector_id' => 'required|exists:sectores,id',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'departamento_id' => 'required|exists:departamentos,id_departamento',
            'ciudad_id' => 'required|exists:ciudades,id_municipio',
            'direccion' => 'required|string|max:255',
            'estado' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nombre_legal.required' => 'El nombre legal de la empresa es obligatorio.',
            'nombre_legal.max' => 'El nombre legal no puede tener más de 255 caracteres.',

            'representante_legal.required' => 'El representante legal es obligatorio.',
            'representante_legal.max' => 'El nombre del representante legal no puede tener más de 255 caracteres.',

            'nit.required' => 'El NIT es obligatorio.',
            'nit.regex' => 'El NIT debe contener entre 9 y 10 dígitos numéricos.',
            'nit.unique' => 'Este NIT ya está registrado en el sistema.',

            'tipo_empresa_id.required' => 'El tipo de empresa es obligatorio.',
            'tipo_empresa_id.exists' => 'El tipo de empresa seleccionado no es válido.',

            'sector_id.required' => 'El sector es obligatorio.',
            'sector_id.exists' => 'El sector seleccionado no es válido.',

            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',

            'departamento_id.required' => 'El departamento es obligatorio.',
            'departamento_id.exists' => 'El departamento seleccionado no es válido.',

            'ciudad_id.required' => 'La ciudad es obligatoria.',
            'ciudad_id.exists' => 'La ciudad seleccionada no es válida.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',

            'logo.image' => 'El archivo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser un archivo de tipo: jpeg, png, jpg o gif.',
            'logo.max' => 'El logo no puede ser mayor a 2MB.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'nombre_legal' => 'nombre legal',
            'representante_legal' => 'representante legal',
            'nit' => 'NIT',
            'tipo_empresa_id' => 'tipo de empresa',
            'sector_id' => 'sector',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
            'departamento_id' => 'departamento',
            'ciudad_id' => 'ciudad',
            'direccion' => 'dirección',
            'estado' => 'estado',
            'logo' => 'logo'
        ];
    }

    /**
     * Preparar los datos para la validación
     */
    protected function prepareForValidation(): void
    {
        // Convertir el estado a booleano
        $this->merge([
            'estado' => $this->boolean('estado'),
            // Asegurarnos de que el NIT se guarde en minúsculas
            'nit' => $this->input('nit')
        ]);
    }
}
