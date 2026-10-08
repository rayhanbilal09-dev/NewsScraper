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
        'duration_seconds',
        'records_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_seconds' => 'float',
        'records_count' => 'integer',
    ];

    /**
     * Human-friendly formatted duration.
     */
    public function getDurationFormattedAttribute(): string
    {
        if ($this->duration_seconds !== null && $this->duration_seconds > 0) {
            return $this->duration_seconds < 1
                ? number_format($this->duration_seconds, 2) . 's'
                : round($this->duration_seconds, 1) . 's';
        }

        if ($this->start_time && $this->end_time) {
            $diff = $this->end_time->diffInSeconds($this->start_time);
            return $diff . 's';
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
