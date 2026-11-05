@extends('layouts.app')

@section('title', 'Preschool Programs | Kidzee Pentamind Curriculum')

@section('content')
<div class="py-12 bg-amber-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-900 text-xs font-bold uppercase tracking-wider mb-2">
                Pentamind Academic Architecture
            </span>
            <h1 class="font-heading text-4xl sm:text-5xl font-black text-slate-900">
                Early Childhood Programs at <span class="text-purple-800">Kidzee</span>
            </h1>
            <p class="text-slate-600 text-sm sm:text-base mt-2">
                Our scientifically calibrated programs nurture independence, linguistic mastery, spatial reasoning, and social harmony during the golden window of early brain development.
            </p>
        </div>

        <!-- Programs List -->
        <div class="space-y-12">
            <!-- 1. Nuvo -->
            <div id="nuvo" class="bg-white rounded-3xl p-8 border-2 border-amber-200 shadow-md grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-900 border border-amber-300">
                            Age: 1.5 to 2 Years
                        </span>
                        <span class="text-xs text-slate-500 font-semibold">Timing: 9:00 AM – 11:30 AM</span>
                    </div>
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900">Nuvo Program</h2>
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">The Wonder of First Steps & Sensory Awakening</p>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Nuvo is designed for toddlers taking their first steps outside the comfort of home. Through sensory touch basins, kinetic sand, soft climbing gyms, and melodious nursery rhymes, toddlers enhance hand-eye coordination, balance, and early verbal expression in a 1:5 caregiver ratio.
                    </p>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-amber-500"></i> Tactile grasping & baby gym
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-amber-500"></i> Daily diaper & feeding log
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-amber-500"></i> Gentle separation anxiety easing
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-amber-500"></i> Classical music & lullaby corners
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-4 bg-amber-50 rounded-2xl p-6 border border-amber-200 text-center">
                    <span class="text-xs font-bold text-slate-500 block mb-1">Admissions for Nov 20 Batch</span>
                    <h3 class="font-heading text-xl font-bold text-purple-950 mb-4">Enroll in Nuvo</h3>
                    <a href="{{ route('admissions.apply') }}?program=Nuvo" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <span>Apply For Nuvo</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Playgroup -->
            <div id="playgroup" class="bg-white rounded-3xl p-8 border-2 border-purple-200 shadow-md grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-purple-100 text-purple-900 border border-purple-300">
                            Age: 2 to 3 Years
                        </span>
                        <span class="text-xs text-slate-500 font-semibold">Timing: 9:00 AM – 12:00 PM</span>
                    </div>
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900">Playgroup Program</h2>
                    <p class="text-xs font-bold text-purple-700 uppercase tracking-wider">Words, Curiosity & Social Play</p>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        At age 2, a child's vocabulary explodes. Playgroup harnesses this through interactive circle time, puppet drama, color mixing, and sensory math counters. Children learn cooperation, toilet training milestones, self-feeding, and confidence in group settings.
                    </p>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-purple-600"></i> Phonics vocabulary flashcards
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-purple-600"></i> Puppet storytelling theater
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-purple-600"></i> Collaborative block engineering
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-purple-600"></i> Safe outdoor sand play
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-4 bg-purple-50 rounded-2xl p-6 border border-purple-200 text-center">
                    <span class="text-xs font-bold text-slate-500 block mb-1">Admissions for Nov 20 Batch</span>
                    <h3 class="font-heading text-xl font-bold text-purple-950 mb-4">Enroll in Playgroup</h3>
                    <a href="{{ route('admissions.apply') }}?program=Playgroup" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <span>Apply For Playgroup</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Nursery -->
            <div id="nursery" class="bg-white rounded-3xl p-8 border-2 border-sky-200 shadow-md grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-sky-100 text-sky-900 border border-sky-300">
                            Age: 3 to 4 Years
                        </span>
                        <span class="text-xs text-slate-500 font-semibold">Timing: 9:00 AM – 12:30 PM</span>
                    </div>
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900">Nursery Program</h2>
                    <p class="text-xs font-bold text-sky-600 uppercase tracking-wider">Foundations of Literacy, Phonics & Numbers</p>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Nursery sparks structured pre-reading habits, Jolly Phonics letter-sound blending, pattern tracing, basic numeracy, spatial geometry, and nature discovery. Children become articulate communicators and creative thinkers.
                    </p>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-sky-500"></i> Jolly Phonics reading foundation
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-sky-500"></i> Pattern writing & pre-cursive grip
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-sky-500"></i> Counting 1 to 50 with concrete beads
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-sky-500"></i> Monthly nature & science themes
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-4 bg-sky-50 rounded-2xl p-6 border border-sky-200 text-center">
                    <span class="text-xs font-bold text-slate-500 block mb-1">Admissions for Nov 20 Batch</span>
                    <h3 class="font-heading text-xl font-bold text-purple-950 mb-4">Enroll in Nursery</h3>
                    <a href="{{ route('admissions.apply') }}?program=Nursery" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <span>Apply For Nursery</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- 4. Kindergarten (Junior & Senior KG) -->
            <div id="junior-kg" class="bg-white rounded-3xl p-8 border-2 border-emerald-200 shadow-md grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                            Age: 4 to 6 Years
                        </span>
                        <span class="text-xs text-slate-500 font-semibold">Timing: 8:30 AM – 1:30 PM</span>
                    </div>
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900">Junior & Senior KG</h2>
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Advanced Literacy, Mental Math & School Readiness</p>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Preparing children seamlessly for formal primary school admissions (ICSE, CBSE, IB, Cambridge). Features fluent sentence writing, mental arithmetic, science exploration experiments, basic coding blocks, and public stage confidence.
                    </p>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Cursive sentence formation & spelling
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Math word problems & basic fractions
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Junior STEM & logic puzzles
                        </div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Annual day & graduation gala prep
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-4 bg-emerald-50 rounded-2xl p-6 border border-emerald-200 text-center">
                    <span class="text-xs font-bold text-slate-500 block mb-1">Admissions for Nov 20 Batch</span>
                    <h3 class="font-heading text-xl font-bold text-purple-950 mb-4">Enroll in Kindergarten</h3>
                    <a href="{{ route('admissions.apply') }}?program=Junior%20KG" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <span>Apply For Kindergarten</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
