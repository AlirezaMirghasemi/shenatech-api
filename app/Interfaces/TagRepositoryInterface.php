<?php

namespace App\Interfaces;

interface TagRepositoryInterface
{
    public function getAllTags(array $relations = []);
    public function createTags(array $data);
    public function isTagTitleUnique(string $title): bool;
    public function deleteTags(array $tags);


}
