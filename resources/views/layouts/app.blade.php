<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'News Scraper & Topic Extraction') - Monitoring System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        amber: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('trending-topics.index') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-base font-bold tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">NewsScraper<span class="text-blue-600">.AI</span></span>
                            <span class="hidden sm:inline-block text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 ml-1.5 border border-blue-200/60">NLP & Sastrawi</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <nav class="hidden md:flex items-center gap-1 ml-8">
                        <a href="{{ route('trending-topics.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('trending-topics.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 {{ request()->routeIs('trending-topics.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                Trending Topics
                            </span>
                        </a>

                        <a href="{{ route('scraping-logs.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-semibold transition-all {{ request()->routeIs('scraping-logs.*') || request()->routeIs('scraper-monitor.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 {{ request()->routeIs('scraping-logs.*') || request()->routeIs('scraper-monitor.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Web Scraper Monitor
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-3">
                    <!-- Status Indicator -->
                    <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Pipeline Active
                    </div>

                    <!-- Trigger Scraping Button -->
                    <form action="{{ route('scraper.trigger') }}" method="POST" class="inline" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').classList.add('opacity-75'); this.querySelector('#btn-spinner').classList.remove('hidden'); this.querySelector('#btn-icon').classList.add('hidden');">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg id="btn-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg id="btn-spinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Run Scraper Now</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Navigation Bar -->
            <div class="md:hidden flex items-center justify-around py-2 border-t border-slate-100 text-xs">
                <a href="{{ route('trending-topics.index') }}" class="py-1.5 px-3 rounded-md font-medium {{ request()->routeIs('trending-topics.*') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-slate-600' }}">
                    Trending Topics
                </a>
                <a href="{{ route('scraping-logs.index') }}" class="py-1.5 px-3 rounded-md font-medium {{ request()->routeIs('scraping-logs.*') || request()->routeIs('scraper-monitor.*') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-slate-600' }}">
                    Scraper Monitor
                </a>
            </div>
        </div>
    </header>

    <!-- Flash Alert Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-xs flex items-start gap-3">
                <div class="shrink-0 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 shadow-xs flex items-start gap-3">
                <div class="shrink-0 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 text-sm font-medium text-rose-800">
                    {{ session('error') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 py-6">
        @yield('content')
    </main>

    <!-- Modern Clean Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-slate-700">News Scraper System</span>
                <span>•</span>
                <span>PHP 8.2 & Laravel 12</span>
                <span>•</span>
                <span>Sastrawi Stemming & TextRank</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ url('/api/trending-topics') }}" target="_blank" class="text-blue-600 hover:underline">API: Trending Topics</a>
                <a href="{{ url('/api/scraping-logs') }}" target="_blank" class="text-blue-600 hover:underline">API: Scraping Logs</a>
                <span>&copy; {{ date('Y') }} All rights reserved</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
