<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user?->id,
            'tipo_documento' => 'nullable|exists:tipo_documentos,id',
            'cedula' => 'nullable|numeric|digits_between:6,12',
            'telefono' => 'nullable|string|max:15',
            'whatsapp' => 'nullable|string|max:15',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
            'ciudad_id' => 'nullable|exists:ciudades,id_municipio',
            'barrio_id' => 'nullable|exists:barrios,id',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'activo' => NO se valida aquí - solo los administradores pueden cambiarlo
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
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg o gif.',
            'avatar.max' => 'La imagen no debe pesar más de 2MB.'
        ];
    }
}
