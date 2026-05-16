<?php

namespace App\Models;

use App\Models\Concerns\ResolvesCategorySlug;
use App\Support\RelatedContentMap;
use Database\Factories\ToolFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tool extends Model
{
    /** @use HasFactory<ToolFactory> */
    use HasFactory;

    use ResolvesCategorySlug;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'body',
        'icon',
        'tool_type',
        'meta_title',
        'meta_description',
        'og_image',
        'is_featured',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
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

        return Post::published()
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
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->where('is_published', true)
            ->where(function (Builder $builder): void {
                $builder->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->published()->orderByDesc('published_at')->orderByDesc('id');
    }
}
