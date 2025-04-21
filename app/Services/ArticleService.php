<?php

namespace App\Services;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Contracts\Services\ArticleServiceInterface;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleService implements ArticleServiceInterface
{
    protected $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllArticles(): Collection
    {
        return $this->repository->all();
    }

    public function getArticleById(int $id): ?Article
    {
        return $this->repository->find($id);
    }

    public function createArticle(array $data): Article
    {
        return $this->repository->create($data);
    }

    public function updateArticle(int $id, array $data): Article
    {
        $this->repository->update($id, $data);
        return $this->getArticleById($id);
    }

    public function deleteArticle(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
