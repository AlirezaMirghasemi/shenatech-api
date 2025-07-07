<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagRepository implements TagRepositoryInterface
{
    public function getAllTags(array $relations = [])
    {
        return Tag::with($relations);
    }
    public function createTags(array $data): Tag
    {
        return Tag::create($data);
    }
    public function isTagTitleUnique(string $title): bool
    {
        $tag = Tag::where('title', $title)->first();
        if ($tag) {
            return false;
        }
        return true;
    }
}
