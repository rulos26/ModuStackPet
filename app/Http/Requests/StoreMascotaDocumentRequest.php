<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMascotaDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Verificar que el usuario sea dueño de la mascota o administrador
        $mascotaId = $this->input('mascota_id');
        if ($mascotaId) {
            $mascota = \App\Models\Mascota::find($mascotaId);
            if ($mascota) {
                $user = auth()->user();
                return $user->hasRole('Superadmin') 
                    || $user->hasRole('Admin') 
                    || $mascota->user_id === $user->id;
            }
        }
        return true; // La validación de existencia se hace en rules()
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mascota_id' => 'required|exists:mascotas,id',
            'document_requirement_id' => 'required|exists:document_requirements,id',
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'fecha_emision' => 'nullable|date|before_or_equal:today',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:1000',
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
            'mascota_id.required' => 'Debe seleccionar una mascota.',
            'mascota_id.exists' => 'La mascota seleccionada no existe.',
            'document_requirement_id.required' => 'Debe seleccionar un tipo de documento.',
            'document_requirement_id.exists' => 'El tipo de documento seleccionado no existe.',
            'archivo.required' => 'Debe subir un archivo.',
            'archivo.mimes' => 'El archivo debe ser PDF, JPG, JPEG o PNG.',
            'archivo.max' => 'El archivo no debe exceder 10MB.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento debe ser posterior o igual a la fecha de emisión.',
        ];
    }
}
