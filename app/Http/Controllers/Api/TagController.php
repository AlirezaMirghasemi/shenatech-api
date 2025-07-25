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
        $page = $request->input('page', 1); // دریافت شماره صفحه

        $search = $request->input('search', null);
        $tags = $this->tagService->getAllTags($page,$perPage, $search);
        return TagResource::collection($tags)->response();
    }
    public function store(StoreTagRequest $request): JsonResponse
    {
        $tags = $this->tagService->createTags($request->validated());
        return ($tags)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    public function destroy(Request $request): JsonResponse
    {
        $tags = $request->input('tagIds', []);
        $this->tagService->deleteTags($tags); // Service handles authorization
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204
    }
    public function isUnique(string $title): JsonResponse
    {
        $isUnique = $this->tagService->isTagTitleUnique($title);
        return response()->json(['isUnique' => $isUnique]);
    }
    public function restores(Request $request):JsonResponse{
        $tags=$request->input('tagIds',[]);
        $this->tagService->restoreTags($tags);
        return response()->json(null,Response::HTTP_OK);
    }
}
