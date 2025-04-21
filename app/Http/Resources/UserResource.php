<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'bio' => $this->bio,
            'gender' => $this->gender,
            'mobile' => $this->mobile,
            'profile_image' => $this->whenLoaded('profileImage', fn () => [
                'id' => $this->profileImage->id,
                'path' => $this->profileImage->path,
                'type' => $this->profileImage->type,
            ]),
            'roles' => $this->roles->pluck('name'),
        ];
    }
}
