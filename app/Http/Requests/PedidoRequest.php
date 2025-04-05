<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoRequest extends FormRequest
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
            'cliente' => 'required',
            'producto' => 'required',
            'ciclo' => 'required',
            'estado_pedido' => 'required',
            'estado_deuda' => 'required',
            'descripcion' => 'string',
            'valor_catalogo' => 'required',
            'valor_lista' => 'required',
        ];
    }
}
