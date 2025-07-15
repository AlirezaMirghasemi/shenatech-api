<?php

namespace App\Interfaces;

interface TagServiceInterface
{
    public function getAllTags(int $perPage, string $search = null);
    public function createTags(array $data);
    public function isTagTitleUnique(string $title);
}
