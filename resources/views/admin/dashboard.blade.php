@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Campus Overview')
@section('page_subtitle', 'Concept Kidzee Flagship Preschool Management')

@section('content')
<div class="space-y-6">

    <!-- Grand Opening Countdown Banner -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-purple-900 via-indigo-900 to-purple-950 text-white shadow-lg border border-purple-800 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400 text-purple-950 text-xs font-black">
                <i class="fa-solid fa-sparkles"></i> INAUGURATION MILESTONE
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kidzee Grand Opening on November 20, 2026</h2>
            <p class="text-xs sm:text-sm text-purple-200/90 max-w-xl">
                Preparing welcome kits, finalizing classroom setups, and managing inaugural admissions.
            </p>
        </div>

        <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3.5 rounded-2xl border border-white/10">
            <div class="flex items-center gap-2 px-2" id="admin-countdown-timer">
                <div class="text-center min-w-[34px]">
                    <span class="block text-2xl font-black text-amber-300 font-brand" id="admin-cd-days">{{ $daysRemaining }}</span>
                    <span class="text-[10px] uppercase font-bold text-purple-200 tracking-wider">Days</span>
                </div>
                <span class="text-amber-300/70 font-black text-xl -mt-4">:</span>
                <div class="text-center min-w-[34px]">
                    <span class="block text-2xl font-black text-amber-300 font-brand" id="admin-cd-hours">{{ sprintf('%02d', $hoursRemaining) }}</span>
                    <span class="text-[10px] uppercase font-bold text-purple-200 tracking-wider">Hours</span>
                </div>
                <span class="text-amber-300/70 font-black text-xl -mt-4">:</span>
                <div class="text-center min-w-[34px]">
                    <span class="block text-2xl font-black text-amber-300 font-brand" id="admin-cd-mins">{{ sprintf('%02d', $minsRemaining) }}</span>
                    <span class="text-[10px] uppercase font-bold text-purple-200 tracking-wider">Mins</span>
                </div>
            </div>
            <div class="h-8 w-px bg-white/20"></div>
            <div class="text-center px-3">
                <span class="block text-2xl font-black text-white font-brand">{{ $pendingApplications }}</span>
                <span class="text-[10px] uppercase font-bold text-purple-200 tracking-wider">Pending Apps</span>
            </div>
            <div class="h-8 w-px bg-white/20"></div>
            <div class="text-center px-3">
                <span class="block text-2xl font-black text-emerald-400 font-brand">{{ $activeStudents }}</span>
                <span class="text-[10px] uppercase font-bold text-purple-200 tracking-wider">Confirmed</span>
            </div>
        </div>
    </div>

    <!-- 4 Core Metric KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Queries / Leads -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">1. Inquiries & Queries</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalQueries }}</span>
                    @if($newQueries > 0)
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">
                            {{ $newQueries }} New
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800 inline-flex items-center gap-1 mt-3">
                    <span>Manage queries</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-comment-dots"></i>
            </div>
        </div>

        <!-- 2. Admission Applications -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">2. Admission Forms</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalApplications }}</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                        {{ $pendingApplications }} Pending
                    </span>
                </div>
                <a href="{{ route('admin.admissions.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 inline-flex items-center gap-1 mt-3">
                    <span>Review applications</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-file-signature"></i>
            </div>
        </div>

        <!-- 3. Subscriptions -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">3. Subscriptions</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $activeSubscriptionsCount }}</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800">
                        Active Daycare/Plans
                    </span>
                </div>
                <a href="{{ route('admin.subscriptions.index') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-800 inline-flex items-center gap-1 mt-3">
                    <span>View packages</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-arrows-rotate"></i>
            </div>
        </div>

        <!-- 4. Fee Collections -->
        <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-sm flex items-start justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Fee Collected & Billing</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-black text-slate-900">₹{{ number_format($totalFeeCollected) }}</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">
                    Pending Dues: <span class="font-bold text-rose-600">₹{{ number_format($pendingFeeAmount) }}</span>
                </p>
                <a href="{{ route('admin.invoices.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 inline-flex items-center gap-1 mt-1.5">
                    <span>Billing invoices</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
        </div>
    </div>

    <!-- Quick Action Bar -->
    <div class="p-4 rounded-2xl bg-white border border-slate-200 flex flex-wrap items-center justify-between gap-3 text-xs">
        <span class="font-bold text-slate-700 uppercase tracking-wider">Quick Actions:</span>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.students.create') }}" class="px-3.5 py-1.5 rounded-xl bg-purple-900 text-amber-300 font-bold hover:bg-purple-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus text-xs"></i> Enroll Student
            </a>
            <a href="{{ route('admin.invoices.create') }}" class="px-3.5 py-1.5 rounded-xl bg-amber-400 text-slate-950 font-bold hover:bg-amber-500 transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-invoice-dollar text-xs"></i> Create Invoice
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="px-3.5 py-1.5 rounded-xl bg-teal-600 text-white font-bold hover:bg-teal-700 transition flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-check text-xs"></i> Mark Today's Attendance
            </a>
            <a href="{{ route('home') }}#inquiry-form-section" target="_blank" class="px-3.5 py-1.5 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-50 transition flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i> Open Website Form
            </a>
        </div>
    </div>

    <!-- Two-column content: Inquiries & Applications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- 1. Recent Inquiries (Queries) -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-comment-dots"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Recent Inquiries (Feature 1)</h3>
                            <p class="text-xs text-slate-500">Parent leads seeking admission</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-bold text-purple-700 hover:underline">
                        View All ({{ $totalQueries }})
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentInquiries as $inq)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-300 transition flex items-start justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900">{{ $inq->parent_name }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $inq->status === 'new' ? 'bg-rose-100 text-rose-700' : ($inq->status === 'converted' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $inq->status)) }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500">
                                    Child: <strong class="text-slate-800">{{ $inq->child_name }}</strong> ({{ $inq->child_age ?: 'Age not specified' }}) • Program: <strong class="text-purple-900">{{ $inq->program_interested }}</strong>
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    <i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $inq->phone }}
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-1.5 shrink-0">
                                <a href="{{ route('admin.inquiries.show', $inq) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-[11px] font-bold text-slate-700 shadow-2xs">
                                    Action
                                </a>
                                @if($inq->status !== 'converted')
                                    <form action="{{ route('admin.inquiries.convert', $inq) }}" method="POST">
                                        @csrf
                                        <button type="submit" title="Convert to Admission Application" class="text-[11px] text-purple-700 font-bold hover:underline">
                                            Convert →
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">No inquiries logged yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-bold text-purple-800 hover:underline">
                    Manage Inquiries Pipeline →
                </a>
            </div>
        </div>

        <!-- 2. Recent Admissions & Billing -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Admission Applications (Feature 2)</h3>
                            <p class="text-xs text-slate-500">Submitted online registration forms</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.admissions.index') }}" class="text-xs font-bold text-purple-700 hover:underline">
                        View All ({{ $totalApplications }})
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentApplications as $app)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-300 transition flex items-start justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900">{{ $app->child_full_name }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $app->status === 'admitted' ? 'bg-emerald-100 text-emerald-800' : ($app->status === 'approved' ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500">
                                    App ID: <strong class="text-purple-900">{{ $app->application_number }}</strong> • Program: <strong class="text-slate-800">{{ $app->program_applied }}</strong>
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    Father: {{ $app->father_name }} ({{ $app->father_phone }})
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-1.5 shrink-0">
                                <a href="{{ route('admin.admissions.show', $app) }}" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-[11px] font-bold text-slate-700 shadow-2xs">
                                    Review
                                </a>
                                @if($app->status !== 'admitted')
                                    <form action="{{ route('admin.admissions.enroll', $app) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[11px] text-emerald-700 font-bold hover:underline">
                                            Enroll Child →
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">No applications submitted yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.admissions.index') }}" class="text-xs font-bold text-purple-800 hover:underline">
                    Manage Admissions & Approval →
                </a>
            </div>
        </div>

    </div>

    <!-- Recent Billing & Invoices Table -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Recent Fee Invoices & Payment Status</h3>
                <p class="text-xs text-slate-500">Admission fees, daycare subscriptions, and receipts</p>
            </div>
            <a href="{{ route('admin.invoices.index') }}" class="text-xs font-bold text-purple-700 hover:underline">
                View All Invoices →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase font-bold border-b border-slate-100">
                        <th class="pb-3">Invoice No</th>
                        <th class="pb-3">Parent & Student</th>
                        <th class="pb-3">Description</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Receipt / Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentInvoices as $inv)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 font-bold text-purple-900">{{ $inv->invoice_number }}</td>
                            <td class="py-3">
                                <span class="font-bold text-slate-900 block">{{ $inv->parent_name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $inv->student ? 'Student: ' . $inv->student->full_name : 'Applicant' }}</span>
                            </td>
                            <td class="py-3 text-slate-600 max-w-xs truncate">{{ $inv->title }}</td>
                            <td class="py-3 font-bold text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</td>
                            <td class="py-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($inv->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.invoices.show', $inv) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] mr-1">
                                    Details
                                </a>
                                @if($inv->status === 'paid')
                                    <a href="{{ route('admin.invoices.receipt', $inv) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-purple-900 text-amber-300 font-bold text-[11px] hover:bg-purple-800">
                                        <i class="fa-solid fa-print"></i> Receipt
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-400">No invoices generated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const targetDate = new Date('{{ $openingDate->toIso8601String() }}').getTime();

        function updateAdminCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            const elDays = document.getElementById('admin-cd-days');
            const elHours = document.getElementById('admin-cd-hours');
            const elMins = document.getElementById('admin-cd-mins');

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

                if (elDays) elDays.innerText = days;
                if (elHours) elHours.innerText = String(hours).padStart(2, '0');
                if (elMins) elMins.innerText = String(minutes).padStart(2, '0');
            } else {
                if (elDays) elDays.innerText = '0';
                if (elHours) elHours.innerText = '00';
                if (elMins) elMins.innerText = '00';
            }
        }

        updateAdminCountdown();
        setInterval(updateAdminCountdown, 30000);
    });
</script>
@endsection
