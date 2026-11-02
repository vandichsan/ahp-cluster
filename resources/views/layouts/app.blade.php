<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Synthesis Huis SPK')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            950: '#020617',
                            900: '#0F172A',
                            800: '#1E293B',
                        },
                        gold: {
                            400: '#C5A880',
                            500: '#D4AF37',
                            600: '#B5952F',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        #sidebar {
            transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .sidebar-icon {
            transition: margin 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .sidebar-label,
        .sidebar-section-title,
        .sidebar-logout-label {
            transition: opacity 0.2s ease, max-width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), margin 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            max-width: 200px;
            opacity: 1;
            white-space: nowrap;
            overflow: hidden;
        }

        #sidebar.collapsed .sidebar-label,
        #sidebar.collapsed .sidebar-section-title,
        #sidebar.collapsed .sidebar-logout-label {
            opacity: 0;
            max-width: 0;
            margin: 0;
            padding: 0;
        }

        #sidebar.collapsed .sidebar-icon {
            margin-right: 0;
        }

        #sidebar:not(.collapsed) .sidebar-icon {
            margin-right: 0.75rem;
        }

        nav,
        .sidebar-logout-btn {
            transition: padding 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        #sidebar nav::-webkit-scrollbar {
            display: none;
        }

        #sidebar nav {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        #sidebar.collapsed nav {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            overflow: visible !important;
        }

        #sidebar.collapsed nav a,
        #sidebar.collapsed .sidebar-logout-btn {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar-logo-full,
        .sidebar-logo-mini {
            transition: opacity 0.3s ease, transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        #sidebar.collapsed .sidebar-logo-full {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }

        #sidebar.collapsed .sidebar-logo-mini {
            opacity: 1;
            transform: translateX(0);
        }

        #sidebar:not(.collapsed) .sidebar-logo-full {
            opacity: 1;
            transform: translateX(0);
        }

        #sidebar:not(.collapsed) .sidebar-logo-mini {
            opacity: 0;
            transform: translateX(10px);
            pointer-events: none;
        }

        #sidebar.collapsed nav a {
            position: relative;
        }

        #sidebar.collapsed nav a .sidebar-tooltip {
            display: block;
        }

        #sidebar:not(.collapsed) nav a .sidebar-tooltip {
            display: none;
        }

        .sidebar-tooltip {
            display: none;
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: #1E293B;
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s ease;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 100;
        }

        .sidebar-tooltip::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1E293B;
        }

        #sidebar.collapsed nav a:hover .sidebar-tooltip {
            opacity: 1;
        }

        #sidebar-toggle-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar.collapsed #sidebar-toggle-icon {
            transform: rotate(180deg);
        }

        #sidebar-overlay {
            transition: opacity 0.3s ease;
        }
    </style>
</head>

