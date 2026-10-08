@extends('layouts.app')

@section('title', 'Trending Topics - Top Extracted Indonesian Topics')
@section('breadcrumb', 'Trending Topics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ═══════════════ PAGE HEADER ═══════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 pb-6 animate-fade-in">
        <div>
            <div class="flex items-center gap-2.5 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gradient-to-r from-indigo-500/10 to-violet-500/10 text-indigo-700 border border-indigo-200/40">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    Live NLP
                </span>
                <span class="text-[11px] text-slate-400 font-medium">TextRank &amp; Sastrawi Pipeline</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Trending Topics
            </h1>
            <p class="text-sm text-slate-500 mt-1">Top-scored Indonesian news topics from multi-source web scraping</p>
        </div>

        {{-- Last Updated Badge --}}
        <div class="flex items-center gap-2 text-xs text-slate-500 glass px-4 py-2.5 rounded-xl border border-slate-200/60 shadow-sm">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="text-slate-400">Last updated at:</span>
            <span class="font-semibold text-slate-700">
                @if($lastUpdatedAt)
                    {{ $lastUpdatedAt->format('d M Y, H:i') }}
                    <span class="text-slate-400 font-normal">({{ $lastUpdatedAt->diffForHumans() }})</span>
                @else
                    <span class="italic text-slate-400">Not yet</span>
                @endif
            </span>
        </div>
    </div>

    {{-- ═══════════════ TOPIC CARDS GRID ═══════════════ --}}
    <div class="mt-2">
        @if($topics->isEmpty())
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-16 text-center shadow-sm animate-fade-in">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-50 to-violet-50 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-sm animate-float">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No Trending Topics Found</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto mt-2 mb-8 leading-relaxed">
                    Trigger the scraper to crawl Indonesian news headlines, apply Sastrawi stemming, and rank the top 5 trending topics via TextRank.
                </p>
                <form action="{{ route('scraper.trigger') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl text-sm font-semibold shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /></svg>
                        Run Scraper Pipeline
                    </button>
                </form>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
                @foreach($topics as $index => $topic)
                    @php
                        $category = $topic->category ?: '#Umum';
                        $catLower = strtolower($category);

                        // Premium category theming with vivid gradients
                        if (str_contains($catLower, 'politik')) {
                            $gradientFrom = 'from-blue-500'; $gradientTo = 'to-blue-700';
                            $accentBg = 'bg-blue-50'; $accentText = 'text-blue-700'; $accentBorder = 'border-blue-200/60';
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />';
                        } elseif (str_contains($catLower, 'tekno') || str_contains($catLower, 'teknologi')) {
                            $gradientFrom = 'from-amber-400'; $gradientTo = 'to-orange-500';
                            $accentBg = 'bg-amber-50'; $accentText = 'text-amber-700'; $accentBorder = 'border-amber-200/60';
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
                        } elseif (str_contains($catLower, 'ekonomi') || str_contains($catLower, 'bisnis')) {
                            $gradientFrom = 'from-emerald-500'; $gradientTo = 'to-teal-600';
                            $accentBg = 'bg-emerald-50'; $accentText = 'text-emerald-700'; $accentBorder = 'border-emerald-200/60';
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />';
                        } elseif (str_contains($catLower, 'pendidikan') || str_contains($catLower, 'edukasi')) {
                            $gradientFrom = 'from-violet-500'; $gradientTo = 'to-purple-700';
                            $accentBg = 'bg-violet-50'; $accentText = 'text-violet-700'; $accentBorder = 'border-violet-200/60';
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5" />';
                        } elseif (str_contains($catLower, 'kesehatan')) {
                            $gradientFrom = 'from-rose-500'; $gradientTo = 'to-pink-600';
                            $accentBg = 'bg-rose-50'; $accentText = 'text-rose-700'; $accentBorder = 'border-rose-200/60';
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
                        } else {
                            $gradientFrom = 'from-slate-500'; $gradientTo = 'to-slate-700';
                            $accentBg = 'bg-slate-50'; $accentText = 'text-slate-700'; $accentBorder = 'border-slate-200/60';
                            $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />';
                        }
                    @endphp

                    <div class="flex flex-col rounded-2xl overflow-hidden bg-white border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group animate-slide-up stagger-{{ $index + 1 }}">
                        {{-- Gradient Header --}}
                        <div class="bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} px-4 py-3.5 flex items-center justify-between relative overflow-hidden">
                            {{-- Decorative circles --}}
                            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-white/10"></div>
                            <div class="absolute -bottom-6 -left-4 w-12 h-12 rounded-full bg-white/[0.07]"></div>

                            <div class="flex items-center gap-2 relative z-10">
                                <span class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconSvg !!}</svg>
                                </span>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-white/90">{{ $category }}</span>
                            </div>

                            {{-- Rank Badge --}}
                            <span class="relative z-10 w-7 h-7 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center text-[11px] font-black text-white">
                                {{ $index + 1 }}
                            </span>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <h2 class="text-[13px] sm:text-sm font-bold text-slate-900 leading-relaxed group-hover:text-indigo-600 transition-colors duration-200">
                                {{ $topic->topic_name }}
                            </h2>

                            <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] text-slate-400 font-medium">Score</span>
                                    <span class="px-2 py-0.5 rounded-lg {{ $accentBg }} font-mono text-[11px] font-bold {{ $accentText }} border {{ $accentBorder }}">
                                        {{ number_format($topic->score_or_count, 1) }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-slate-400">
                                    {{ $topic->last_successful_update?->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══════════════ NLP PIPELINE INFO ═══════════════ --}}
    <div class="mt-12 bg-white rounded-2xl border border-slate-200/60 overflow-hidden shadow-sm animate-fade-in">
        {{-- Section Header --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">End-to-End Scraping &amp; Topic Extraction Pipeline</h3>
                    <p class="text-[11px] text-slate-500">How raw HTML becomes ranked trending topics</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @php
                    $pipelineSteps = [
                        ['num' => '01', 'title' => 'Guzzle & DomCrawler', 'desc' => 'Crawl Indonesian RSS feeds and parse HTML with custom headers, timeouts, and error handling.', 'color' => 'indigo'],
                        ['num' => '02', 'title' => 'Sastrawi Stopwords', 'desc' => 'Filter Indonesian stop words (yang, di, dan, dari, ke) using StopWordRemoverFactory.', 'color' => 'violet'],
                        ['num' => '03', 'title' => 'Sastrawi Stemmer', 'desc' => 'Reduce morphological forms to root words using the Nazief-Adriani algorithm.', 'color' => 'purple'],
                        ['num' => '04', 'title' => 'TextRank Scoring', 'desc' => 'Build co-occurrence graph via TextRankFacade and extract top-scored trending topics.', 'color' => 'fuchsia'],
                    ];
                @endphp

                @foreach($pipelineSteps as $i => $step)
                    <div class="relative p-4 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-{{ $step['color'] }}-200 hover:bg-{{ $step['color'] }}-50/30 transition-all duration-200 group/step">
                        <div class="text-[10px] font-black text-{{ $step['color'] }}-300 mb-2 font-mono">STEP {{ $step['num'] }}</div>
                        <span class="font-bold text-sm text-slate-800 block mb-1.5 group-hover/step:text-{{ $step['color'] }}-700 transition-colors">{{ $step['title'] }}</span>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                        @if($i < 3)
                            <div class="hidden md:block absolute top-1/2 -right-3 text-slate-300 -translate-y-1/2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
