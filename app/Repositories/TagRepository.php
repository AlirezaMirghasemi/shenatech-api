<?php

namespace App\Repositories;

use App\Enums\CommonStatus;
use App\Http\Resources\TagResource;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagRepository implements TagRepositoryInterface
{
    public function getAllTags(array $relations = [])
    {
        if (auth()->user()->roles()->where('name', 'Admin')->exists())
            return Tag::with($relations)->orderBy('updated_at', 'desc')->withTrashed();
        else
            return Tag::with($relations)->orderBy('updated_at', 'desc');
    }
    public function getTagById(int $id, array $relations = [])
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
    public function deleteTags(array $tags)
    {
        $tags = Tag::whereIn('id', $tags["ids"])->get();
        $tags = $tags->each(function ($tag) {
            $tag->status = CommonStatus::DELETED;
            $tag->deleted_by = auth()->user()->id;
            $tag->update();
            $tag->delete();
        });

        return $tags;
    }
    public function isTagTitleUnique(string $title): bool
    {
        $response = Tag::where('title', $title)->withTrashed()->exists();
        return !$response;
    }
    public function restoreTags(array $tags)
    {
        $tags = Tag::onlyTrashed()->whereIn('id', $tags)->get();
        foreach ($tags as $tag) {
            $tag->status = CommonStatus::ACTIVE;
            $tag->deleted_by = null;
            $tag->touch();
            $tag->restore();
        }
        return $tags;
    }
}
