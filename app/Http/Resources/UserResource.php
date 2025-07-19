<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// Import other resources if needed
// use App\Http\Resources\RoleResource;
// use App\Http\Resources\PermissionResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'bio' => $this->bio,
            'status' => $this->status,
            'gender' => $this->gender, // Already casted to Enum, will be serialized to its value
            'mobile' => $this->mobile,
            'mobile_verified_at' => $this->mobile_verified_at?->toIso8601String(),
            'email_verified_at' => $this->email_verified_at?->toIso8601String(), // Format date
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            // Conditionally load relationships if they are loaded on the model
            //'profile_image_url' => $this->whenLoaded('profileImage', fn() => $this->profileImage?->url), // Use the accessor from Image model
            'profile_image' => $this->whenLoaded('profileImage', fn() => $this->profileImage),
            // Use RoleResource and PermissionResource (create them in Phase 8)
            // 'roles' => RoleResource::collection($this->whenLoaded('roles')),
            // 'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'role_names' => $this->whenLoaded('roles', fn()=>$this->roles->pluck('name')),
            'permission_names' => $this->whenLoaded('permissions', fn() => $this->permissions->pluck('name')),
            'created_by' => $this->creator ?? null,
            'updated_by' => $this->updater ?? null,
            'deleted_by' => $this->destroyer ?? null,
            'restored_by' => $this->restorer ?? null,
        ];
    }
}
