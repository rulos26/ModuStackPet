<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiseRequest extends FormRequest
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
			'name' => 'required|string',
			'iso_name' => 'required|string',
			'alfa2' => 'required|string',
			'alfa3' => 'required|string',
			'numerico' => 'required',
        ];
    }
}
