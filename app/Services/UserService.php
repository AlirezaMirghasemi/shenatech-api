<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers(): Collection
    {
        return $this->repository->all();
    }

    public function getUserById(int $id): ?User
    {
        return $this->repository->find($id);
    }

    public function createUser(array $data): User
    {
        return $this->repository->create($data);
    }

    public function updateUser(int $id, array $data): User
    {
        $this->repository->update($id, $data);
        return $this->getUserById($id);
    }

    public function deleteUser(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function uploadProfileImage(int $id, $file): User
    {
        $path = $file->store('profile', 'images');
        $image = \App\Models\Image::create([
            'title' => 'Profile Image',
            'type' => \App\Enums\ImageType::PROFILE,
            'path' => $path,
            'disk' => 'images',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        $this->repository->update($id, ['image_id' => $image->id]);
        return $this->getUserById($id);
    }
}
