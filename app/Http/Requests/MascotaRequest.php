<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MascotaRequest extends FormRequest
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
			'avatar' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
			'nombre' => 'required|string',
			'vacunas_completas' => 'required|in:0,1',
			'comportamiento' => 'nullable|string',
			'recomendaciones' => 'nullable|string',
			'esterilizado' => 'required|in:0,1',
			'enfermedades' => 'nullable|string',
			'edad' => 'required|integer|min:0',
			'genero' => 'required|string',
			'raza_id' => 'required|exists:razas,id',
        ];
    }
}
