<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') | Kidzee Preschool Administrative Suite</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fredoka:wght@600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-brand {
            font-family: 'Fredoka', cursive, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    <!-- Left Sidebar -->
    <aside id="sidebar" class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col z-30 fixed inset-y-0 left-0 transition-transform -translate-x-full lg:translate-x-0 lg:static">
        <!-- Sidebar Brand -->
        <div class="h-20 bg-slate-950 px-6 flex items-center justify-between border-b border-slate-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center text-purple-950 font-bold shadow-md">
                    <i class="fa-solid fa-shapes text-lg"></i>
                </div>
                <div>
                    <div class="font-brand font-bold text-xl text-white tracking-tight leading-none">
                        kidzee <span class="text-xs font-sans font-bold px-1.5 py-0.5 rounded bg-amber-400/20 text-amber-300">ADMIN</span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">Campus Operations</p>
                </div>
            </a>
            <button type="button" onclick="document.getElementById('sidebar').classList.add('-translate-x-full')" class="lg:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Sidebar Opening Badge -->
        <div class="p-3 mx-3 my-2 rounded-xl bg-gradient-to-r from-purple-950 to-indigo-950 border border-purple-800/40 text-xs">
            <div class="flex items-center gap-2 text-amber-300 font-bold mb-1">
                <i class="fa-solid fa-sparkles"></i> Opening Nov 20, 2026
            </div>
            <p class="text-[11px] text-slate-300 leading-tight">Admissions & Early Bird campaigns active.</p>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1 text-sm font-semibold">
            <!-- Dashboard Overview -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center text-base {{ request()->routeIs('admin.dashboard') ? 'text-slate-950' : 'text-amber-400' }}"></i>
                <span>Dashboard Overview</span>
            </a>

            <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                Core Operations
            </div>

            <!-- Feature 1: Queries -->
            <a href="{{ route('admin.inquiries.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.inquiries.*') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-comment-dots w-5 text-center text-base {{ request()->routeIs('admin.inquiries.*') ? 'text-slate-950' : 'text-sky-400' }}"></i>
                    <span>1. Inquiries & Leads</span>
                </div>
                @php $newInqCount = \App\Models\Inquiry::where('status', 'new')->count(); @endphp
                @if($newInqCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ request()->routeIs('admin.inquiries.*') ? 'bg-slate-950 text-amber-400' : 'bg-rose-500 text-white' }}">{{ $newInqCount }}</span>
                @endif
            </a>

            <!-- Feature 2: Admissions & Billing -->
            <a href="{{ route('admin.admissions.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.admissions.*') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-file-signature w-5 text-center text-base {{ request()->routeIs('admin.admissions.*') ? 'text-slate-950' : 'text-emerald-400' }}"></i>
                    <span>2. Admissions</span>
                </div>
                @php $pendingAppCount = \App\Models\AdmissionApplication::whereIn('status', ['submitted', 'under_review'])->count(); @endphp
                @if($pendingAppCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ request()->routeIs('admin.admissions.*') ? 'bg-slate-950 text-amber-400' : 'bg-emerald-500 text-white' }}">{{ $pendingAppCount }}</span>
                @endif
            </a>

            <!-- Monthly Fee Generation Engine -->
            <a href="{{ route('admin.billing.monthly') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.billing.monthly') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-calendar-days w-5 text-center text-base {{ request()->routeIs('admin.billing.monthly') ? 'text-slate-950' : 'text-emerald-400' }}"></i>
                <span>Monthly Fee Billing</span>
            </a>

            <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.invoices.*') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-base {{ request()->routeIs('admin.invoices.*') ? 'text-slate-950' : 'text-amber-400' }}"></i>
                <span>Invoices & Receipts</span>
            </a>

            <!-- Feature 3: Subscriptions -->
            <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.subscriptions.*') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-arrows-rotate w-5 text-center text-base {{ request()->routeIs('admin.subscriptions.*') ? 'text-slate-950' : 'text-indigo-400' }}"></i>
                <span>3. Subscriptions</span>
            </a>

            <!-- Feature 4: Student Database -->
            <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                Student Administration
            </div>

            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.students.index') || request()->routeIs('admin.students.show') || request()->routeIs('admin.students.edit') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-children w-5 text-center text-base {{ request()->routeIs('admin.students.*') ? 'text-slate-950' : 'text-pink-400' }}"></i>
                <span>4. Student Database</span>
            </a>

            <a href="{{ route('admin.attendance.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.attendance.*') ? 'bg-amber-400 text-slate-950 font-bold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-calendar-check w-5 text-center text-base {{ request()->routeIs('admin.attendance.*') ? 'text-slate-950' : 'text-teal-400' }}"></i>
                <span>Daily Attendance</span>
            </a>

            <div class="pt-4 border-t border-slate-800/80 mt-4 space-y-1">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition text-xs font-semibold">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-globe"></i> Parent Portal</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </nav>

        <!-- Current User Info -->
        <div class="p-4 bg-slate-950 border-t border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-full bg-purple-800 text-amber-300 font-bold flex items-center justify-center shrink-0">
                    {{ substr(Auth::user()->name ?? 'Admin', 0, 1) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()?->email }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" class="text-slate-400 hover:text-rose-400 p-2 rounded-lg hover:bg-slate-800 transition">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-20 bg-white border-b border-slate-200/80 sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <button type="button" onclick="document.getElementById('sidebar').classList.remove('-translate-x-full')" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-900">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 hidden sm:block">@yield('page_subtitle', 'Concept Kidzee Preschool Management')</p>
                </div>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.invoices.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-sm transition">
                    <i class="fa-solid fa-plus text-amber-500"></i> New Invoice
                </a>

                <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 text-xs font-extrabold shadow-sm transition">
                    <i class="fa-solid fa-user-plus"></i> Enroll Student
                </a>
            </div>
        </header>

        <!-- Flash alerts -->
        @if(session('success'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-sm animate-fade-in">
                <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5 text-xs">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="flex-1 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-sm">
                <div class="w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5 text-xs">
                    <i class="fa-solid fa-exclamation"></i>
                </div>
                <div class="flex-1 text-sm font-semibold">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Main Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>
