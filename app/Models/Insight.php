<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Insight extends Model
{
    protected $fillable = ['title', 'slug', 'label', 'body', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Insight $insight): void {
            $insight->slug = $insight->slug ?: Str::slug($insight->title);
        });
    }
}
