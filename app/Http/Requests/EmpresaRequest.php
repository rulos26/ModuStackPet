<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
			'nombre_legal' => 'required|string',
			'nombre_comercial' => 'string',
			'nit' => 'required|string',
			'dv' => 'string',
			'representante_legal' => 'required|string',
			'tipo_empresa_id' => 'required',
			'telefono' => 'string',
			'email' => 'string',
			'direccion' => 'string',
			'ciudad_id' => 'required',
			'departamento_id' => 'required',
			'sector_id' => 'required',
			'logo' => 'string',
			'estado' => 'required',
        ];
    }
}
