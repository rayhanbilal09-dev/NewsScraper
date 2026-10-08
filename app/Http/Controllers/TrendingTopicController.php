<?php

namespace App\Http\Controllers;

use App\Models\TrendingTopic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrendingTopicController extends Controller
{
    /**
     * Display trending topics page or return JSON payload.
     */
    public function index(Request $request): View|JsonResponse
    {
        $topics = TrendingTopic::orderBy('score_or_count', 'desc')
            ->take(5)
            ->get();

        $lastUpdatedAt = TrendingTopic::max('last_successful_update');

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'last_updated_at' => $lastUpdatedAt,
                'data' => $topics->map(function ($topic, $index) {
                    return [
                        'rank' => $index + 1,
                        'id' => $topic->id,
                        'topic_name' => $topic->topic_name,
                        'category' => $topic->category,
                        'score_or_count' => $topic->score_or_count,
                        'last_successful_update' => $topic->last_successful_update?->toIso8601String(),
                    ];
                }),
            ]);
        }

        return view('pages.trending-topics', [
            'topics' => $topics,
            'lastUpdatedAt' => $lastUpdatedAt ? \Carbon\Carbon::parse($lastUpdatedAt) : null,
        ]);
    }

    /**
     * Dedicated API endpoint for trending topics.
     */
    public function apiIndex(): JsonResponse
    {
        $topics = TrendingTopic::orderBy('score_or_count', 'desc')
            ->take(5)
            ->get();

        $lastUpdatedAt = TrendingTopic::max('last_successful_update');

        return response()->json([
            'status' => 'success',
            'last_updated_at' => $lastUpdatedAt ? \Carbon\Carbon::parse($lastUpdatedAt)->toIso8601String() : null,
            'data' => $topics,
        ]);
    }
}
