<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\InternalPostPublisherController;
use Illuminate\Support\Facades\Route;

Route::post('/assistant/search', [AssistantController::class, 'search'])
    ->middleware('throttle:30,1')
    ->name('api.assistant.search');

Route::post('/internal/posts/from-ai', InternalPostPublisherController::class)
    ->middleware('throttle:10,1')
    ->name('api.internal.posts.from-ai');
