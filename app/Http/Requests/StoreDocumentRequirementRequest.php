<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequirementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo administradores pueden crear requisitos
        return auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => 'required|string|max:20|unique:document_requirements,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'obligatorio' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer|min:0',
            'tipo_validacion' => 'nullable|string|in:fecha_vencimiento,firma_digital,sello_veterinario',
            'dias_validez' => 'nullable|integer|min:1',
            'formatos_permitidos' => 'nullable|array',
            'formatos_permitidos.*' => 'string|in:pdf,jpg,jpeg,png',
            'tamaño_maximo_kb' => 'integer|min:1|max:10240',
            'aplica_razas_peligrosas' => 'boolean',
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
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'Este código ya está en uso.',
            'nombre.required' => 'El nombre es obligatorio.',
            'tipo_validacion.in' => 'El tipo de validación no es válido.',
            'formatos_permitidos.*.in' => 'Los formatos permitidos deben ser: pdf, jpg, jpeg, png.',
        ];
    }
}
