<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlojamientoResource extends JsonResource
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
            'localizacion_id' => $this->localizacion_id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'tipo' => $this->tipo,
            'enlace' => $this->enlace,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'imagen' => $this->imagen,
            'activo' => $this->activo,
            'comentarios' => ComentarioAlojamientoResource::collection($this->whenLoaded('comentarios')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