<body class="bg-slate-950 font-sans text-gray-200 antialiased h-screen flex overflow-hidden">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden md:hidden"
        onclick="toggleMobileSidebar()"></div>

    <aside id="sidebar"
        class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col z-40 shadow-xl
                               fixed md:relative h-full -translate-x-full md:translate-x-0 transition-transform duration-300">

        <div class="h-16 flex items-center border-b border-slate-800 px-4 relative overflow-hidden">
            <div class="flex-1 h-full relative flex items-center">
                <a href="{{ route('dashboard') }}"
                    class="sidebar-logo-full absolute left-0 px-2 flex items-center whitespace-nowrap">
                    <img src="{{ asset('images/logo.png') }}" class="w-8 h-8 mr-2.5 object-contain" alt="Logo">
                    <span class="text-xl font-bold text-white tracking-wide">Synthesis<span
                            class="text-gold-500">Huis</span></span>
                </a>
                <a href="{{ route('dashboard') }}"
                    class="sidebar-logo-mini absolute left-0 w-full flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" class="w-8 h-8 object-contain" alt="Logo">
                </a>
            </div>
            <button id="sidebar-toggle" onclick="toggleSidebar()"
                class="hidden md:flex absolute right-4 items-center justify-center w-7 h-7 rounded-lg text-gray-500 hover:text-gold-400 hover:bg-slate-800 transition-all flex-shrink-0 z-10"
                title="Minimize sidebar">
                <svg id="sidebar-toggle-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <p class="sidebar-section-title px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">
                Menu Utama</p>

            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                <span class="font-medium text-sm sidebar-label">Dashboard</span>
                <span class="sidebar-tooltip">Dashboard</span>
            </a>

            <a href="{{ route('data.alternatives') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('data.alternatives') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <span class="font-medium text-sm sidebar-label">Data Alternatif</span>
                <span class="sidebar-tooltip">Data Alternatif</span>
            </a>

            <a href="{{ route('data.criteria') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('data.criteria') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span class="font-medium text-sm sidebar-label">Kriteria & Sub</span>
                <span class="sidebar-tooltip">Kriteria & Sub</span>
            </a>

            <p
                class="sidebar-section-title px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-6 mb-2">
                Analisis AHP</p>

            <a href="{{ route('ahp.matrix') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('ahp.matrix') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="font-medium text-sm sidebar-label">Kuesioner AHP</span>
                <span class="sidebar-tooltip">Kuesioner AHP</span>
            </a>

            <a href="{{ route('sub_criteria.list') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('sub_criteria.*') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <span class="font-medium text-sm sidebar-label">Sub-Kriteria AHP</span>
                <span class="sidebar-tooltip">Sub-Kriteria AHP</span>
            </a>

            <a href="{{ route('alt_matrix.list') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('alt_matrix.*') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span class="font-medium text-sm sidebar-label">Matriks Alternatif</span>
                <span class="sidebar-tooltip">Matriks Alternatif</span>
            </a>
            <p
                class="sidebar-section-title px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-6 mb-2">
                Laporan</p>
            <a href="{{ route('laporan.alternatif') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('laporan.alternatif') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="font-medium text-sm sidebar-label">Lap. Digital Marketing</span>
                <span class="sidebar-tooltip">Lap. Digital Marketing</span>
            </a>
            <a href="{{ route('laporan.kriteria') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('laporan.kriteria') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="font-medium text-sm sidebar-label">Lap. Kriteria</span>
                <span class="sidebar-tooltip">Lap. Kriteria</span>
            </a>

            <a href="{{ route('laporan.sub_kriteria') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('laporan.sub_kriteria') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                </svg>
                <span class="font-medium text-sm sidebar-label">Lap. Sub-Kriteria</span>
                <span class="sidebar-tooltip">Lap. Sub-Kriteria</span>
            </a>

            <a href="{{ route('laporan.hasil') }}"
                class="flex items-center px-4 py-2.5 {{ request()->routeIs('laporan.hasil') ? 'bg-slate-800/50 text-gold-400 border-l-2 border-gold-500' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-all">
                <svg class="w-5 h-5 sidebar-icon mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                <span class="font-medium text-sm sidebar-label">Lap. Hasil</span>
                <span class="sidebar-tooltip">Lap. Hasil</span>
            </a>
        </nav>


        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="sidebar-logout-btn flex items-center w-full px-4 py-2.5 text-gray-400 hover:bg-red-500/10 hover:text-red-400 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="font-medium text-sm sidebar-logout-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 bg-slate-950 relative">
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-gold-500 rounded-full mix-blend-multiply filter blur-[150px] opacity-10 pointer-events-none">
        </div>

        <header
            class="h-16 glass-panel border-b border-slate-800/50 flex items-center justify-between px-6 z-10 sticky top-0">
            <div class="flex items-center">
                <button onclick="toggleMobileSidebar()" class="md:hidden text-gray-400 hover:text-white mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-xl font-semibold text-white">@yield('header')</h2>
            </div>

            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-gold-500/20 border border-gold-500/50 flex items-center justify-center text-gold-400 font-bold text-sm">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span
                        class="text-sm font-medium text-gray-300 hidden sm:block">{{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-8 z-10">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-500/10 border border-green-500/50 flex items-start space-x-3">
                    <svg class="w-5 h-5 text-green-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-green-300 font-medium">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/50 flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-red-300 font-medium">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const SIDEBAR_KEY = 'sidebar_collapsed';

        if (localStorage.getItem(SIDEBAR_KEY) === 'true') {
            sidebar.classList.add('collapsed');
            sidebar.style.width = '4.5rem';
        }

        function toggleSidebar() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            sidebar.style.width = isCollapsed ? '4.5rem' : '16rem';
            localStorage.setItem(SIDEBAR_KEY, isCollapsed);
        }

        function toggleMobileSidebar() {
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>