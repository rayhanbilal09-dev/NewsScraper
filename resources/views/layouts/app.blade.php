<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Scheduled News Web Scraper with Topic Extraction using Sastrawi NLP & PHP-Science-TextRank. Monitor scraping jobs and trending Indonesian news topics in real-time.">
    <title>@yield('title', 'Scheduled News Web Scraper') - Dashboard</title>

    <!-- Google Fonts: Inter + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                        'slide-in-left': 'slideInLeft 0.3s ease-out forwards',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'shimmer': 'shimmer 2s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-10px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-6px)' },
                        },
                    },
                },
            },
        }
    </script>

    <!-- Alpine.js via CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* Premium scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.4); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.6); }

        /* Glassmorphism utilities */
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .glass-dark { background: rgba(15,23,42,0.95); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }

        /* Gradient text */
        .text-gradient { background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        /* Subtle noise texture overlay */
        .noise::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.015;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* Sidebar glow effect */
        .sidebar-glow { box-shadow: 4px 0 24px -2px rgba(99, 102, 241, 0.08); }

        /* Card shine on hover */
        .card-shine { position: relative; overflow: hidden; }
        .card-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.03) 50%, transparent 60%);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }
        .card-shine:hover::after { opacity: 1; }

        /* Stagger animation delays */
        .stagger-1 { animation-delay: 0.05s; }
        .stagger-2 { animation-delay: 0.1s; }
        .stagger-3 { animation-delay: 0.15s; }
        .stagger-4 { animation-delay: 0.2s; }
        .stagger-5 { animation-delay: 0.25s; }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50"
      x-data="{
          sidebarOpen: false,
          sidebarCollapsed: false,
          modalOpen: false,
          modalData: null,
          isTriggering: false,
          openModal(log) { this.modalData = log; this.modalOpen = true; },
          closeModal() { this.modalOpen = false; setTimeout(() => this.modalData = null, 200); }
      }"
      @keydown.escape.window="closeModal()">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" x-cloak
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- SIDEBAR: Fixed left-side collapsible navigation (Dark modern theme bg-slate-900) -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col glass-dark text-white transition-all duration-300 ease-in-out border-r border-white/[0.06] sidebar-glow"
           :class="{
               'w-[272px]': !sidebarCollapsed,
               'w-[72px]': sidebarCollapsed,
               'translate-x-0': sidebarOpen,
               '-translate-x-full lg:translate-x-0': !sidebarOpen
           }">

        <!-- Sidebar Brand -->
        <div class="flex items-center justify-between h-16 px-5 border-b border-white/[0.06] shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-3 overflow-hidden group">
                <div class="relative w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 shrink-0 group-hover:shadow-indigo-500/40 group-hover:scale-105 transition-all duration-300">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <!-- Animated ring -->
                    <div class="absolute inset-0 rounded-xl border border-white/20 animate-pulse-slow"></div>
                </div>
                <div class="whitespace-nowrap transition-all duration-300" x-show="!sidebarCollapsed" x-transition>
                    <div class="text-[15px] font-bold tracking-tight text-white/95">NewsScraper</div>
                    <div class="text-[10px] text-slate-400 font-medium tracking-wide">NLP Topic Extraction</div>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 lg:hidden transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
            <div x-show="!sidebarCollapsed" class="px-3 pb-3 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">Navigation</div>

            @php $isTrendingActive = request()->routeIs('trending-topics.*') || request()->routeIs('home'); @endphp
            <a href="{{ route('trending-topics.index') }}"
               class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-200 {{ $isTrendingActive ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}"
               :title="sidebarCollapsed ? 'Trending Topics' : ''">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $isTrendingActive ? 'bg-white/15' : 'bg-white/[0.04] group-hover:bg-white/[0.08]' }} transition-colors">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <span class="whitespace-nowrap" x-show="!sidebarCollapsed" x-transition>Trending Topics</span>
                @if($isTrendingActive)<span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-white/80"></span>@endif
            </a>

            @php $isMonitorActive = request()->routeIs('scraping-logs.*') || request()->routeIs('scraper-monitor.*'); @endphp
            <a href="{{ route('scraping-logs.index') }}"
               class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-200 {{ $isMonitorActive ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:text-white hover:bg-white/[0.06]' }}"
               :title="sidebarCollapsed ? 'Scraping Monitor' : ''">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $isMonitorActive ? 'bg-white/15' : 'bg-white/[0.04] group-hover:bg-white/[0.08]' }} transition-colors">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <span class="whitespace-nowrap" x-show="!sidebarCollapsed" x-transition>Scraping Monitor</span>
                @if($isMonitorActive)<span x-show="!sidebarCollapsed" class="ml-auto w-1.5 h-1.5 rounded-full bg-white/80"></span>@endif
            </a>

            <!-- Separator -->
            <div class="!my-4 border-t border-white/[0.06]"></div>
            <div x-show="!sidebarCollapsed" class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Actions</div>

            <!-- Run Scraper CTA -->
            <form action="{{ route('scraper.trigger') }}" method="POST" @submit="isTriggering = true">
                @csrf
                <button type="submit" :disabled="isTriggering"
                    class="w-full group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all duration-200 disabled:opacity-40"
                    :title="sidebarCollapsed ? 'Run Scraper' : ''">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-emerald-500/10 text-emerald-400 group-hover:bg-emerald-500/20 transition-colors">
                        <svg x-show="!isTriggering" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <svg x-show="isTriggering" x-cloak class="w-[18px] h-[18px] animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </div>
                    <span class="whitespace-nowrap" x-show="!sidebarCollapsed" x-text="isTriggering ? 'Scraping...' : 'Run Scraper Now'" x-transition></span>
                </button>
            </form>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-white/[0.06] shrink-0" x-show="!sidebarCollapsed" x-transition>
            <div class="flex items-center gap-2.5 px-1">
                <div class="relative">
                    <span class="flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] font-semibold text-slate-300">Pipeline Active</p>
                    <p class="text-[10px] text-slate-500 font-mono">Sastrawi + TextRank v1.2</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- MAIN CONTENT WRAPPER -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col min-h-screen transition-all duration-300 ease-in-out"
         :class="{ 'lg:pl-[272px]': !sidebarCollapsed, 'lg:pl-[72px]': sidebarCollapsed }">

        <!-- Top Header Bar -->
        <header class="sticky top-0 z-30 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 glass border-b border-slate-200/60">
            <div class="flex items-center gap-3">
                <!-- Mobile hamburger -->
                <button @click="sidebarOpen = true" class="p-2 -ml-1 text-slate-500 rounded-xl lg:hidden hover:text-slate-900 hover:bg-slate-100 transition-colors" aria-label="Open sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <!-- Desktop collapse toggle -->
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="hidden lg:flex p-2 text-slate-400 rounded-xl hover:text-slate-700 hover:bg-slate-100 transition-all" :title="sidebarCollapsed ? 'Expand' : 'Collapse'">
                    <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                </button>
                <!-- Breadcrumb -->
                <div class="hidden sm:flex items-center gap-1.5 text-xs text-slate-400">
                    <span class="font-medium text-slate-500">Dashboard</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    <span class="font-semibold text-slate-700">@yield('breadcrumb', 'Overview')</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <!-- Quick scraper button -->
                <form action="{{ route('scraper.trigger') }}" method="POST" @submit="isTriggering = true">
                    @csrf
                    <button type="submit" :disabled="isTriggering"
                            class="inline-flex items-center gap-1.5 pl-3 pr-3.5 py-[7px] rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-xs font-semibold shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 disabled:opacity-50">
                        <svg x-show="!isTriggering" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        <svg x-show="isTriggering" x-cloak class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span x-text="isTriggering ? 'Scraping...' : 'Run Scraper'"></span>
                    </button>
                </form>
                <!-- Status -->
                <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/70 text-[11px] font-semibold text-emerald-700">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                    <span class="hidden sm:inline">Online</span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="px-4 sm:px-6 lg:px-8 pt-4">
            <div x-data="{ show: true }" x-show="show" x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="flex items-center justify-between p-4 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/60 text-emerald-800 text-sm shadow-sm animate-slide-up">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div><span class="font-bold">Success!</span> <span class="text-emerald-700">{{ session('success') }}</span></div>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 p-1 rounded-lg hover:bg-emerald-100 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
        </div>
        @endif

        <!-- Main Content Area -->
        <main class="flex-1 pb-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-5 px-4 sm:px-6 lg:px-8 border-t border-slate-200/60 bg-white/50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-slate-400">
                <span>Built with <span class="text-slate-500 font-medium">Sastrawi NLP</span> &bull; <span class="text-slate-500 font-medium">PHP-Science-TextRank</span> &bull; <span class="text-slate-500 font-medium">GuzzleHTTP</span></span>
                <span class="font-mono">Laravel 12 &bull; Tailwind CSS &bull; Alpine.js</span>
            </div>
        </footer>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- MODAL: Scraping Log Detail Viewer -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Backdrop -->
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="closeModal()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Panel -->
            <div x-show="modalOpen"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-2xl overflow-hidden text-left bg-white shadow-2xl rounded-2xl border border-slate-200/60 sm:my-8">

                <!-- Modal gradient header bar -->
                <div class="h-1 w-full" :class="modalData && modalData.status === 'success' ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : 'bg-gradient-to-r from-rose-400 to-pink-500'"></div>

                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                                 :class="modalData && modalData.status === 'success' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'">
                                <template x-if="modalData && modalData.status === 'success'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></template>
                                <template x-if="modalData && modalData.status !== 'success'"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></template>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Execution Log #<span x-text="modalData ? modalData.id : ''"></span></h3>
                                <p class="text-xs text-slate-500 mt-0.5" x-text="modalData ? modalData.source_name : ''"></p>
                            </div>
                        </div>
                        <button @click="closeModal()" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="py-5 space-y-3.5">
                        <div class="grid grid-cols-3 gap-3">
                            <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Status</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold"
                                      :class="modalData && modalData.status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                      x-text="modalData ? (modalData.status === 'success' ? '● Success' : '● Failed') : ''"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Duration</span>
                                <span class="font-mono text-sm font-bold text-slate-800" x-text="modalData ? (modalData.duration || '0s') : '0s'"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Executed</span>
                                <span class="text-xs font-medium text-slate-700" x-text="modalData ? (modalData.created_at || modalData.start_time) : ''"></span>
                            </div>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-100">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Target URL</span>
                            <a :href="modalData ? modalData.url : '#'" target="_blank" class="font-mono text-xs text-indigo-600 hover:text-indigo-800 hover:underline break-all" x-text="modalData ? modalData.url : ''"></a>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Start Time</span>
                                <span class="font-mono text-xs text-slate-700" x-text="modalData ? modalData.start_time : '-'"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-100">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">End Time</span>
                                <span class="font-mono text-xs text-slate-700" x-text="modalData ? modalData.end_time : '-'"></span>
                            </div>
                        </div>
                        <template x-if="modalData && modalData.error_message">
                            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200/60">
                                <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5 mb-2">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Error Details
                                </span>
                                <pre class="font-mono text-[11px] text-rose-700 whitespace-pre-wrap break-all bg-white/70 p-3 rounded-lg border border-rose-200/50" x-text="modalData.error_message"></pre>
                            </div>
                        </template>
                        <template x-if="modalData && modalData.status === 'success'">
                            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/60 text-xs text-emerald-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Content fetched &amp; processed through Sastrawi stemmer and TextRank pipeline.</span>
                            </div>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button @click="closeModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
