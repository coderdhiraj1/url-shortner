<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ShortUrl extends Model
{
    protected $fillable = [
        'company_id',
        'created_by',
        'original_url',
        'short_code',
        'hits',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilterByDate(Builder $query, string $filter): Builder
    {
        return match ($filter) {
            'today' => $query->whereDate('created_at', today()),

            'this_week' => $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]),

            'this_month' => $query->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]),

            'last_month' => $query->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth(),
            ]),

            default => $query,
        };
    }
}