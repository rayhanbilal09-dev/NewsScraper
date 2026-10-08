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
     * Indicates if the model should be timestamped.
     * Table schema does not include created_at/updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

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
     * Returns a Tailwind header background color and text styling for cards.
     * Matches requirement: Distinct, colorful headers (e.g., blue, yellow, emerald).
     */
    public function getCategoryThemeAttribute(): array
    {
        $normalized = strtolower($this->category ?? '');

        if (str_contains($normalized, 'politik')) {
            return [
                'header_bg' => 'bg-blue-500 text-white',
                'badge_bg' => 'bg-blue-50 text-blue-700 border-blue-200',
                'pill_color' => 'bg-blue-600',
                'accent' => 'blue',
            ];
        }
        if (str_contains($normalized, 'tekno') || str_contains($normalized, 'teknologi')) {
            return [
                'header_bg' => 'bg-amber-400 text-slate-950',
                'badge_bg' => 'bg-amber-50 text-amber-800 border-amber-200',
                'pill_color' => 'bg-amber-500',
                'accent' => 'amber',
            ];
        }
        if (str_contains($normalized, 'ekonomi') || str_contains($normalized, 'bisnis')) {
            return [
                'header_bg' => 'bg-emerald-500 text-white',
                'badge_bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'pill_color' => 'bg-emerald-600',
                'accent' => 'emerald',
            ];
        }
        if (str_contains($normalized, 'pendidikan') || str_contains($normalized, 'edukasi')) {
            return [
                'header_bg' => 'bg-indigo-500 text-white',
                'badge_bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'pill_color' => 'bg-indigo-600',
                'accent' => 'indigo',
            ];
        }
        if (str_contains($normalized, 'kesehatan')) {
            return [
                'header_bg' => 'bg-rose-500 text-white',
                'badge_bg' => 'bg-rose-50 text-rose-700 border-rose-200',
                'pill_color' => 'bg-rose-600',
                'accent' => 'rose',
            ];
        }

        // Fallback palette
        return [
            'header_bg' => 'bg-sky-500 text-white',
            'badge_bg' => 'bg-sky-50 text-sky-700 border-sky-200',
            'pill_color' => 'bg-sky-600',
            'accent' => 'sky',
        ];
    }
}
