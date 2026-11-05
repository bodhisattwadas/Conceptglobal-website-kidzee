@extends('layouts.app')

@section('title', 'Track Admission & Invoice Status | Kidzee Preschool')

@section('content')
<div class="py-12 bg-amber-50/40 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Search Header -->
        <div class="text-center mb-8">
            <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-900 text-xs font-bold uppercase tracking-wider mb-2">
                Parent Self-Service Portal
            </span>
            <h1 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
                Track Your Child's Admission Status
            </h1>
            <p class="text-slate-600 text-sm mt-2 max-w-lg mx-auto">
                Check the review stage of your online admission form, review billing invoices, and download official payment receipts.
            </p>
        </div>

        <!-- Search Box -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border-4 border-amber-300 mb-8">
            <form action="{{ route('admissions.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="query" value="{{ $search ?? '' }}" required placeholder="Enter Application ID (e.g. KDZ-APP-2026-101) or Mobile Number" class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-600 text-sm font-semibold text-slate-800">
                </div>
                <button type="submit" class="px-8 py-3 rounded-2xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                    <span>Check Status</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
            <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                <span>Try sample applicant:</span>
                <a href="{{ route('admissions.track', ['query' => 'KDZ-APP-2026-101']) }}" class="text-purple-700 underline font-semibold">KDZ-APP-2026-101</a>
                <span>or</span>
                <a href="{{ route('admissions.track', ['query' => '+91 98712 34567']) }}" class="text-purple-700 underline font-semibold">+91 98712 34567</a>
            </div>
        </div>

        @if($search && !$application)
            <div class="p-8 rounded-3xl bg-white border border-slate-200 text-center shadow-md">
                <div class="w-16 h-16 mx-auto rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-file-circle-xmark"></i>
                </div>
                <h3 class="font-heading text-xl font-bold text-slate-900 mb-1">No Application Found</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    We could not find an admission application matching "<strong>{{ $search }}</strong>". Please verify your application number or parent mobile number.
                </p>
                <a href="{{ route('admissions.apply') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-amber-400 text-purple-950 font-bold text-xs shadow-sm hover:bg-amber-500 transition">
                    Fill New Admission Application
                </a>
            </div>
        @elseif($application)
            <!-- Application Result Details -->
            <div class="space-y-6 animate-fade-in">
                <!-- Status Banner Card -->
                <div class="p-6 sm:p-8 rounded-3xl bg-white border-2 border-purple-200 shadow-xl">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Application Reference</span>
                            <h2 class="font-heading text-2xl font-black text-purple-950">{{ $application->application_number }}</h2>
                            <p class="text-xs text-slate-500">Submitted on {{ $application->created_at->format('M d, Y') }} • Batch: Opening Nov 20, 2026</p>
                        </div>
                        <div>
                            @if($application->status === 'admitted')
                                <span class="px-4 py-2 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i> ADMISSION CONFIRMED
                                </span>
                            @elseif($application->status === 'approved')
                                <span class="px-4 py-2 rounded-full text-xs font-black bg-sky-100 text-sky-800 border border-sky-300 flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-thumbs-up text-sky-600"></i> APPLICATION APPROVED
                                </span>
                            @elseif($application->status === 'under_review')
                                <span class="px-4 py-2 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300 flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-spinner fa-spin text-amber-600"></i> UNDER COUNSELOR REVIEW
                                </span>
                            @else
                                <span class="px-4 py-2 rounded-full text-xs font-black bg-purple-100 text-purple-800 border border-purple-300 flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-clock text-purple-600"></i> SUBMITTED
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Progress Step Indicator -->
                    <div class="py-6 border-b border-slate-100">
                        <div class="grid grid-cols-4 gap-2 text-center text-xs">
                            <!-- Step 1 -->
                            <div class="space-y-1">
                                <div class="w-8 h-8 mx-auto rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="block font-bold text-slate-800">1. Form Received</span>
                            </div>

                            <!-- Step 2 -->
                            <div class="space-y-1">
                                <div class="w-8 h-8 mx-auto rounded-full {{ in_array($application->status, ['under_review', 'approved', 'admitted']) ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-user-check"></i>
                                </div>
                                <span class="block font-bold text-slate-800">2. Review</span>
                            </div>

                            <!-- Step 3 -->
                            <div class="space-y-1">
                                <div class="w-8 h-8 mx-auto rounded-full {{ in_array($application->status, ['approved', 'admitted']) ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-shield-heart"></i>
                                </div>
                                <span class="block font-bold text-slate-800">3. Approved</span>
                            </div>

                            <!-- Step 4 -->
                            <div class="space-y-1">
                                <div class="w-8 h-8 mx-auto rounded-full {{ $application->status === 'admitted' ? 'bg-purple-900 text-amber-300' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <span class="block font-bold text-slate-800">4. Enrolled</span>
                            </div>
                        </div>
                    </div>

                    <!-- Child & Enrolled Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6">
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Child Details</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $application->child_full_name }}</p>
                            <p class="text-xs text-slate-500">DOB: {{ $application->dob->format('d M Y') }} • {{ $application->gender }}</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Program Applied</span>
                            <p class="font-bold text-purple-900 text-sm mt-0.5">{{ $application->program_applied }}</p>
                            <p class="text-xs text-slate-500">Academic Year: {{ $application->academic_year }}</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Parent Contact</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $application->father_name ?: $application->mother_name }}</p>
                            <p class="text-xs text-slate-500">{{ $application->father_phone ?: $application->mother_phone }}</p>
                        </div>
                    </div>

                    @if($application->student)
                        <!-- Student record badge -->
                        <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-id-badge"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-emerald-800 uppercase">Enrolled Student Profile Active</span>
                                    <h4 class="font-heading text-sm font-bold text-emerald-950">Admission Number: {{ $application->student->admission_number }}</h4>
                                </div>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 bg-emerald-600 text-white rounded-full">
                                Class Section {{ $application->student->section }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Billing & Invoices (Feature 2) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h3 class="font-heading text-xl font-bold text-slate-900">Billing & Fee Invoices</h3>
                            <p class="text-xs text-slate-500">Fee statements associated with this admission</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                            {{ $application->invoices->count() }} Invoice(s)
                        </span>
                    </div>

                    @forelse($application->invoices as $inv)
                        <div class="p-5 rounded-2xl border {{ $inv->status === 'paid' ? 'border-emerald-200 bg-emerald-50/30' : 'border-amber-200 bg-amber-50/30' }} mb-4">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-3">
                                <div>
                                    <span class="text-xs font-bold text-slate-400">{{ $inv->invoice_number }}</span>
                                    <h4 class="font-bold text-slate-900 text-base">{{ $inv->title }}</h4>
                                    <p class="text-xs text-slate-500">Due Date: {{ $inv->due_date->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs uppercase font-bold px-2.5 py-0.5 rounded-full {{ $inv->status === 'paid' ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-purple-950' }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                    <div class="font-heading text-xl font-black text-slate-900 mt-1">
                                        ₹{{ number_format($inv->total_amount, 2) }}
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row items-start sm:items-center justify-between text-xs gap-2">
                                <div class="text-slate-600">
                                    <span>Paid: <strong>₹{{ number_format($inv->paid_amount, 2) }}</strong></span>
                                    @if($inv->balance_due > 0)
                                        <span class="text-rose-600 font-bold ml-2">Balance Due: ₹{{ number_format($inv->balance_due, 2) }}</span>
                                    @endif
                                </div>

                                <div>
                                    @if($inv->status === 'paid')
                                        <a href="{{ route('admin.invoices.receipt', $inv) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition">
                                            <i class="fa-solid fa-receipt text-xs"></i>
                                            <span>View / Print Official Receipt</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-amber-800 font-semibold">
                                            Payable via NetBanking / UPI or at campus helpdesk.
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-4">No invoices generated yet for this application.</p>
                    @endforelse
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
