<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InternalPostPublisherService
{
    public function create(array $payload): Post
    {
        $category = Category::query()
            ->where('type', 'blog')
            ->where('slug', $payload['category_slug'])
            ->firstOrFail();

        return DB::transaction(function () use ($payload, $category): Post {
            $status = $payload['status'] ?? 'draft';
            $publishedAt = $this->resolvePublishedAt($status, $payload['published_at'] ?? null);

            $post = Post::query()->create([
                'category_id' => $category->getKey(),
                'title' => $payload['title'],
                'slug' => $this->uniqueSlug($payload['slug'] ?? null, $payload['title']),
                'excerpt' => $payload['excerpt'],
                'content' => $payload['content'],
                'featured_image' => $payload['featured_image'] ?? null,
                'meta_title' => $payload['meta_title'] ?? $payload['title'],
                'meta_description' => $payload['meta_description'] ?? Str::limit(strip_tags($payload['excerpt']), 160),
                'og_image' => $payload['og_image'] ?? null,
                'status' => $status,
                'published_at' => $publishedAt,
            ]);

            foreach ($payload['faqs'] ?? [] as $index => $faq) {
                $post->faqs()->create([
                    'faqable_type' => Post::class,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $index + 1,
                    'is_active' => $faq['is_active'] ?? true,
                ]);
            }

            return $post->load(['category', 'faqs']);
        });
    }

    protected function uniqueSlug(?string $slug, string $title): string
    {
        $baseSlug = Str::slug($slug ?: $title);
        $candidate = $baseSlug !== '' ? $baseSlug : 'artikel-bantukerja';
        $counter = 2;

        while (Post::query()->where('slug', $candidate)->exists()) {
            $candidate = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    protected function resolvePublishedAt(string $status, mixed $publishedAt): ?Carbon
    {
        if ($status !== 'published') {
            return null;
        }

        if (blank($publishedAt)) {
            return now();
        }

        return Carbon::parse($publishedAt);
    }
}
