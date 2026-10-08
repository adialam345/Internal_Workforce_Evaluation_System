<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Sistem Evaluasi Tenaga Kerja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-900 bg-slate-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex flex-col lg:flex-row">
        
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             @keydown.escape.window="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden" 
             style="display: none;"></div>

        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 flex flex-col transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 shrink-0 no-print">
            
            <!-- Brand / Logo Header -->
            <div class="h-16 flex items-center justify-between px-5 bg-slate-950 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-md bg-blue-600 flex items-center justify-center font-bold text-white text-sm tracking-wider">
                        WE
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-white tracking-tight leading-none">WORKEVAL</h1>
                        <p class="text-[11px] text-slate-400 mt-0.5">Sistem Evaluasi Internal</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-md" aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @if(auth()->user()->isAdmin())
                    <!-- Admin Menu Group -->
                    <div class="px-3 pt-2 pb-1 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        Menu Utama
                    </div>

                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>

                    <div class="px-3 pt-4 pb-1 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        Master & Evaluasi
                    </div>

                    <a href="{{ route('admin.employees.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.employees.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Tenaga Kerja
                    </a>

                    <a href="{{ route('admin.evaluators.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.evaluators.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Akun Evaluator
                    </a>

                    <a href="{{ route('admin.evaluations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.evaluations.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Hasil Evaluasi
                    </a>

                    <a href="{{ route('admin.questions.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.questions.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Kriteria & Pertanyaan
                    </a>

                    <div class="px-3 pt-4 pb-1 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        Data & Laporan
                    </div>

                    <a href="{{ route('admin.import.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.import.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Import Excel
                    </a>

                    <a href="{{ route('admin.export.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.export.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export Laporan
                    </a>

                    <a href="{{ route('admin.audit_logs.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.audit_logs.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Audit Log
                    </a>
                @else
                    <!-- Evaluator Menu Group -->
                    <div class="px-3 pt-2 pb-1 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        Menu Evaluator
                    </div>

                    <a href="{{ route('evaluator.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('evaluator.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('evaluator.employees.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('evaluator.employees.*') || request()->routeIs('evaluator.evaluations.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Anggota Saya
                    </a>
                @endif
            </nav>

            <!-- User Info & Logout Footer -->
            <div class="p-3 bg-slate-950 border-t border-slate-800">
                <div class="flex items-center justify-between">
                    <div class="truncate pr-2">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ auth()->user()->isAdmin() ? 'bg-blue-900/60 text-blue-300 border border-blue-700' : 'bg-emerald-900/60 text-emerald-300 border border-emerald-700' }}">
                            {{ strtoupper(auth()->user()->role) }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" 
                                title="Keluar dari sistem" 
                                class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-md transition-colors" 
                                aria-label="Keluar">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Workspace Container -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header Navbar -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 shrink-0 no-print">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-md" aria-label="Buka Menu">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 leading-tight">@yield('page_title', 'Halaman')</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">@yield('page_subtitle', 'Sistem Evaluasi Tenaga Kerja Internal')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-medium text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-500">{{ auth()->user()->department ?? 'Kantor Pusat' }}</p>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="mb-5 p-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start gap-3" role="alert">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 p-4 rounded-md bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3" role="alert">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-5 p-4 rounded-md bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-start gap-3" role="alert">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>{{ session('warning') }}</div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-5 p-4 rounded-md bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-start gap-3" role="alert">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>{{ session('info') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-md bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <p class="font-semibold mb-1">Terdapat beberapa kesalahan pengisian form:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
