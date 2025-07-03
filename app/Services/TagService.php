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

    public function getAllTags(int $perPage = 10)
    {
        if (Gate::denies('view tags')) {
            throw new AuthorizationException('You do not have permission to view tags.');
        }
        $tags = $this->tagRepository->getAllTags();

        return $tags->paginate($perPage);
    }
}
