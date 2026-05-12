<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AlojamientoPost extends FormRequest
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
            'localizacion_id' => 'required|exists:localizaciones,id',
            'nombre' => 'required|max:150',
            'direccion' => 'nullable|max:255',
            'tipo' => 'required|in:hostal,hotel,albergue,casa_rural,camping',
            'enlace' => 'nullable|max:255|url',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'activo' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
        public function messages()
    {
        return [
            'localizacion_id.required' => 'La localización es obligatoria.',
            'localizacion_id.exists' => 'La localización seleccionada no existe.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'tipo.required' => 'Debes seleccionar un tipo de alojamiento.',
            'tipo.in' => 'El tipo debe ser: hostal, hotel, albergue, casa_rural o camping.',
            'enlace.url' => 'El enlace debe ser una URL válida.',
            'email.email' => 'El email debe ser válido.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'Formato permitido: jpg, jpeg, png.',
            'imagen.max' => 'La imagen no puede superar los 2MB.',
        ];
    }
}
