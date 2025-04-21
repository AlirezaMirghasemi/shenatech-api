<?php

namespace App\Contracts\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Article;
    public function create(array $data): Article;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
