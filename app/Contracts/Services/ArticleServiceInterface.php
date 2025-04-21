<?php

namespace App\Contracts\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleServiceInterface
{
    public function getAllArticles(): Collection;
    public function getArticleById(int $id): ?Article;
    public function createArticle(array $data): Article;
    public function updateArticle(int $id, array $data): Article;
    public function deleteArticle(int $id): bool;
}
