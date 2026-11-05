<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kidzee Preschool & Day Care | Where Kids Love To Learn')</title>
    <meta name="description" content="Welcome to Concept Kidzee — India's premier preschool network offering Pentamind experiential learning, Nuvo, Playgroup, Nursery, Junior KG, Senior KG & Daycare. Grand Opening November 20!">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Quicksand:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome / Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
        }
        h1, h2, h3, .font-heading {
            font-family: 'Fredoka', cursive, sans-serif;
        }
        .kidzee-purple {
            color: #6B1D76;
        }
        .bg-kidzee-purple {
            background-color: #6B1D76;
        }
        .bg-kidzee-yellow {
            background-color: #FFC72C;
        }
        .border-kidzee-yellow {
            border-color: #FFC72C;
        }
    </style>
</head>
<body class="bg-amber-50/30 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-amber-300 selection:text-purple-900">

    <!-- Top Announcement Bar (Opening Nov 20) -->
    <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-purple-800 text-white text-xs sm:text-sm py-2.5 px-4 shadow-sm relative z-50">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-400 text-purple-950 animate-pulse">
                    <i class="fa-solid fa-sparkles mr-1"></i> GRAND OPENING
                </span>
                <span class="font-medium">
                    We are opening Kidzee on <strong class="text-amber-300 underline decoration-wavy">November 20</strong>! Early Bird Admissions Open with Special Welcome Kits.
                </span>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <a href="tel:+919845012345" class="hover:text-amber-300 transition flex items-center gap-1 font-semibold">
                    <i class="fa-solid fa-phone text-amber-400"></i> +91 98450 12345
                </a>
                <span class="text-purple-400">|</span>
                <a href="{{ route('admissions.track') }}" class="hover:text-amber-300 transition flex items-center gap-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Track Admission
                </a>
                <span class="text-purple-400">|</span>
                <a href="{{ route('admin.login') }}" class="text-amber-300 hover:text-white transition flex items-center gap-1 font-semibold bg-white/10 px-2 py-0.5 rounded">
                    <i class="fa-solid fa-lock text-xs"></i> Admin Staff
                </a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-amber-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Kidzee Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-amber-400 flex items-center justify-center shadow-md group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-shapes text-2xl text-purple-900"></i>
                    </div>
                    <div>
                        <div class="flex items-baseline font-heading font-extrabold text-2xl sm:text-3xl tracking-tight">
                            <span class="text-purple-800">k</span><span class="text-amber-500">i</span><span class="text-orange-500">d</span><span class="text-sky-500">z</span><span class="text-emerald-500">e</span><span class="text-rose-500">e</span>
                        </div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-purple-900/70 -mt-1 font-sans">
                            Where Kids Love To Learn
                        </p>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2 font-semibold text-sm">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-slate-700 hover:text-purple-700 hover:bg-purple-50 transition {{ request()->routeIs('home') ? 'text-purple-800 bg-purple-50 font-bold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('programs') }}" class="px-3 py-2 rounded-xl text-slate-700 hover:text-purple-700 hover:bg-purple-50 transition {{ request()->routeIs('programs') ? 'text-purple-800 bg-purple-50 font-bold' : '' }}">
                        Preschool Programs
                    </a>
                    <a href="{{ route('subscriptions') }}" class="px-3 py-2 rounded-xl text-slate-700 hover:text-purple-700 hover:bg-purple-50 transition {{ request()->routeIs('subscriptions') ? 'text-purple-800 bg-purple-50 font-bold' : '' }}">
                        Day Care & Packages
                    </a>
                    <a href="{{ route('admissions.track') }}" class="px-3 py-2 rounded-xl text-slate-700 hover:text-purple-700 hover:bg-purple-50 transition {{ request()->routeIs('admissions.track') ? 'text-purple-800 bg-purple-50 font-bold' : '' }}">
                        Track Status
                    </a>
                    <a href="#inquiry-form-section" class="px-3 py-2 rounded-xl text-slate-700 hover:text-purple-700 hover:bg-purple-50 transition">
                        Campus Visit
                    </a>
                </nav>

                <!-- Actions -->
                <div class="hidden sm:flex items-center gap-3">
                    <a href="#quick-inquiry-modal" onclick="document.getElementById('quick-inquiry-modal').classList.remove('hidden')" class="px-4 py-2.5 rounded-full border-2 border-purple-800 text-purple-900 font-bold text-sm hover:bg-purple-50 transition shadow-sm">
                        <i class="fa-regular fa-comment-dots mr-1"></i> Quick Inquiry
                    </a>
                    <a href="{{ route('admissions.apply') }}" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-purple-950 font-extrabold text-sm shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                        <span>Apply for Admission</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <!-- Mobile menu toggle -->
                <div class="flex md:hidden items-center gap-2">
                    <a href="{{ route('admissions.apply') }}" class="px-3 py-1.5 rounded-full bg-amber-400 text-purple-950 font-bold text-xs">
                        Apply
                    </a>
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="p-2 rounded-lg text-slate-700 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Drawer -->
            <div id="mobile-menu" class="hidden md:hidden py-4 border-t border-slate-100 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg font-semibold text-slate-800 hover:bg-purple-50">Home</a>
                <a href="{{ route('programs') }}" class="block px-3 py-2 rounded-lg font-semibold text-slate-800 hover:bg-purple-50">Preschool Programs</a>
                <a href="{{ route('subscriptions') }}" class="block px-3 py-2 rounded-lg font-semibold text-slate-800 hover:bg-purple-50">Day Care & Subscriptions</a>
                <a href="{{ route('admissions.apply') }}" class="block px-3 py-2 rounded-lg font-semibold text-purple-900 bg-amber-100">Online Admission Form</a>
                <a href="{{ route('admissions.track') }}" class="block px-3 py-2 rounded-lg font-semibold text-slate-800 hover:bg-purple-50">Track Application & Invoices</a>
                <a href="{{ route('admin.login') }}" class="block px-3 py-2 rounded-lg font-semibold text-purple-700 hover:bg-purple-50">Admin Dashboard Login</a>
            </div>
        </div>
    </header>

    <!-- Flash notifications -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-sm animate-fade-in">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-emerald-900">Success!</h4>
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-rose-900">Notice</h4>
                    <p class="text-sm text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Quick Inquiry Modal -->
    <div id="quick-inquiry-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border-4 border-amber-300">
            <button type="button" onclick="document.getElementById('quick-inquiry-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-400 text-purple-900 flex items-center justify-center font-bold text-lg">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <h3 class="font-heading text-xl font-bold text-purple-900">Parent Admission Query</h3>
                    <p class="text-xs text-slate-500">Opening November 20 • We reply within 2 hours</p>
                </div>
            </div>

            <form action="{{ route('inquiries.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Parent's Full Name *</label>
                    <input type="text" name="parent_name" required placeholder="e.g. Radhika Sharma" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Number *</label>
                        <input type="tel" name="phone" required placeholder="+91 98765 43210" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email ID</label>
                        <input type="email" name="email" placeholder="parent@example.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Child's Name *</label>
                        <input type="text" name="child_name" required placeholder="e.g. Aarav" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Child's Age</label>
                        <input type="text" name="child_age" placeholder="e.g. 3 years" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program Interested In *</label>
                        <select name="program_interested" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm bg-white">
                            <option value="Playgroup">Playgroup (2 – 3 Years)</option>
                            <option value="Nursery">Nursery (3 – 4 Years)</option>
                            <option value="Junior KG">Junior KG (4 – 5 Years)</option>
                            <option value="Senior KG">Senior KG (5 – 6 Years)</option>
                            <option value="Nuvo">Nuvo (1.5 – 2 Years)</option>
                            <option value="Daycare">Day Care & Club</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">How did you learn about us? *</label>
                        <select name="referral_source" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm bg-white">
                            <option value="Friend / Family Referral">Friend / Family Referral</option>
                            <option value="Google Search">Google Search</option>
                            <option value="Social Media (Instagram/Facebook)">Social Media (Instagram/Facebook)</option>
                            <option value="Hoarding / Outdoor Banner">Hoarding / Outdoor Banner</option>
                            <option value="Newspaper / Pamphlet">Newspaper / Pamphlet</option>
                            <option value="Word of Mouth">Word of Mouth</option>
                            <option value="Direct Walk-in">Direct Walk-in</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Area / Locality & Address</label>
                    <input type="text" name="address" placeholder="e.g. Bellandur / Green Glen Layout" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Special Question / Visit Request</label>
                    <textarea name="message" rows="2" placeholder="Tell us if you need bus transport, specific timings, or weekend visit..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"></textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-extrabold text-sm shadow-md transition flex items-center justify-center gap-2">
                        <span>Submit Inquiry & Reserve Welcome Kit</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kidzee Footer -->
    <footer class="bg-purple-950 text-white pt-14 pb-8 border-t-8 border-amber-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                <!-- Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center text-purple-950 font-bold text-xl">
                            <i class="fa-solid fa-shapes"></i>
                        </div>
                        <span class="font-heading text-2xl font-bold text-white tracking-tight">
                            kidzee
                        </span>
                    </div>
                    <p class="text-sm text-purple-200/80 leading-relaxed">
                        Concept Kidzee Flagship Preschool & Daycare Center. Pioneering Pentamind early childhood education designed to nurture every child's unique potential.
                    </p>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-900 text-amber-300 text-xs font-bold">
                        <i class="fa-solid fa-calendar-star"></i> Grand Opening: November 20
                    </div>
                </div>

                <!-- Programs -->
                <div>
                    <h4 class="font-heading text-lg font-bold text-amber-400 mb-4">Preschool Programs</h4>
                    <ul class="space-y-2 text-sm text-purple-200">
                        <li><a href="{{ route('programs') }}#nuvo" class="hover:text-amber-300 transition">Nuvo (1.5 – 2 Yrs)</a></li>
                        <li><a href="{{ route('programs') }}#playgroup" class="hover:text-amber-300 transition">Playgroup (2 – 3 Yrs)</a></li>
                        <li><a href="{{ route('programs') }}#nursery" class="hover:text-amber-300 transition">Nursery (3 – 4 Yrs)</a></li>
                        <li><a href="{{ route('programs') }}#junior-kg" class="hover:text-amber-300 transition">Junior KG (4 – 5 Yrs)</a></li>
                        <li><a href="{{ route('programs') }}#senior-kg" class="hover:text-amber-300 transition">Senior KG (5 – 6 Yrs)</a></li>
                        <li><a href="{{ route('subscriptions') }}" class="hover:text-amber-300 transition">Day Care & Activity Club</a></li>
                    </ul>
                </div>

                <!-- Quick Links & Tracking -->
                <div>
                    <h4 class="font-heading text-lg font-bold text-amber-400 mb-4">Admissions & Parents</h4>
                    <ul class="space-y-2 text-sm text-purple-200">
                        <li><a href="{{ route('admissions.apply') }}" class="hover:text-amber-300 transition flex items-center gap-1.5"><i class="fa-solid fa-file-pen text-amber-400 text-xs"></i> Online Admission Form</a></li>
                        <li><a href="{{ route('admissions.track') }}" class="hover:text-amber-300 transition flex items-center gap-1.5"><i class="fa-solid fa-barcode text-amber-400 text-xs"></i> Check Application Status</a></li>
                        <li><a href="{{ route('subscriptions') }}" class="hover:text-amber-300 transition flex items-center gap-1.5"><i class="fa-solid fa-receipt text-amber-400 text-xs"></i> Fee & Subscription Plans</a></li>
                        <li><a href="#inquiry-form-section" class="hover:text-amber-300 transition flex items-center gap-1.5"><i class="fa-solid fa-phone text-amber-400 text-xs"></i> Book Campus Tour</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-amber-300 transition flex items-center gap-1.5"><i class="fa-solid fa-user-shield text-amber-400 text-xs"></i> Admin Dashboard</a></li>
                    </ul>
                </div>

                <!-- Campus Contact -->
                <div>
                    <h4 class="font-heading text-lg font-bold text-amber-400 mb-4">Campus Location</h4>
                    <p class="text-sm text-purple-200 mb-3">
                        <i class="fa-solid fa-location-dot text-amber-400 mr-2"></i>
                        Concept Kidzee Campus, Near Central Park, Green Glen Layout, Bengaluru 560103
                    </p>
                    <p class="text-sm text-purple-200 mb-2">
                        <i class="fa-solid fa-phone text-amber-400 mr-2"></i> +91 98450 12345 / +91 98712 34567
                    </p>
                    <p class="text-sm text-purple-200 mb-4">
                        <i class="fa-solid fa-envelope text-amber-400 mr-2"></i> admissions@conceptkidzee.com
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-9 h-9 rounded-full bg-purple-900 hover:bg-amber-400 hover:text-purple-950 transition flex items-center justify-center text-sm"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-purple-900 hover:bg-amber-400 hover:text-purple-950 transition flex items-center justify-center text-sm"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-purple-900 hover:bg-amber-400 hover:text-purple-950 transition flex items-center justify-center text-sm"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-purple-900 hover:bg-amber-400 hover:text-purple-950 transition flex items-center justify-center text-sm"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-purple-900 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-purple-300 gap-3">
                <p>© {{ date('Y') }} Kidzee Preschool & Day Care • Concept Global Education Network. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <span>Opening Nov 20, 2026</span>
                    <a href="{{ route('admin.login') }}" class="text-amber-400 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[10px]"></i> Admin Portal
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
