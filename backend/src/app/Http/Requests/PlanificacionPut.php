<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlanificacionPut extends FormRequest
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
            'localizacion_inicio_id' => 'required|exists:localizaciones,id',
            'localizacion_fin_id' => 'nullable|exists:localizaciones,id',
            'fecha_inicio' => 'required|date',
            'km_dia' => 'required|numeric|min:1|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'ruta_id.required' => 'Debes seleccionar una ruta.',
            'ruta_id.exists' => 'La ruta seleccionada no existe.',
            'localizacion_inicio_id.required' => 'Debes seleccionar el punto de inicio.',
            'localizacion_inicio_id.exists' => 'La localización de inicio no existe.',
            'localizacion_fin_id.exists' => 'La localización de fin no existe.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'Formato de fecha inválido.',
            'km_dia.required' => 'Los kilómetros por día son obligatorios.',
            'km_dia.numeric' => 'Debe ser un número.',
            'km_dia.min' => 'Mínimo 1 km por día.',
            'km_dia.max' => 'Máximo 100 km por día.',
        ];
    }
}
