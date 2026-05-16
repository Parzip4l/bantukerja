<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GeneratorTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'generator_type',
        'description',
        'preview_image',
        'view_path',
        'paper_size',
        'orientation',
        'is_active',
        'is_premium',
        'sort_order',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForType(Builder $query, string $type): Builder
    {
        return $query->whereIn('generator_type', [$type, 'all']);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->where('is_premium', false);
    }
}
