<?php

namespace App\Models;

use App\Models\Concerns\ResolvesCategorySlug;
use App\Support\RelatedContentMap;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    use ResolvesCategorySlug;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'og_image',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('sort_order');
    }

    public function relatedPosts()
    {
        $categorySlugs = RelatedContentMap::for($this->resolveCategorySlug())['blog'];

        return static::published()
            ->whereHas('category', fn ($builder) => $builder->whereIn('slug', $categorySlugs))
            ->whereKeyNot($this->getKey())
            ->latestPublished();
    }

    public function relatedTools()
    {
        $categorySlugs = RelatedContentMap::for($this->resolveCategorySlug())['tool'];

        return Tool::published()
            ->whereHas('category', fn ($builder) => $builder->whereIn('slug', $categorySlugs))
            ->latestPublished();
    }

    public function relatedTemplates()
    {
        $categorySlugs = RelatedContentMap::for($this->resolveCategorySlug())['template'];

        return DocumentTemplate::published()
            ->whereHas('category', fn ($builder) => $builder->whereIn('slug', $categorySlugs))
            ->latestPublished();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $builder): void {
                $builder->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->published()->orderByDesc('published_at')->orderByDesc('id');
    }
}
