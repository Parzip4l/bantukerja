<?php

namespace App\Models;

use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'status',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ContactMessage $message): void {
            if ($message->status === 'new') {
                $message->read_at = null;

                return;
            }

            $message->read_at ??= now();
        });
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }
}
