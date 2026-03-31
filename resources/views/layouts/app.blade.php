<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Kaizen Tracker')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased flex min-h-screen">

<!-- SideNavBar -->
<aside class="flex flex-col sticky top-0 h-screen w-64 border-r border-slate-200 bg-white font-['Inter'] antialiased text-sm font-medium">
    <div class="px-6 py-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-7 h-7 bg-primary flex items-center justify-center rounded-sm">
                <span class="material-symbols-outlined text-white text-base">precision_manufacturing</span>
            </div>
            <div>
                <h1 class="text-base font-bold tracking-tight text-slate-900">Kaizen Tracker</h1>
                <p class="text-[9px] uppercase tracking-widest text-slate-500">Industrial Precision</p>
            </div>
        </div>
        <nav class="space-y-1">
            <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('dashboard') ? 'text-blue-700 font-bold border-r-2 border-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined text-[20px]">dashboard</span> Dashboard
            </a>

            @if(auth()->user()->isAdmin())
            <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('weekly-plans.create') ? 'text-blue-700 font-bold border-r-2 border-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors' }}" href="{{ route('weekly-plans.create') }}">
                <span class="material-symbols-outlined text-[20px]">calendar_view_week</span> Weekly Plan
            </a>
            <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('weekly-plans.closing') ? 'text-blue-700 font-bold border-r-2 border-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors' }}" href="{{ route('weekly-plans.closing') }}">
                <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span> Closing
            </a>
            @endif

            <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('rankings') ? 'text-blue-700 font-bold border-r-2 border-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors' }}" href="{{ route('rankings') }}">
                <span class="material-symbols-outlined text-[20px]">leaderboard</span> Ranking
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('weekly-reports.*') ? 'text-blue-700 font-bold border-r-2 border-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors' }}" href="{{ route('weekly-reports.index') }}">
                <span class="material-symbols-outlined text-[20px]">view_list</span> Daftar Rencana
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <div class="pt-4 pb-2 border-t border-slate-100 mt-2">
                <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Settings</p>
                <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('admin.users.*') ? 'text-blue-700 font-bold border-r-2 border-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors' }}" href="{{ route('admin.users.index') }}">
                    <span class="material-symbols-outlined text-[20px]">manage_accounts</span> Users & Dept
                </a>
                <a class="flex items-center gap-3 px-3 py-1.5 {{ request()->routeIs('admin.categories.*') ? 'text-blue-700 font-bold border-r-2 border-blue-700 bg-blue-50/50' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors' }}" href="{{ route('admin.categories.index') }}">
                    <span class="material-symbols-outlined text-[20px]">category</span> Categories
                </a>
            </div>
            @endif
        </nav>
        @if(auth()->user()->isAdmin())
        <div class="mt-8">
            <a href="{{ route('weekly-plans.create') }}" class="block w-full bg-primary text-white py-2 rounded-sm font-bold text-[11px] tracking-wide hover:bg-primary-dim transition-all text-center">
                NEW IMPROVEMENT
            </a>
        </div>
        @endif
    </div>
    <div class="mt-auto px-6 py-6 border-t border-slate-100">
        <nav class="space-y-1">
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
            <a class="flex items-center gap-3 px-3 py-1 text-slate-500 hover:text-red-600 transition-colors cursor-pointer" onclick="document.getElementById('logout-form').submit()">
                <span class="material-symbols-outlined text-[18px]">logout</span> Logout
            </a>
        </nav>
        <div class="flex items-center gap-3 mt-4 px-3">
            <img alt="User Profile Avatar" class="w-7 h-7 rounded-full object-cover grayscale" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=005db6&color=fff"/>
            <div class="overflow-hidden">
                <p class="text-[11px] font-bold truncate text-slate-900">{{ auth()->user()->name ?? 'Guest' }}</p>
            </div>
        </div>
    </div>
</aside>

<main class="flex-1 flex flex-col min-w-0">
    <header class="flex justify-between items-center w-full px-8 py-3 sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="flex items-center flex-1 max-w-xl">
            <div class="relative w-full">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input class="w-full bg-transparent border-none focus:ring-0 text-sm py-1 pl-10 pr-4 outline-none" placeholder="Search metrics..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-4 ml-6">
            <button class="p-1.5 text-slate-400 hover:text-primary transition-all">
                <span class="material-symbols-outlined text-xl">notifications</span>
            </button>
            <div class="h-4 w-[1px] bg-slate-200"></div>
            <button class="px-3 py-1 bg-slate-100 text-on-surface font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200">
                {{ auth()->user()->role ?? 'ADMIN' }}
            </button>
        </div>
    </header>

    @yield('content')
</main>

@yield('scripts')
    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#0061e0',
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#0061e0',
        });
    </script>
    @endif
</body>
</html>
