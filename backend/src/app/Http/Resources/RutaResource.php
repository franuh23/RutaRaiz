<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RutaResource extends JsonResource
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
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'dificultad' => $this->dificultad,
            'inicio' => $this->inicio,
            'fin' => $this->fin,
            'kilometros' => $this->kilometros,
            'imagen' => $this->imagen,
            'activo' => $this->activo,
            'localizaciones' => LocalizacionResource::collection($this->whenLoaded('localizaciones')),
            'comentarios' => ComentarioRutaResource::collection($this->whenLoaded('comentarios')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
