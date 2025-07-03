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
}
