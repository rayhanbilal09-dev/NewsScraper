<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapingLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scraping_logs';

    /**
     * The attributes that are mass assignable.
     * Schema strictly contains: source_name, url, status, start_time, end_time, error_message
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'source_name',
        'url',
        'status',
        'start_time',
        'end_time',
        'error_message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Computed duration in seconds between start_time and end_time.
     */
    public function getDurationSecondsAttribute(): float
    {
        if ($this->start_time && $this->end_time) {
            return (float) round(abs($this->end_time->diffInMilliseconds($this->start_time) / 1000), 2);
        }

        return 0.0;
    }

    /**
     * Human-friendly formatted duration.
     */
    public function getDurationFormattedAttribute(): string
    {
        if ($this->start_time && $this->end_time) {
            $diff = abs($this->end_time->diffInMilliseconds($this->start_time) / 1000);
            return round($diff, 2) . 's';
        }

        return '0s';
    }

    /**
     * Scope for successful logs.
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed logs.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
