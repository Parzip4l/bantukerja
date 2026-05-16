<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratorDownloadLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'generator_type',
        'template_slug',
        'action',
        'ip_hash',
        'user_agent_hash',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
