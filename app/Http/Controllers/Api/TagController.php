<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Resources\TagResource;
use App\Interfaces\TagServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagService;
    public function __construct(TagServiceInterface $tagService)
    {
        $this->tagService = $tagService;
    }
public function index(Request $request): JsonResponse
    {
         $perPage = $request->input('per_page', 10); // دریافت تعداد آیتم در هر صفحه
        $tags = $this->tagService->getAllTags($perPage);
        return TagResource::collection($tags)->response();
    }
    public function store(StoreTagRequest $request): JsonResponse{
        $tags = $this->tagService->createTags($request->validated());
        return (new TagResource($tags))->response()->setStatusCode(201);
    }
    public function isUnique(string $title): JsonResponse
    {
        $isUnique = $this->tagService->isTagTitleUnique($title);
        return response()->json(['is_unique' => $isUnique]);
    }
}
