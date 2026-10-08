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
     * GET /trending-topics
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
                'last_updated_at' => $lastUpdatedAt ? \Carbon\Carbon::parse($lastUpdatedAt)->toIso8601String() : null,
                'data' => $topics,
                'trending_topics' => $topics,
            ]);
        }

        return view('pages.trending-topics', [
            'topics' => $topics,
            'lastUpdatedAt' => $lastUpdatedAt ? \Carbon\Carbon::parse($lastUpdatedAt) : null,
        ]);
    }

    /**
     * Dedicated API endpoint returning top 5 trending topics and last_updated_at.
     * GET /api/trending-topics
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
            'trending_topics' => $topics,
        ]);
    }
}
