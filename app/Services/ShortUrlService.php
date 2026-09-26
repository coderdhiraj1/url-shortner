<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Str;

class ShortUrlService
{
    public function create(User $user, string $originalUrl): ShortUrl 
    {
        do {
            $shortCode = Str::random(6);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        return ShortUrl::create([
            'company_id' => $user->company_id,
            'created_by' => $user->id,
            'original_url' => $originalUrl,
            'short_code' => $shortCode,
            'hits' => 0,
        ]);
    }
}