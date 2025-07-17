<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'status' => $this->status,
            'created_by' => $this->created_by?->username ?? null,
            'updated_by' => $this->updated_by?->username ?? null,
            'deleted_by' => $this->deleted_by?->username ?? null,
            'restored_by' => $this->restored_by?->username ?? null,
        ];
    }
}
