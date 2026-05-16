<?php

use App\Http\Controllers\InternalPostPublisherController;
use Illuminate\Support\Facades\Route;

Route::post('/internal/posts/from-ai', InternalPostPublisherController::class)
    ->middleware('throttle:10,1')
    ->name('api.internal.posts.from-ai');
