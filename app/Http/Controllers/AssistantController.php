<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistantSearchRequest;
use App\Services\AssistantSearchService;
use Illuminate\Http\JsonResponse;

class AssistantController extends Controller
{
    public function search(AssistantSearchRequest $request, AssistantSearchService $assistantSearchService): JsonResponse
    {
        return response()->json(
            $assistantSearchService->search($request->string('message')->toString())
        );
    }
}
