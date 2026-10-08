<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendingTopic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trending_topics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'topic_name',
        'category',
        'score_or_count',
        'last_successful_update',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score_or_count' => 'float',
        'last_successful_update' => 'datetime',
    ];

    /**
     * Returns a Tailwind header background color depending on category or fallback alternate palette.
     */
    public function getHeaderBgClassAttribute(): string
    {
        $normalized = strtolower($this->category ?? '');

        if (str_contains($normalized, 'politik')) {
            return 'bg-blue-500 text-white';
        }
        if (str_contains($normalized, 'tekno') || str_contains($normalized, 'teknologi')) {
            return 'bg-yellow-400 text-gray-900';
        }
        if (str_contains($normalized, 'ekonomi') || str_contains($normalized, 'bisnis')) {
            return 'bg-emerald-500 text-white';
        }
        if (str_contains($normalized, 'pendidikan')) {
            return 'bg-indigo-500 text-white';
        }
        if (str_contains($normalized, 'kesehatan')) {
            return 'bg-rose-500 text-white';
        }

        // Alternating fallback colors
        $colors = [
            'bg-yellow-400 text-gray-900',
            'bg-blue-500 text-white',
            'bg-amber-400 text-gray-900',
            'bg-sky-500 text-white',
        ];

        return $colors[$this->id % count($colors)] ?? 'bg-blue-400 text-white';
    }
}
