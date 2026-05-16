<?php

namespace App\Http\Controllers;

use App\Http\Requests\InternalPostPublishRequest;
use App\Services\InternalPostPublisherService;
use Illuminate\Http\JsonResponse;

class InternalPostPublisherController extends Controller
{
    public function __invoke(
        InternalPostPublishRequest $request,
        InternalPostPublisherService $internalPostPublisherService,
    ): JsonResponse {
        $post = $internalPostPublisherService->create($request->validated());

        return response()->json([
            'message' => 'Artikel berhasil dibuat.',
            'data' => [
                'id' => $post->getKey(),
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
                'published_at' => optional($post->published_at)?->toIso8601String(),
                'url' => route('blog.show', $post->slug),
                'faqs_count' => $post->faqs->count(),
            ],
        ], 201);
    }
}
