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
            'nit' => [
                'required',
                'string',
                'regex:/^[0-9]{9,10}$/',
                'unique:empresas,nit,' . $empresaId,
                function ($attribute, $value, $fail) {
                    if (!$this->validarNitColombiano($value)) {
                        $fail('El NIT no es válido según el algoritmo colombiano.');
                    }
                },
            ],
            'dv' => 'required|string|size:1',
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
            // Asegurarnos de que el NIT se guarde correctamente
            'nit' => $this->input('nit'),
            // Calcular automáticamente el dígito de verificación
            'dv' => $this->calcularDigitoVerificacion($this->input('nit'))
        ]);
    }

    /**
     * Valida el NIT colombiano usando el algoritmo oficial
     */
    private function validarNitColombiano($nit)
    {
        if (strlen($nit) < 9 || strlen($nit) > 10) {
            return false;
        }

        $dvCalculado = $this->calcularDigitoVerificacion($nit);
        $dvIngresado = $this->input('dv');

        return $dvCalculado == $dvIngresado;
    }

    /**
     * Calcula el dígito de verificación del NIT colombiano
     */
    private function calcularDigitoVerificacion($nit)
    {
        $secuencia = [71, 67, 59, 53, 47, 43, 41, 37, 29, 23, 19, 17, 13, 7, 3];
        $suma = 0;

        // Asegurar que el NIT tenga 9 dígitos
        $nit = str_pad($nit, 9, '0', STR_PAD_LEFT);

        for ($i = 0; $i < strlen($nit); $i++) {
            $suma += intval($nit[$i]) * $secuencia[$i];
        }

        $residuo = $suma % 11;
        return $residuo < 2 ? $residuo : 11 - $residuo;
    }
}
