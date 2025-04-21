<?php

namespace App\Repositories;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function all(): Collection
    {
        return Article::all();
    }

    public function find(int $id): ?Article
    {
        return Article::find($id);
    }

    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Article::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Article::destroy($id);
    }
}
