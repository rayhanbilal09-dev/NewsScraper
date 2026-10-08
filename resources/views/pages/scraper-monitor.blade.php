@extends('layouts.app')

@section('title', 'Web Scraper Monitor - Scraping Execution Logs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    System Health
                </span>
                <span class="text-xs text-slate-500 font-medium">Real-time Scraping & Parsing Telemetry</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                Web Scraper Monitor
            </h1>
        </div>

        <!-- Live Refresh / Scrape Actions -->
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Top Stats Row: 4 Summary Metric Cards (Yellow & Blue Accents) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-6">
        
        <!-- Card 1: Jobs Succeeded (Blue Accent) -->
        <div class="bg-white rounded-2xl border-l-4 border-l-blue-500 border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Jobs Succeeded</p>
                    <p class="text-3xl font-black text-slate-900 mt-1">{{ number_format($stats['succeeded_jobs_count']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500">
                <span class="text-blue-600 font-semibold mr-1">Completed</span> crawler tasks
            </div>
        </div>

        <!-- Card 2: Active Scrapers (Yellow Accent) -->
        <div class="bg-white rounded-2xl border-l-4 border-l-yellow-400 border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Scrapers</p>
                    <p class="text-3xl font-black text-slate-900 mt-1">{{ $stats['active_scrapers_count'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500">
                <span class="text-yellow-600 font-semibold mr-1">Configured</span> news channels & feeds
            </div>
        </div>

        <!-- Card 3: Failed Jobs (Yellow/Amber Accent) -->
        <div class="bg-white rounded-2xl border-l-4 border-l-amber-500 border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Failed Jobs</p>
                    <p class="text-3xl font-black text-slate-900 mt-1">{{ number_format($stats['failed_jobs_count']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500">
                <span class="text-amber-600 font-semibold mr-1">Gracefully handled</span> & logged
            </div>
        </div>

        <!-- Card 4: Proxy Health (Blue Accent) -->
        <div class="bg-white rounded-2xl border-l-4 border-l-blue-600 border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Proxy Health</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['proxy_health'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500">
                <span class="text-blue-600 font-semibold mr-1">Latency & HTTP</span> status monitored
            </div>
        </div>

    </div>

    <!-- Recent Jobs Table Section -->
    <div class="mt-10 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Table Title Header -->
        <div class="px-6 py-4.5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Recent Scraping Jobs</h2>
                <p class="text-xs text-slate-500">Latest crawler and extraction execution logs</p>
            </div>
            <div class="text-xs font-medium text-slate-500">
                Showing {{ $logs->count() }} most recent entries
            </div>
        </div>

        <!-- Full-Width Clean Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-100/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th scope="col" class="py-3.5 pl-6 pr-3">Status</th>
                        <th scope="col" class="px-3 py-3.5">Source</th>
                        <th scope="col" class="px-3 py-3.5">URL</th>
                        <th scope="col" class="px-3 py-3.5">Duration</th>
                        <th scope="col" class="px-3 py-3.5">Records</th>
                        <th scope="col" class="px-3 py-3.5">Executed At</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-sm">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Status Column (Green Check / Red Cross) -->
                            <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                @if($log->status === 'success')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Success
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Failed
                                    </span>
                                @endif
                            </td>

                            <!-- Source Column -->
                            <td class="whitespace-nowrap px-3 py-4 text-slate-900 font-semibold text-xs sm:text-sm">
                                {{ $log->source_name }}
                            </td>

                            <!-- URL Column (Truncated with Ellipsis) -->
                            <td class="px-3 py-4 max-w-xs sm:max-w-md">
                                <a href="{{ $log->url }}" target="_blank" title="{{ $log->url }}" class="inline-flex items-center gap-1 text-slate-600 hover:text-blue-600 font-mono text-xs truncate max-w-full group">
                                    <span class="truncate">{{ $log->url }}</span>
                                    <svg class="w-3 h-3 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </td>

                            <!-- Duration Column (e.g. 2s) -->
                            <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-slate-600">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-mono">
                                    {{ $log->duration_formatted }}
                                </span>
                            </td>

                            <!-- Records Column (e.g. 50 recs) -->
                            <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold {{ $log->records_count > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                {{ $log->records_count }} recs
                            </td>

                            <!-- Executed At Column -->
                            <td class="whitespace-nowrap px-3 py-4 text-xs text-slate-500">
                                {{ $log->created_at?->format('H:i:s d M') }}
                            </td>

                            <!-- Action Button: "View Logs" Modal Trigger -->
                            <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right">
                                <button type="button"
                                        onclick="openLogModal({{ json_encode($log) }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Logs
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm">
                                No scraping jobs recorded yet. Click "Run Scraper Now" above to initiate a job.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Log Details Modal -->
<div id="log-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeLogModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
            
            <!-- Modal Header -->
            <div class="px-6 py-4.5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2.5">
                    <div id="modal-status-badge"></div>
                    <h3 class="text-base font-bold text-slate-900" id="modal-source-name">Job Log Details</h3>
                </div>
                <button type="button" onclick="closeLogModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4 text-sm">
                
                <!-- Target URL -->
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Target URL</span>
                    <a id="modal-url" href="#" target="_blank" class="text-blue-600 hover:underline font-mono text-xs break-all bg-slate-50 p-2.5 rounded-lg border border-slate-100 block"></a>
                </div>

                <!-- Execution Metrics Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-medium text-slate-500 block">Duration</span>
                        <span id="modal-duration" class="font-bold text-slate-900 text-sm"></span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-medium text-slate-500 block">Records Parsed</span>
                        <span id="modal-records" class="font-bold text-slate-900 text-sm"></span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-medium text-slate-500 block">Start Time</span>
                        <span id="modal-start-time" class="font-semibold text-slate-700 text-xs"></span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-medium text-slate-500 block">End Time</span>
                        <span id="modal-end-time" class="font-semibold text-slate-700 text-xs"></span>
                    </div>
                </div>

                <!-- Error Message Details (if failed) -->
                <div id="modal-error-container" class="hidden">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-500 block mb-1">Error Trace / Reason</span>
                    <div class="bg-rose-50 border border-rose-200 rounded-xl p-3.5 text-rose-900 font-mono text-xs overflow-x-auto" id="modal-error-message">
                    </div>
                </div>

                <!-- Success Note (if success) -->
                <div id="modal-success-container" class="hidden">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 text-emerald-900 text-xs flex items-start gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Target content was successfully downloaded, parsed with DomCrawler, filtered using Sastrawi Indonesian Stopwords & Stemming, and scored with TextRank.</span>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="button" onclick="closeLogModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-lg transition">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function openLogModal(log) {
        document.getElementById('modal-source-name').innerText = log.source_name;
        
        const urlElem = document.getElementById('modal-url');
        urlElem.href = log.url;
        urlElem.innerText = log.url;

        document.getElementById('modal-duration').innerText = (log.duration_seconds || 0) + 's';
        document.getElementById('modal-records').innerText = (log.records_count || 0) + ' records';
        
        // Format dates
        document.getElementById('modal-start-time').innerText = log.start_time ? new Date(log.start_time).toLocaleTimeString() : '-';
        document.getElementById('modal-end-time').innerText = log.end_time ? new Date(log.end_time).toLocaleTimeString() : '-';

        const statusBadge = document.getElementById('modal-status-badge');
        const errorContainer = document.getElementById('modal-error-container');
        const successContainer = document.getElementById('modal-success-container');

        if (log.status === 'success') {
            statusBadge.innerHTML = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">SUCCESS</span>';
            errorContainer.classList.add('hidden');
            successContainer.classList.remove('hidden');
        } else {
            statusBadge.innerHTML = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-800">FAILED</span>';
            document.getElementById('modal-error-message').innerText = log.error_message || 'HTTP timeout or connection error';
            errorContainer.classList.remove('hidden');
            successContainer.classList.add('hidden');
        }

        document.getElementById('log-modal').classList.remove('hidden');
    }

    function closeLogModal() {
        document.getElementById('log-modal').classList.add('hidden');
    }

    // Close on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogModal();
        }
    });
</script>
@endpush
@endsection
