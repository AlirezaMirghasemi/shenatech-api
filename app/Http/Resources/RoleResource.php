<?php
namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'status' => $this->status,
            'created_by' => $this->creator ?? null,
            'updated_by' => $this->updater ?? null,
            'deleted_by' => $this->destroyer ?? null,
            'restored_by' => $this->restorer ?? null,

            // Load permissions only if loaded on the model
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'users' => User::with('roles')->whereHas('roles', fn($query) => $query->where('id', $this->id))->get(),
        ];
    }
}
