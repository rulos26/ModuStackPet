<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonasAfiliadaRequest extends FormRequest
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
			'nombres_apellidos' => 'required|string',
			'cedula' => 'required|string',
			'edad' => 'required',
			'fecha_nacimiento' => 'required',
			'lugar_nacimiento_id' => 'required',
			'fecha_siniestro' => 'required',
			'lugar_siniestro_id' => 'required',
			'estado_civil_siniestro_id' => 'required',
			'estado_civil_desde' => 'required',
			'estado_civil_hasta' => 'required',
			'hijos' => 'required',
			'edad_hijos' => 'required|string',
			'relacion_con' => 'required|string',
			'convivencia_con' => 'string',
			'tiempo_convivencia' => 'required|string',
			'direccion_residencia' => 'required|string',
			'titular_trabaja' => 'required',
			'empresa' => 'required|string',
			'cargo' => 'required|string',
			'tiempo_laboral' => 'required|string',
			'salario' => 'required',
			'telefono' => 'required|string',
			'cobertura_salud_id' => 'required',
			'tipo_afiliacion_id' => 'required',
			'registra_beneficiarios' => 'required',
			'observaciones' => 'string',
        ];
    }
}
