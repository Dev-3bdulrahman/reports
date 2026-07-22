<?php

namespace Dev3bdulrahman\Reports\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'module' => $this->module,
            'type' => $this->type,
            'filters' => $this->filters,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
