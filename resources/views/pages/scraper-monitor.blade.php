@extends('layouts.app')

@section('title', 'Web Scraper Monitor - Scraping Execution Logs & Telemetry')
@section('breadcrumb', 'Scraping Monitor')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ═══════════════ PAGE HEADER ═══════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 pb-6 animate-fade-in">
        <div>
            <div class="flex items-center gap-2.5 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-500/10 to-teal-500/10 text-emerald-700 border border-emerald-200/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Telemetry
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Scraping Health &amp; Logs</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Web Scraper Monitor
            </h1>
            <p class="text-sm text-slate-500 mt-1">Real-time HTTP crawling telemetry, response durations, and diagnostics</p>
        </div>

        {{-- Header Controls: Refresh & Live Trigger --}}
        <div class="flex items-center gap-2.5">
            <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 px-3.5 py-2 glass hover:bg-white text-slate-600 hover:text-slate-900 text-xs font-semibold rounded-xl border border-slate-200/80 shadow-xs hover:shadow-sm transition-all duration-200">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Refresh</span>
            </button>

            <form action="{{ route('scraper.trigger') }}" method="POST" @submit="isTriggering = true">
                @csrf
                <button type="submit"
                        :disabled="isTriggering"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-xs font-semibold shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 disabled:opacity-50">
                    <svg x-show="!isTriggering" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="isTriggering" x-cloak class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="isTriggering ? 'Running...' : 'Run Pipeline'"></span>
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════ STATS ROW (4 METRIC CARDS) ═══════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
        
        {{-- Card 1: Jobs Succeeded --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-200/60 p-5 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 group animate-slide-up stagger-1">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-emerald-100/50 to-teal-50/20 rounded-full blur-xl group-hover:scale-125 transition-transform duration-300"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jobs Succeeded</p>
                    <p class="text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ number_format($stats['succeeded_jobs_count']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5 text-emerald-600 font-semibold text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Successful Crawls
                </span>
                <span class="text-[10px] text-slate-400 font-mono">200 OK</span>
            </div>
        </div>

        {{-- Card 2: Active Scrapers --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-200/60 p-5 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 group animate-slide-up stagger-2">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-indigo-100/50 to-violet-50/20 rounded-full blur-xl group-hover:scale-125 transition-transform duration-300"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Active Scrapers</p>
                    <p class="text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $stats['active_scrapers_count'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5 text-indigo-600 font-semibold text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    Multi-Provider
                </span>
                <span class="text-[10px] text-slate-400">Tempo, Antara, CNN, Kompas</span>
            </div>
        </div>

        {{-- Card 3: Failed Jobs --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-200/60 p-5 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 group animate-slide-up stagger-3">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-rose-100/50 to-pink-50/20 rounded-full blur-xl group-hover:scale-125 transition-transform duration-300"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Failed Jobs</p>
                    <p class="text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ number_format($stats['failed_jobs_count']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-rose-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5 text-rose-600 font-semibold text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Diagnostics
                </span>
                <span class="text-[10px] text-slate-400">Captured in error_message</span>
            </div>
        </div>

        {{-- Card 4: Proxy Health / Success Rate --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-slate-200/60 p-5 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200 group animate-slide-up stagger-4">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-blue-100/50 to-sky-50/20 rounded-full blur-xl group-hover:scale-125 transition-transform duration-300"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Proxy Health</p>
                    <p class="text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $stats['proxy_health'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5 text-blue-600 font-semibold text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Success Rate
                </span>
                <span class="text-[10px] text-slate-400">Last 100 executions</span>
            </div>
        </div>

    </div>

    {{-- ═══════════════ RECENT LOGS TABLE CARD ═══════════════ --}}
    <div class="mt-10 bg-white rounded-2xl border border-slate-200/60 shadow-xs overflow-hidden animate-fade-in"
         x-data="{
             filterStatus: 'all',
             searchQuery: '',
             matches(itemStatus, itemSource, itemUrl) {
                 if (this.filterStatus !== 'all' && itemStatus !== this.filterStatus) return false;
                 if (this.searchQuery.trim() === '') return true;
                 const q = this.searchQuery.toLowerCase();
                 return itemSource.toLowerCase().includes(q) || itemUrl.toLowerCase().includes(q);
             }
         }">
        
        {{-- Card Header with Filter Controls --}}
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>Recent Scraping Jobs</span>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">{{ $logs->count() }} records</span>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Execution attempts, HTTP response statuses, and durations</p>
            </div>

            {{-- Interactive Filters --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Search Box --}}
                <div class="relative">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Filter by source or URL..."
                           class="w-48 sm:w-64 pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-white text-slate-700 placeholder-slate-400 focus:outline-hidden focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">&times;</button>
                </div>

                {{-- Status Filter Buttons --}}
                <div class="flex items-center rounded-xl bg-slate-100 p-0.5 border border-slate-200/60 text-xs">
                    <button type="button"
                            @click="filterStatus = 'all'"
                            :class="filterStatus === 'all' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800 font-medium'"
                            class="px-2.5 py-1 rounded-lg transition-all">All</button>
                    <button type="button"
                            @click="filterStatus = 'success'"
                            :class="filterStatus === 'success' ? 'bg-emerald-500 text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800 font-medium'"
                            class="px-2.5 py-1 rounded-lg transition-all">Success</button>
                    <button type="button"
                            @click="filterStatus = 'failed'"
                            :class="filterStatus === 'failed' ? 'bg-rose-500 text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800 font-medium'"
                            class="px-2.5 py-1 rounded-lg transition-all">Failed</button>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto max-h-[640px] overflow-y-auto">
            <table class="w-full text-left border-collapse">
                {{-- Sticky Header --}}
                <thead class="sticky top-0 z-10 bg-slate-50/95 backdrop-blur-xs text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-200/80">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3">Status</th>
                        <th scope="col" class="px-3 py-3.5">Source Name</th>
                        <th scope="col" class="px-3 py-3.5">Target Endpoint</th>
                        <th scope="col" class="px-3 py-3.5">Duration</th>
                        <th scope="col" class="px-3 py-3.5">Timestamp</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-xs">
                    @forelse($logs as $log)
                        @php
                            $logPayload = [
                                'id' => $log->id,
                                'source_name' => $log->source_name,
                                'url' => $log->url,
                                'status' => $log->status,
                                'start_time' => $log->start_time?->format('Y-m-d H:i:s'),
                                'end_time' => $log->end_time?->format('Y-m-d H:i:s'),
                                'duration' => $log->duration_formatted,
                                'error_message' => $log->error_message,
                                'created_at' => $log->created_at?->format('Y-m-d H:i:s'),
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors group"
                            x-show="matches('{{ $log->status }}', '{{ addslashes($log->source_name) }}', '{{ addslashes($log->url) }}')">
                            
                            {{-- Status Badge --}}
                            <td class="whitespace-nowrap py-3.5 pl-6 pr-3">
                                @if($log->status === 'success')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Success
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Failed
                                    </span>
                                @endif
                            </td>

                            {{-- Source Name with Icon --}}
                            <td class="whitespace-nowrap px-3 py-3.5 font-bold text-slate-800">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg {{ $log->status === 'success' ? 'bg-indigo-50 text-indigo-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center text-[10px] font-black shrink-0">
                                        {{ substr($log->source_name, 0, 1) }}
                                    </div>
                                    <span>{{ $log->source_name }}</span>
                                </div>
                            </td>

                            {{-- Target URL --}}
                            <td class="px-3 py-3.5 max-w-xs sm:max-w-md">
                                <a href="{{ $log->url }}" target="_blank" rel="noopener noreferrer" title="{{ $log->url }}"
                                   class="inline-flex items-center gap-1 font-mono text-[11px] text-slate-500 hover:text-indigo-600 truncate max-w-full group/link">
                                    <span class="truncate">{{ $log->url }}</span>
                                    <svg class="w-3 h-3 shrink-0 opacity-0 group-hover/link:opacity-100 transition-opacity text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </td>

                            {{-- Duration --}}
                            <td class="whitespace-nowrap px-3 py-3.5 font-mono text-[11px] font-semibold text-slate-700">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60">
                                    {{ $log->duration_formatted }}
                                </span>
                            </td>

                            {{-- Executed At --}}
                            <td class="whitespace-nowrap px-3 py-3.5 text-slate-400 font-mono text-[11px]">
                                {{ $log->created_at?->format('H:i:s d M Y') ?? '-' }}
                            </td>

                            {{-- View Logs Button --}}
                            <td class="whitespace-nowrap py-3.5 pl-3 pr-6 text-right">
                                <button type="button"
                                        @click="openModal({{ json_encode($logPayload) }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-700 border border-slate-200/70 hover:border-indigo-200 shadow-2xs transition-all duration-150 cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>View Logs</span>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                                No scraping jobs recorded yet. Click "Run Pipeline" above to perform your first crawl.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer Summary --}}
        <div class="px-6 py-3.5 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>Displaying up to 50 recent scraping telemetry events</span>
            <span class="font-mono text-[11px]">Auto-pruned after 30 days</span>
        </div>

    </div>

</div>
@endsection
