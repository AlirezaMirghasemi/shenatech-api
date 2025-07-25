<?php

namespace App\Services;

use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\TagServiceInterface;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
class TagService implements TagServiceInterface
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags(int $page = 1, int $perPage = 10, string $search = null)
    {
        if (Gate::denies('view tags')) {
            throw new AuthorizationException('You do not have permission to view tags.');
        }
        if ($search == null) {
            $tags = $this->tagRepository->getAllTags();
        } else {
            $tags = $this->tagRepository->getAllTags()->where('title', 'like', "%{$search}%");
        }
        return $tags->paginate($perPage, ['*'], 'page', $page);
    }
    public function createTags(array $data)
    {
        if (Gate::denies('manage tags')) {
            throw new AuthorizationException('You do not have permission to manage tags.');
        }
        return $this->tagRepository->createTags($data);
    }
    public function deleteTags(array $tags)
    {
        if (Gate::denies('manage tags')) {
            throw new AuthorizationException('You do not have permission to manage tags.');
        }
        return $this->tagRepository->deleteTags($tags);
    }
    public function isTagTitleUnique(string $title)
    {
        if (Gate::denies('manage tags')) {
            throw new AuthorizationException('You do not have permission to manage tags.');
        }
        return $this->tagRepository->isTagTitleUnique($title);
    }
    public function restoreTags(array $tags)
    {
        if (Gate::denies('manage tags')) {
            throw new AuthorizationException('You do not have permission to manage tags.');
        }
        return $this->tagRepository->restoreTags($tags);
    }
}
