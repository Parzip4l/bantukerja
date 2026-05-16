<?php

namespace App\Models;

use Database\Factories\AdSlotFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdSlot extends Model
{
    /** @use HasFactory<AdSlotFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'location',
        'code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
