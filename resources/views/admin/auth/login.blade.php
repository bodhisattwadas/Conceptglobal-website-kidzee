<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator Login | Kidzee Preschool Administrative Suite</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fredoka:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-brand { font-family: 'Fredoka', cursive, sans-serif; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 selection:bg-amber-300 selection:text-purple-900">

    <div class="max-w-md w-full">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-400 text-purple-950 font-bold text-3xl shadow-xl mb-3">
                <i class="fa-solid fa-shapes"></i>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/20 text-amber-300 text-[11px] font-bold uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-shield-halved text-[10px]"></i> Administrator Portal
                </span>
            </div>
            <h1 class="font-brand text-3xl font-extrabold text-white tracking-tight">
                kidzee <span class="text-amber-400">admin</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Authorized Operations & Student Management Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-950 rounded-3xl p-8 border border-slate-800 shadow-2xl">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400 mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Admin Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="admin@conceptkidzee.com" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-400 text-white text-sm placeholder:text-slate-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-400 text-white text-sm placeholder:text-slate-600">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-900 border-slate-800 text-amber-400 focus:ring-0">
                        <span>Remember session</span>
                    </label>
                    <span class="text-[11px] text-slate-500 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[9px] text-slate-400"></i> Secure Access
                    </span>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-bold text-sm shadow-lg transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        <span>Sign In as Administrator</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-800/80 text-center space-y-3">
                <p class="text-[11px] text-slate-500">
                    Access restricted to authorized Kidzee administrators only.
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-amber-300 transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Back to Parent Facing Portal</span>
                </a>
            </div>
        </div>
    </div>

</body>
</html>
