<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanificarRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'ruta_id' => 'required|exists:rutas,id',
            'localizacion_inicio_id' => 'required|exists:localizaciones,id',
            'tipo_planificacion' => 'required|in:destino_ritmo,dias_ritmo,destino_dias',
            'dias_disponibles' => 'required_if:tipo_planificacion,dias_ritmo,destino_dias|nullable|integer|min:1|max:90',
            'km_dia' => 'required_if:tipo_planificacion,destino_ritmo,dias_ritmo|nullable|numeric|min:1|max:100',
            'localizacion_fin_id' => 'required_if:tipo_planificacion,destino_dias|nullable|exists:localizaciones,id',
        ];
    }
}
