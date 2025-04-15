<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validar los datos de Ciudad
 *
 * Se utiliza para validar los datos enviados al crear o actualizar una ciudad
 */
class CiudadeRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'municipio' => 'required|string|max:255',
            'departamento_id' => 'required|exists:departamentos,id',
            'estado' => 'required|integer|in:0,1'
        ];

        // Si es una actualización, agregar regla unique ignorando el registro actual
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $rules['municipio'] .= '|unique:ciudades,municipio,' . $this->route('ciudade') . ',id_municipio,departamento_id,' . $this->departamento_id;
        } else {
            $rules['municipio'] .= '|unique:ciudades,municipio,NULL,id_municipio,departamento_id,' . $this->departamento_id;
        }

        return $rules;
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'municipio.required' => 'El nombre del municipio es obligatorio',
            'municipio.string' => 'El nombre debe ser texto',
            'municipio.max' => 'El nombre no puede tener más de 255 caracteres',
            'municipio.unique' => 'Ya existe un municipio con este nombre en el departamento seleccionado',
            'departamento_id.required' => 'El departamento es obligatorio',
            'departamento_id.exists' => 'El departamento seleccionado no existe',
            'estado.required' => 'El estado es obligatorio',
            'estado.integer' => 'El estado debe ser un número',
            'estado.in' => 'El estado debe ser 0 o 1'
        ];
    }

    /**
     * Prepara los datos para la validación
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'estado' => $this->boolean('estado', true) ? 1 : 0
        ]);
    }
}
