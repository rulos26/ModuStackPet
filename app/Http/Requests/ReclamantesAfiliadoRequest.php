<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReclamantesAfiliadoRequest extends FormRequest
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
			'cedula_numero' => 'required|string',
			'reclamante' => 'required',
			'nombre' => 'required|string',
			'tipo_documento' => 'required',
			'cedula_reclamante' => 'required|string',
			'fecha_nacimiento' => 'required',
			'ciudad_nacimiento' => 'required',
			'departamento_nacimiento' => 'required',
			'edad' => 'required',
			'fecha_expedicion' => 'required',
			'ciudad_expedicion' => 'required',
			'departamento_expedicion' => 'required',
			'estado_civil' => 'required',
			'compartieron_techo_mesa_lecho' => 'required',
			'afiliado_relacion_quedaron_hijos' => 'required',
			'direccion_siniestro' => 'required|string',
			'direccion_actual' => 'required|string',
			'barrio' => 'required|string',
			'ciudad' => 'required',
			'vivienda' => 'required|string',
			'movil' => 'required|string',
			'activa_laboralmente_siniestro' => 'required',
			'trabajaba_empresa' => 'string',
			'ocupacion' => 'string',
			'coberturas_salud' => 'required',
			'tipos_afiliaciones' => 'required',
			'regimen' => 'string',
			'estado_afiliacion' => 'required',
			'registra_beneficiarios_eps' => 'required',
        ];
    }
}
