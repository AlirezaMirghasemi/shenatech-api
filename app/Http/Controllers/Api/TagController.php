<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Resources\TagResource;
use App\Interfaces\TagServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    protected $tagService;
    public function __construct(TagServiceInterface $tagService)
    {
        $this->tagService = $tagService;
    }
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $search=$request->input('search',null);
        $tags = $this->tagService->getAllTags($perPage,$search);
        return TagResource::collection($tags)->response();
    }
    public function store(StoreTagRequest $request): JsonResponse
    {
        $tags = $this->tagService->createTags($request->validated());
        return ($tags)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    public function isUnique(string $title): bool
    {
        return $this->tagService->isTagTitleUnique($title);
    }
}
