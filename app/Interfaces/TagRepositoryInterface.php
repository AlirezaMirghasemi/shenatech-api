<?php

namespace App\Interfaces;

interface TagRepositoryInterface
{
    public function getAllTags(array $relations = []);

}
