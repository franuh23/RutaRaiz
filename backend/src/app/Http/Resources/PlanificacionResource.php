<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanificacionResource extends JsonResource
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
            'usuario_id' => $this->usuario_id,
            'usuario_nick' => $this->usuario->nick ?? null,
            'ruta_id' => $this->ruta_id,
            'ruta_nombre' => $this->ruta->nombre ?? null,
            'localizacion_inicio_id' => $this->localizacion_inicio_id,
            'localizacion_inicio_nombre' => $this->localizacionInicio->nombre ?? null,
            'localizacion_fin_id' => $this->localizacion_fin_id,
            'localizacion_fin_nombre' => $this->localizacionFin->nombre ?? null,
            'fecha_inicio' => $this->fecha_inicio ? \Carbon\Carbon::parse($this->fecha_inicio)->format('Y-m-d') : null,
            'km_dia' => $this->km_dia,
            'dias_totales' => $this->dias_totales,
            'activo' => $this->activo,
            'en_curso' => (bool) $this->en_curso,
            'is_public' => (bool) $this->is_public,
            'es_clonada' => $this->original_id !== null,
            'etapas' => EtapaResource::collection($this->whenLoaded('etapas')),
            'comentarios' => ComentarioPlanificacionResource::collection($this->whenLoaded('comentarios')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
