@extends('layouts.app')

@section('title', 'Trending Topics - Top Extracted Keywords')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Page Header & Last Updated Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Live Topic Extraction
                </span>
                <span class="text-xs text-slate-500 font-medium">Top 5 Trending News Topics</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                Trending Topics
            </h1>
        </div>

        <!-- Last Updated Badge / Text -->
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200/80 rounded-xl shadow-xs text-sm text-slate-600">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-slate-500">Last updated at:</span>
            <span class="font-bold text-slate-900">
                @if($lastUpdatedAt)
                    {{ $lastUpdatedAt->format('d M Y, H:i:s') }}
                    <span class="text-xs text-slate-400 font-normal">({{ $lastUpdatedAt->diffForHumans() }})</span>
                @else
                    <span class="text-slate-400 italic">Never updated yet</span>
                @endif
            </span>
        </div>
    </div>

    <!-- Topics Grid (4 Columns on desktop, responsive) -->
    <div class="mt-8">
        @if($topics->isEmpty())
            <!-- Empty state if no topics yet -->
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No Trending Topics Available</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto mt-1 mb-6">
                    Run the news scraper pipeline to extract news articles, apply Sastrawi stemming, and rank top trending topics with TextRank.
                </p>
                <form action="{{ route('scraper.trigger') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        Run First Scrape Pipeline
                    </button>
                </form>
            </div>
        @else
            <!-- Responsive Grid: 4 columns on large screens -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($topics as $index => $topic)
                    @php
                        $category = $topic->category ?: '#Umum';
                        $catLower = strtolower($category);

                        // Category specific header colors & icons
                        if (str_contains($catLower, 'politik')) {
                            $headerBg = 'bg-blue-400 text-slate-900';
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />';
                        } elseif (str_contains($catLower, 'tekno')) {
                            $headerBg = 'bg-yellow-400 text-slate-900';
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
                        } elseif (str_contains($catLower, 'ekonomi') || str_contains($catLower, 'bisnis')) {
                            $headerBg = 'bg-emerald-400 text-slate-900';
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />';
                        } elseif (str_contains($catLower, 'pendidikan')) {
                            $headerBg = 'bg-indigo-400 text-white';
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5" />';
                        } elseif (str_contains($catLower, 'kesehatan')) {
                            $headerBg = 'bg-rose-400 text-white';
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
                        } elseif (str_contains($catLower, 'hukum')) {
                            $headerBg = 'bg-amber-400 text-slate-900';
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />';
                        } else {
                            $headerBg = ($index % 2 === 0) ? 'bg-yellow-400 text-slate-900' : 'bg-blue-400 text-slate-900';
                            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />';
                        }
                    @endphp

                    <!-- Topic Card -->
                    <div class="flex flex-col rounded-2xl overflow-hidden border border-slate-200/90 shadow-sm hover:shadow-md transition-all duration-200 group bg-white">
                        
                        <!-- Colorful Header (Icon + Category/Hashtag) -->
                        <div class="{{ $headerBg }} px-5 py-3.5 flex items-center justify-between font-semibold shadow-inner">
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 rounded-lg bg-black/10 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $iconPath !!}
                                    </svg>
                                </span>
                                <span class="tracking-tight text-sm font-bold">{{ $category }}</span>
                            </div>

                            <!-- Rank Badge -->
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white/30 backdrop-blur-xs text-xs font-black">
                                #{{ $index + 1 }}
                            </span>
                        </div>

                        <!-- White Card Body (Bordered layout displaying Topic text) -->
                        <div class="bg-white p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Topic Text (Format: "Topik: #Category - Topic Name") -->
                                <h2 class="text-base font-bold text-slate-900 leading-snug group-hover:text-blue-600 transition-colors">
                                    {{ $topic->topic_name }}
                                </h2>
                            </div>

                            <!-- Card Footer: Relevance Score & Timestamp -->
                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-slate-400 font-medium">TextRank:</span>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 font-semibold text-slate-700">
                                        {{ number_format($topic->score_or_count, 1) }}
                                    </span>
                                </div>
                                
                                <span class="text-[11px] text-slate-400 font-medium">
                                    {{ $topic->last_successful_update?->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Tech Stack Pipeline Explanation -->
    <div class="mt-12 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            NLP & Scraping Processing Pipeline
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-bold text-blue-600 block mb-1">1. Guzzle & DomCrawler</span>
                <p class="text-xs text-slate-600">Fetches live RSS feeds & HTML articles from Kompas, Tempo, Antara, and CNN Indonesia.</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-bold text-blue-600 block mb-1">2. Sastrawi Stopwords</span>
                <p class="text-xs text-slate-600">Eliminates high-frequency Indonesian non-content filler words (yang, di, dan, dari, dsb).</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-bold text-blue-600 block mb-1">3. Sastrawi Stemming</span>
                <p class="text-xs text-slate-600">Reduces affixed Indonesian terms to their root morphological base (Nazief-Adriani algorithm).</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-xs font-bold text-blue-600 block mb-1">4. PHP-Science-TextRank</span>
                <p class="text-xs text-slate-600">Builds a graph-based word network via TextRankFacade to compute PageRank term salience and scores.</p>
            </div>
        </div>
    </div>

</div>
@endsection
