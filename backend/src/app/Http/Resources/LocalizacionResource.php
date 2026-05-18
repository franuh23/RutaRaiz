<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocalizacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ruta_id' => $this->ruta_id,
            'nombre' => $this->nombre,
            'distancia_desde_inicio' => $this->distancia_desde_inicio,
            'distancia_desde_fin' => $this->distancia_desde_fin,
            'descripcion' => $this->descripcion,
            'activo' => $this->activo,
            'alojamientos' => AlojamientoResource::collection($this->whenLoaded('alojamientos')),
            'comentarios' => ComentarioLocalizacionResource::collection($this->whenLoaded('comentarios')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
