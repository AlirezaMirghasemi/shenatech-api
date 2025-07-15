<?php

namespace App\Repositories;

use App\Http\Resources\TagResource;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagRepository implements TagRepositoryInterface
{
    public function getAllTags(array $relations = [])
    {
        return Tag::with($relations)->orderBy('updated_at', 'desc');
    }
    public function createTags(array $data)
    {
        $tags = [];
        foreach ($data['titles'] as $title) {
            if (empty(trim($title))) {
                continue;
            }
            $tags[] = Tag::create([
                'title' => $title,
            ]);
        }
        return TagResource::collection($tags);
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
