@extends('layouts.app')

@section('title', 'Kidzee Preschool & Daycare | Opening November 20 | Where Kids Love To Learn')

@section('content')

    <!-- Hero Section with Opening Nov 20 Announcement -->
    <section class="relative overflow-hidden kidzee-gradient-hero pt-8 pb-16 lg:py-20 border-b border-amber-200/60">
        <!-- Floating decorative blobs -->
        <div class="absolute -top-12 -left-12 w-64 h-64 bg-amber-300/40 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-1/2 -right-12 w-72 h-72 bg-purple-300/30 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Column -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <!-- Grand Opening Banner Pill -->
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/90 border border-amber-300 shadow-sm">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <span class="text-xs sm:text-sm font-extrabold text-purple-950 uppercase tracking-wider">
                            🎉 Grand Opening On <span class="text-purple-700 underline font-black">November 20</span>
                        </span>
                        <span class="hidden sm:inline-block bg-amber-400 text-purple-950 text-[11px] font-black px-2 py-0.5 rounded-full">
                            Admissions Open 2026-27
                        </span>
                    </div>

                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.1]">
                        Every Child is <span class="text-purple-800 underline decoration-amber-400 decoration-wavy">Unique</span>, <br>
                        Learning Begins with <span class="text-amber-500">Joy!</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 max-w-2xl font-medium leading-relaxed">
                        Welcome to <strong>Concept Kidzee</strong> — Asia's most trusted preschool network. Powered by our proprietary <strong>Pentamind</strong> learning methodology, we provide joyful, holistic early education and safe daycare where children thrive.
                    </p>

                    <!-- Opening Countdown Banner -->
                    <div class="p-4 sm:p-5 rounded-3xl bg-white/95 border-2 border-amber-300 shadow-lg max-w-xl">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <span class="text-xs uppercase font-extrabold tracking-wider text-purple-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-bell text-amber-500"></i> Inauguration Countdown
                                </span>
                                <p class="text-sm font-bold text-slate-800">Doors open November 20</p>
                            </div>
                            <!-- Countdown Counter -->
                            <div class="flex items-center gap-2 text-center" id="countdown-timer">
                                <div class="bg-purple-900 text-white rounded-xl px-2.5 py-1.5 min-w-[50px]">
                                    <span class="block font-heading text-lg font-bold text-amber-300" id="cd-days">17</span>
                                    <span class="text-[9px] uppercase tracking-wider text-purple-200">Days</span>
                                </div>
                                <div class="text-purple-900 font-bold">:</div>
                                <div class="bg-purple-900 text-white rounded-xl px-2.5 py-1.5 min-w-[50px]">
                                    <span class="block font-heading text-lg font-bold text-amber-300" id="cd-hours">09</span>
                                    <span class="text-[9px] uppercase tracking-wider text-purple-200">Hours</span>
                                </div>
                                <div class="text-purple-900 font-bold">:</div>
                                <div class="bg-purple-900 text-white rounded-xl px-2.5 py-1.5 min-w-[50px]">
                                    <span class="block font-heading text-lg font-bold text-amber-300" id="cd-mins">45</span>
                                    <span class="text-[9px] uppercase tracking-wider text-purple-200">Mins</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-purple-900 font-semibold">
                            <span>✨ Early Bird Benefit: <strong>₹1,500 Welcome Kit Waiver</strong></span>
                            <span class="text-amber-700 font-bold">Limited to First 50 Seats</span>
                        </div>
                    </div>

                    <!-- Call To Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start pt-2">
                        <a href="{{ route('admissions.apply') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-purple-950 font-heading font-black text-lg shadow-xl hover:shadow-2xl transition transform hover:-translate-y-0.5 flex items-center justify-center gap-3">
                            <span>Apply for Admission</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="#inquiry-form-section" class="w-full sm:w-auto px-7 py-4 rounded-2xl bg-white hover:bg-slate-50 text-purple-900 font-bold text-base border-2 border-purple-800 shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-calendar-check text-amber-500"></i>
                            <span>Schedule Campus Visit</span>
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-amber-200 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <h4 class="font-heading font-bold text-xl text-purple-950">1 : 8</h4>
                            <p class="text-xs text-slate-500 font-semibold">Teacher to Child Ratio</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <h4 class="font-heading font-bold text-xl text-purple-950">100%</h4>
                            <p class="text-xs text-slate-500 font-semibold">CCTV & Female Staff</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <h4 class="font-heading font-bold text-xl text-purple-950">5 Pillars</h4>
                            <p class="text-xs text-slate-500 font-semibold">Pentamind Curriculum</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Visual Brand Card -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md bg-white rounded-3xl p-6 shadow-2xl border-4 border-amber-300">
                        <div class="absolute -top-4 -right-4 bg-purple-900 text-amber-300 font-heading font-bold text-xs uppercase px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
                            <i class="fa-solid fa-star text-amber-400"></i> Opening Nov 20
                        </div>

                        <!-- Brand Header in Card -->
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-12 h-12 rounded-2xl bg-amber-400 flex items-center justify-center text-purple-950 font-bold text-2xl shadow-inner">
                                <i class="fa-solid fa-child-reaching"></i>
                            </div>
                            <div>
                                <h3 class="font-heading text-xl font-bold text-purple-950">Why Choose Kidzee?</h3>
                                <p class="text-xs text-slate-500">Asia's Most Celebrated Preschool</p>
                            </div>
                        </div>

                        <!-- Feature Checklist -->
                        <div class="space-y-4 my-5">
                            <div class="flex items-start gap-3 p-3 rounded-2xl bg-purple-50/70 border border-purple-100">
                                <div class="w-8 h-8 rounded-xl bg-purple-800 text-amber-300 flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-brain"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-purple-950">Proprietary Pentamind Model</h4>
                                    <p class="text-xs text-slate-600">Scientifically crafted to map multiple intelligences through multisensory stimulation.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 rounded-2xl bg-amber-50/70 border border-amber-200">
                                <div class="w-8 h-8 rounded-xl bg-amber-400 text-purple-950 flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-shield-heart"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-purple-950">Unhindered Child Safety</h4>
                                    <p class="text-xs text-slate-600">Live smartphone CCTV feed for parents, child-safe padded corners & GPS buses.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 text-sm">
                                    <i class="fa-solid fa-apple-whole"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-purple-950">Nutritious Fresh Meals & Daycare</h4>
                                    <p class="text-xs text-slate-600">Full & half day care packages with hygienic kitchen, nap pods and hobby clubs.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Track status quick link -->
                        <div class="p-3.5 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-800">Already applied?</span>
                                <p class="text-[11px] text-slate-500">Track admission & fee receipts</p>
                            </div>
                            <a href="{{ route('admissions.track') }}" class="px-3 py-1.5 rounded-xl bg-purple-900 text-amber-300 font-bold hover:bg-purple-800 transition">
                                Track Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- The 5 Pillars of Pentamind Curriculum -->
    <section class="py-16 bg-white border-b border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-900 text-xs font-bold uppercase tracking-wider mb-2">
                    Kidzee Pedagogical Excellence
                </span>
                <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
                    The <span class="text-purple-800">Pentamind</span> 5-Pillar Learning Model
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2">
                    Every activity at Kidzee is tailored around 5 developmental vertices to nurture intellectual, physical, and emotional readiness.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <!-- Pillar 1 -->
                <div class="p-6 rounded-3xl bg-amber-50/60 border-2 border-amber-200 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-400 text-purple-950 flex items-center justify-center text-2xl font-bold mb-4">
                        <i class="fa-solid fa-shapes"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-purple-950 mb-1">Cognitive</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Logic, problem-solving, math concepts, memory patterns and spatial curiosity.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="p-6 rounded-3xl bg-purple-50/60 border-2 border-purple-200 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-800 text-amber-300 flex items-center justify-center text-2xl font-bold mb-4">
                        <i class="fa-solid fa-person-running"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-purple-950 mb-1">Physical</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Gross motor agility, fine pincer grip, rhythmic dance, yoga and sensorimotor balance.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="p-6 rounded-3xl bg-sky-50/60 border-2 border-sky-200 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl font-bold mb-4">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-purple-950 mb-1">Emotional</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Self-regulation, empathy, emotional identification, resilience and mindfulness.
                    </p>
                </div>

                <!-- Pillar 4 -->
                <div class="p-6 rounded-3xl bg-emerald-50/60 border-2 border-emerald-200 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold mb-4">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-purple-950 mb-1">Social</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Sharing, teamwork, polite communication, leadership in group play and cultural respect.
                    </p>
                </div>

                <!-- Pillar 5 -->
                <div class="p-6 rounded-3xl bg-rose-50/60 border-2 border-rose-200 text-center hover:shadow-lg transition">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-500 text-white flex items-center justify-center text-2xl font-bold mb-4">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-purple-950 mb-1">Creative</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Expressive art, puppetry, musical rhythm, imaginative role play and storytelling.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Preschool Programs Grid -->
    <section class="py-16 bg-slate-50/80" id="programs-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-amber-200 text-purple-950 text-xs font-bold uppercase tracking-wider mb-2">
                        Preschool & Daycare Offerings
                    </span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
                        Tailored for Every Growing Milestone
                    </h2>
                    <p class="text-slate-600 text-sm sm:text-base mt-1">
                        Age-appropriate developmental tracks from 18 months onwards.
                    </p>
                </div>
                <a href="{{ route('admissions.apply') }}" class="inline-flex items-center gap-2 text-purple-900 font-bold hover:text-purple-700 transition">
                    <span>View Admission Criteria</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($programs as $prog)
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm hover:shadow-xl transition group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $prog['badge_color'] }}">
                                    {{ $prog['age'] }}
                                </span>
                                <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> {{ $prog['timing'] }}
                                </span>
                            </div>

                            <h3 class="font-heading text-2xl font-bold text-slate-900 group-hover:text-purple-800 transition">
                                {{ $prog['name'] }}
                            </h3>
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wide mb-3">
                                {{ $prog['tagline'] }}
                            </p>
                            <p class="text-sm text-slate-600 leading-relaxed mb-5">
                                {{ $prog['description'] }}
                            </p>

                            <!-- Highlights -->
                            <div class="space-y-2 mb-6">
                                @foreach($prog['highlights'] as $highlight)
                                    <div class="flex items-center gap-2 text-xs text-slate-700 font-medium">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                        <span>{{ $highlight }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('admissions.apply') }}?program={{ urlencode($prog['name']) }}" class="text-xs font-extrabold text-purple-900 hover:text-amber-600 flex items-center gap-1.5 transition">
                                <span>Apply for {{ $prog['name'] }}</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                            <button type="button" onclick="document.getElementById('quick-inquiry-modal').classList.remove('hidden')" class="px-3 py-1.5 rounded-xl bg-purple-50 text-purple-800 text-xs font-bold hover:bg-purple-100 transition">
                                Inquire
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Subscriptions & Daycare Explorer (Feature 3) -->
    <section class="py-16 bg-white border-y border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
                    Transparent Recurring Fees
                </span>
                <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
                    Day Care & Subscription Packages
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2">
                    Flexible monthly and term plans designed for modern parents. Zero hidden charges, live CCTV tracking, and hygienic meal add-ons.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans->take(3) as $plan)
                    <div class="rounded-3xl p-6 sm:p-8 border-2 {{ $loop->first ? 'border-amber-400 bg-amber-50/30 shadow-lg relative' : 'border-slate-200 bg-white shadow-sm' }} flex flex-col justify-between">
                        @if($loop->first)
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-purple-900 text-amber-300 font-bold text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full shadow">
                                Most Popular For Working Parents
                            </div>
                        @endif

                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $plan->category === 'daycare' ? 'bg-orange-100 text-orange-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucfirst($plan->category) }}
                                </span>
                                <span class="text-xs text-slate-500 font-bold uppercase">
                                    {{ ucfirst($plan->billing_cycle) }}
                                </span>
                            </div>

                            <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">{{ $plan->name }}</h3>
                            <p class="text-xs text-slate-600 leading-relaxed mb-6">{{ $plan->description }}</p>

                            <div class="mb-6 flex items-baseline gap-1">
                                <span class="text-3xl font-heading font-black text-purple-950">₹{{ number_format($plan->price) }}</span>
                                <span class="text-xs text-slate-500 font-bold">/ {{ $plan->billing_cycle }}</span>
                            </div>

                            <!-- Features List -->
                            <div class="space-y-3 mb-6">
                                @if(!empty($plan->features))
                                    @foreach($plan->features as $feat)
                                        <div class="flex items-start gap-2 text-xs text-slate-700">
                                            <i class="fa-solid fa-check text-emerald-500 mt-0.5"></i>
                                            <span>{{ $feat }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('admissions.apply') }}" class="w-full py-3 rounded-xl {{ $loop->first ? 'bg-purple-900 hover:bg-purple-800 text-amber-300' : 'bg-slate-900 hover:bg-slate-800 text-white' }} font-bold text-sm flex items-center justify-center gap-2 transition">
                                <span>Choose Package</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('subscriptions') }}" class="inline-flex items-center gap-2 text-purple-900 font-extrabold text-sm hover:underline">
                    <span>Explore All Add-On Plans (Transport, Organic Meals, STEM Club)</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Admission Application & Lead Query Form Section (Features 1 & 2) -->
    <section class="py-16 bg-gradient-to-b from-amber-50 to-purple-50/40" id="inquiry-form-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left: Why Apply Now -->
                <div class="lg:col-span-5 space-y-6">
                    <span class="inline-block px-3 py-1 rounded-full bg-amber-300 text-purple-950 text-xs font-bold uppercase tracking-wider">
                        Admissions Open for November 20 Batch
                    </span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-black text-slate-900 leading-tight">
                        Reserve Your Child's Seat Before Opening Day!
                    </h2>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                        Take the first step towards an inspiring childhood. Submit an inquiry or book a campus walk-through to experience our classrooms, sanitized play zones, and meet our early childhood educators.
                    </p>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Campus Walk-Throughs</h4>
                                <p class="text-xs text-slate-500">Monday to Saturday: 9:00 AM – 5:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-400 text-purple-950 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Immediate Application Confirmation</h4>
                                <p class="text-xs text-slate-500">Instant application tracking code & digital fee invoice receipt</p>
                            </div>
                        </div>
                    </div>

                    <!-- Track Admission Box -->
                    <div class="mt-8 p-5 rounded-3xl bg-white border border-purple-200 shadow-md">
                        <h4 class="font-heading text-base font-bold text-purple-950 mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass text-amber-500"></i> Track Your Admission Form
                        </h4>
                        <p class="text-xs text-slate-500 mb-3">Enter Application Number or Parent Phone Number:</p>
                        <form action="{{ route('admissions.track') }}" method="GET" class="flex gap-2">
                            <input type="text" name="query" placeholder="e.g. KDZ-APP-2026-101 or phone" required class="flex-1 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-600">
                            <button type="submit" class="px-4 py-2 bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs rounded-xl transition">
                                Track
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Interactive Lead / Query Form -->
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border-4 border-amber-300 relative">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                            <div>
                                <h3 class="font-heading text-2xl font-bold text-purple-950">Quick Admission Inquiry</h3>
                                <p class="text-xs text-slate-500">Our Counselor Pooja will call you to confirm your campus visit</p>
                            </div>
                            <span class="text-xs font-extrabold bg-amber-100 text-amber-800 px-3 py-1 rounded-full">
                                Nov 20 Batch
                            </span>
                        </div>

                        <form action="{{ route('inquiries.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Parent's Name *</label>
                                    <input type="text" name="parent_name" required placeholder="e.g. Rajesh Kumar" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone *</label>
                                    <input type="tel" name="phone" required placeholder="+91 98765 43210" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                                    <input type="email" name="email" placeholder="rajesh@example.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Child's Name *</label>
                                    <input type="text" name="child_name" required placeholder="Child's full name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Child's Age</label>
                                    <input type="text" name="child_age" placeholder="e.g. 2.5 yrs" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Gender</label>
                                    <select name="child_gender" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm bg-white">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Boy</option>
                                        <option value="Female">Girl</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth</label>
                                    <input type="date" name="child_dob" max="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program *</label>
                                    <select name="program_interested" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm bg-white">
                                        <option value="Playgroup">Playgroup (2-3 Yrs)</option>
                                        <option value="Nursery">Nursery (3-4 Yrs)</option>
                                        <option value="Junior KG">Junior KG (4-5 Yrs)</option>
                                        <option value="Senior KG">Senior KG (5-6 Yrs)</option>
                                        <option value="Nuvo">Nuvo (1.5-2 Yrs)</option>
                                        <option value="Daycare">Day Care & Club</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Preferred Campus Visit Date</label>
                                    <input type="date" name="preferred_visit_date" min="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                                </div>
                            </div>

                            <!-- Source & Reference Details -->
                            <div class="bg-amber-50/60 p-3.5 rounded-2xl border border-amber-200 space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-purple-950 uppercase tracking-wider mb-1">
                                            How Did You Learn About Us? *
                                        </label>
                                        <select name="referral_source" required class="w-full px-3 py-2 rounded-xl border border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-white">
                                            <option value="">-- Please Select --</option>
                                            <option value="Word of Mouth">Word of Mouth / Friend / Relative</option>
                                            <option value="Google Search">Google Search</option>
                                            <option value="Social Media (FB / Instagram)">Social Media (FB / Instagram)</option>
                                            <option value="Passed By / Hoarding / Banner">Passed By / Hoarding / Banner</option>
                                            <option value="Pamphlet / Newspaper Insert">Pamphlet / Newspaper Insert</option>
                                            <option value="Kidzee Parent Referral">Kidzee Parent Referral</option>
                                            <option value="School Exhibition / Event">School Exhibition / Event</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-purple-950 uppercase tracking-wider mb-1">
                                            Source / Referred By Details
                                        </label>
                                        <input type="text" name="referral_details" placeholder="e.g. Referred by Mrs. Sharma, or Instagram ad" class="w-full px-3 py-2 rounded-xl border border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm bg-white">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Residential Address / Locality</label>
                                        <input type="text" name="address" placeholder="e.g. Flat 302, Green Valley Apartments" class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pin Code</label>
                                        <input type="text" name="pincode" placeholder="400001" class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs bg-white">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Questions / Specific Requests</label>
                                <textarea name="message" rows="3" placeholder="Tell us if you need bus transport details, day care timings, or special requirements..." class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm"></textarea>
                            </div>

                            <div class="pt-2 flex flex-col sm:flex-row items-center gap-4">
                                <button type="submit" class="w-full sm:flex-1 py-3.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-heading font-black text-base shadow-md transition flex items-center justify-center gap-2">
                                    <span>Send Inquiry & Schedule Visit</span>
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                </button>
                                <a href="{{ route('admissions.apply') }}" class="w-full sm:w-auto px-5 py-3.5 rounded-xl border-2 border-purple-800 text-purple-950 font-bold text-sm hover:bg-purple-50 transition text-center">
                                    Full Application Form
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Life at Kidzee Gallery -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold uppercase tracking-wider mb-2">
                    Campus Experience
                </span>
                <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
                    A Cheerful World Crafted for Little Learners
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2">
                    Every square foot of our campus is designed with child ergonomic standards, anti-skid flooring, and vibrant spaces.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="group relative rounded-3xl overflow-hidden shadow-md aspect-square bg-amber-100 flex flex-col justify-end p-5">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="relative z-10 text-white">
                        <span class="text-xs text-amber-300 font-bold uppercase">Learning Zone</span>
                        <h4 class="font-heading text-lg font-bold">Pentamind Lab</h4>
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-md aspect-square bg-purple-100 flex flex-col justify-end p-5">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="relative z-10 text-white">
                        <span class="text-xs text-amber-300 font-bold uppercase">Sensory</span>
                        <h4 class="font-heading text-lg font-bold">Tactile Soft Play</h4>
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-md aspect-square bg-sky-100 flex flex-col justify-end p-5">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="relative z-10 text-white">
                        <span class="text-xs text-amber-300 font-bold uppercase">Creative Hub</span>
                        <h4 class="font-heading text-lg font-bold">Art & Music Studio</h4>
                    </div>
                </div>

                <div class="group relative rounded-3xl overflow-hidden shadow-md aspect-square bg-emerald-100 flex flex-col justify-end p-5">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="relative z-10 text-white">
                        <span class="text-xs text-amber-300 font-bold uppercase">Rest & Care</span>
                        <h4 class="font-heading text-lg font-bold">Sanitized Nap Pods</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Countdown Javascript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const targetDate = new Date('November 20, 2026 09:00:00').getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance > 0) {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

                    const elDays = document.getElementById('cd-days');
                    const elHours = document.getElementById('cd-hours');
                    const elMins = document.getElementById('cd-mins');

                    if (elDays) elDays.innerText = String(days).padStart(2, '0');
                    if (elHours) elHours.innerText = String(hours).padStart(2, '0');
                    if (elMins) elMins.innerText = String(minutes).padStart(2, '0');
                }
            }

            updateCountdown();
            setInterval(updateCountdown, 60000);
        });
    </script>
@endsection
