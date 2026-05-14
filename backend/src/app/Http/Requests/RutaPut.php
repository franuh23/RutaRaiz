<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RutaPut extends FormRequest
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
            'nombre' => 'required|max:150',
            'descripcion' => 'nullable',
            'dificultad' => 'required|in:baja,media,alta',
            'inicio' => 'required|max:100',
            'fin' => 'required|max:100',
            'kilometros' => 'required|numeric|min:0',
            'imagen' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 150 caracteres.',
            'dificultad.required' => 'Debes seleccionar una dificultad.',
            'dificultad.in' => 'La dificultad debe ser: baja, media o alta.',
            'inicio.required' => 'El punto de inicio es obligatorio.',
            'inicio.max' => 'El inicio no puede superar los 100 caracteres.',
            'fin.required' => 'El punto de fin es obligatorio.',
            'fin.max' => 'El fin no puede superar los 100 caracteres.',
            'kilometros.required' => 'Los kilómetros son obligatorios.',
            'kilometros.numeric' => 'Los kilómetros deben ser un número.',
            'kilometros.min' => 'Los kilómetros no pueden ser negativos.',
        ];
    }
}
