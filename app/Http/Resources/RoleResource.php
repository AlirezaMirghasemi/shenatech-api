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
            // Load permissions only if loaded on the model
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'users' => User::with('roles')->whereHas('roles', fn($query) => $query->where('id', $this->id))->get(),
        ];
    }
}
