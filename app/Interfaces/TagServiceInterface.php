<?php

namespace App\Interfaces;

interface TagServiceInterface
{
    public function getAllTags(int $perPage);
    public function createTags(array $data);
    public function isTagTitleUnique(string $title);
}
