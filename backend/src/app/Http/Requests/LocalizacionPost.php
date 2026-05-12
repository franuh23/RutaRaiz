<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LocalizacionPost extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ruta_id' => 'required|exists:rutas,id',
            'nombre' => 'required|max:150',
            'distancia_desde_inicio' => 'required|numeric|min:0',
            'distancia_desde_fin' => 'required|numeric|min:0',
            'descripcion' => 'nullable',
            'activo' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'ruta_id.required' => 'La ruta es obligatoria.',
            'ruta_id.exists' => 'La ruta seleccionada no existe.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'distancia_desde_inicio.required' => 'La distancia desde inicio es obligatoria.',
            'distancia_desde_inicio.numeric' => 'La distancia debe ser un número.',
            'distancia_desde_fin.required' => 'La distancia hasta el final es obligatoria.',
            'distancia_desde_fin.numeric' => 'La distancia debe ser un número.',
        ];
    }
}
