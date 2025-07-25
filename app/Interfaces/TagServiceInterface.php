<?php

namespace App\Interfaces;

interface TagServiceInterface
{
    public function getAllTags(int $page, int $perPage, string $search = null);
    public function createTags(array $data);
    public function deleteTags(array $tags);
    public function isTagTitleUnique(string $title);
    public function restoreTags(array $tags);
}
