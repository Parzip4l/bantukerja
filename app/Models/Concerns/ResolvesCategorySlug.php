<?php

namespace App\Models\Concerns;

trait ResolvesCategorySlug
{
    protected function resolveCategorySlug(): ?string
    {
        if ($this->relationLoaded('category')) {
            return $this->category?->slug;
        }

        return $this->category()->value('slug');
    }
}
