<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EtapaResource extends JsonResource
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
            'dia' => $this->dia,
            'localizacion_inicio_id' => $this->localizacion_inicio_id,
            'localizacion_inicio_nombre' => $this->localizacionInicio->nombre ?? null,
            'localizacion_fin_id' => $this->localizacion_fin_id,
            'localizacion_fin_nombre' => $this->localizacionFin->nombre ?? null,
            'distancia' => $this->distancia,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
