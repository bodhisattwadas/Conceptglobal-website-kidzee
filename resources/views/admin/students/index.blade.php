@extends('layouts.admin')

@section('title', 'Student Database')
@section('page_title', '4. Student Database')
@section('page_subtitle', 'Enrolled preschool & daycare student directory with monthly billing status')

@section('content')
<div class="space-y-6">

    <!-- Program Distribution KPI Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 text-center shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Enrolled</span>
            <span class="text-2xl font-black text-slate-900 mt-0.5 block">{{ $stats['total'] }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 text-center shadow-xs">
            <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Nuvo</span>
            <span class="text-xl font-bold text-slate-900 mt-0.5 block">{{ $stats['nuvo'] }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 text-center shadow-xs">
            <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Playgroup</span>
            <span class="text-xl font-bold text-slate-900 mt-0.5 block">{{ $stats['playgroup'] }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 text-center shadow-xs">
            <span class="text-[10px] font-bold text-sky-600 uppercase tracking-wider block">Nursery</span>
            <span class="text-xl font-bold text-slate-900 mt-0.5 block">{{ $stats['nursery'] }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 text-center shadow-xs">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Junior KG</span>
            <span class="text-xl font-bold text-slate-900 mt-0.5 block">{{ $stats['junior_kg'] }}</span>
        </div>
        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 text-center shadow-xs">
            <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider block">Senior KG</span>
            <span class="text-xl font-bold text-slate-900 mt-0.5 block">{{ $stats['senior_kg'] }}</span>
        </div>
    </div>

    <!-- Monthly Fee Flags Quick Selector -->
    <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-purple-950 p-4 rounded-3xl text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-400 text-purple-950 font-black text-[10px] uppercase tracking-wider">
                    Monthly Fee Flags
                </span>
                <span class="text-xs text-purple-200">
                    Cycle: {{ DateTime::createFromFormat('!m', $currentMonth)->format('F') }} {{ $currentYear }}
                </span>
            </div>
            <h4 class="font-heading font-black text-base text-white mt-1">Payment Verification & Fee Status</h4>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.students.index', ['fee_status' => 'all', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ empty($feeFilter) || $feeFilter === 'all' ? 'bg-amber-400 text-purple-950 shadow' : 'bg-white/10 text-white hover:bg-white/20' }}">
                All Fees
            </a>
            <a href="{{ route('admin.students.index', ['fee_status' => 'paid', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $feeFilter === 'paid' ? 'bg-emerald-500 text-white shadow' : 'bg-white/10 text-white hover:bg-white/20' }}">
                🟢 Paid
            </a>
            <a href="{{ route('admin.students.index', ['fee_status' => 'pending', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $feeFilter === 'pending' ? 'bg-amber-500 text-purple-950 shadow' : 'bg-white/10 text-white hover:bg-white/20' }}">
                🟡 Due / Pending
            </a>
            <a href="{{ route('admin.students.index', ['fee_status' => 'overdue', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $feeFilter === 'overdue' ? 'bg-rose-600 text-white shadow' : 'bg-white/10 text-white hover:bg-white/20' }}">
                🔴 Overdue
            </a>
            <a href="{{ route('admin.students.index', ['fee_status' => 'unbilled', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $feeFilter === 'unbilled' ? 'bg-slate-300 text-slate-900 shadow' : 'bg-white/10 text-white hover:bg-white/20' }}">
                ⚪ Unbilled
            </a>
            <a href="{{ route('admin.billing.monthly', ['month' => $currentMonth, 'year' => $currentYear]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-purple-900 hover:bg-amber-300 hover:text-purple-950 shadow transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-invoice-dollar"></i> Month-End Billing Console →
            </a>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            @if($feeFilter) <input type="hidden" name="fee_status" value="{{ $feeFilter }}"> @endif
            <select name="program" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs font-semibold text-slate-800 focus:outline-none">
                <option value="all">All Programs</option>
                <option value="Nuvo" {{ $program === 'Nuvo' ? 'selected' : '' }}>Nuvo</option>
                <option value="Playgroup" {{ $program === 'Playgroup' ? 'selected' : '' }}>Playgroup</option>
                <option value="Nursery" {{ $program === 'Nursery' ? 'selected' : '' }}>Nursery</option>
                <option value="Junior KG" {{ $program === 'Junior KG' ? 'selected' : '' }}>Junior KG</option>
                <option value="Senior KG" {{ $program === 'Senior KG' ? 'selected' : '' }}>Senior KG</option>
                <option value="Daycare" {{ $program === 'Daycare' ? 'selected' : '' }}>Day Care</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs font-semibold text-slate-800 focus:outline-none">
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active Students</option>
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive / Withdrawn</option>
                <option value="graduated" {{ $status === 'graduated' ? 'selected' : '' }}>Graduated</option>
            </select>

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search name, admission #, phone..." class="pl-9 pr-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                Search
            </button>
            @if($search || $program || $feeFilter || $status !== 'active')
                <a href="{{ route('admin.students.index') }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs hover:bg-slate-200">
                    Reset
                </a>
            @endif
        </form>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.attendance.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 shadow-2xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-check text-teal-600"></i> Attendance
            </a>
            <a href="{{ route('admin.students.create') }}" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus"></i> Enroll Student
            </a>
        </div>
    </div>

    <!-- Student Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $stu)
            @php
                $feeFlag = $studentFeeStatuses[$stu->id] ?? 'unbilled';
                $inv = $stu->invoices->first();
            @endphp
            <div class="bg-white rounded-3xl p-6 border border-slate-200 hover:border-purple-300 shadow-xs hover:shadow-md transition space-y-4 flex flex-col justify-between">
                <div>
                    <!-- Student Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $stu->photo_url ?: 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($stu->first_name) }}" alt="{{ $stu->full_name }}" class="w-14 h-14 rounded-2xl bg-amber-100 object-cover shadow-xs border border-amber-200">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base leading-tight">
                                    <a href="{{ route('admin.students.show', $stu) }}" class="hover:text-purple-800">
                                        {{ $stu->full_name }}
                                    </a>
                                </h3>
                                <p class="text-[11px] font-mono font-bold text-purple-900 mt-0.5">{{ $stu->admission_number }}</p>
                                <span class="text-[10px] text-slate-400">Roll: {{ $stu->roll_number ?: 'N/A' }} • Sec {{ $stu->section }}</span>
                            </div>
                        </div>

                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $stu->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ ucfirst($stu->status) }}
                        </span>
                    </div>

                    <!-- Program & Monthly Fee Banner -->
                    <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-800 border border-purple-200 text-xs">
                                {{ $stu->program }}
                            </span>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 uppercase block font-semibold">Monthly Plan</span>
                                <span class="text-xs font-black text-slate-900">₹{{ number_format($stu->total_monthly_fee) }}/mo</span>
                            </div>
                        </div>

                        <!-- Current Month Fee Status Flag -->
                        <div class="pt-2 border-t border-slate-200/70 flex items-center justify-between text-xs">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                {{ DateTime::createFromFormat('!m', $currentMonth)->format('M') }} Fee Flag:
                            </span>

                            @if($feeFlag === 'paid')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                                    Paid
                                </span>
                            @elseif($feeFlag === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                                    <i class="fa-solid fa-clock text-amber-600 text-[10px]"></i>
                                    Due (₹{{ number_format($inv?->balance_due ?? $stu->total_monthly_fee) }})
                                </span>
                            @elseif($feeFlag === 'overdue')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-rose-100 text-rose-800 border border-rose-300">
                                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-[10px]"></i>
                                    Overdue (₹{{ number_format($inv?->balance_due ?? $stu->total_monthly_fee) }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-200 text-slate-700">
                                    <i class="fa-solid fa-hourglass-start text-[10px]"></i>
                                    Unbilled
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Contact details -->
                    <div class="mt-3 space-y-1 text-xs text-slate-600">
                        <p class="truncate"><i class="fa-solid fa-user text-[10px] mr-1.5 text-slate-400"></i> Parent: <strong class="text-slate-800">{{ $stu->father_name ?: $stu->mother_name }}</strong></p>
                        <p><i class="fa-solid fa-phone text-[10px] mr-1.5 text-slate-400"></i> {{ $stu->father_phone ?: $stu->mother_phone }}</p>
                        @if($stu->referral_source)
                            <p class="text-[11px] text-amber-800 truncate"><i class="fa-solid fa-bullhorn text-[10px] mr-1 text-amber-600"></i> Learnt via: <strong>{{ $stu->referral_source }}</strong></p>
                        @endif
                    </div>

                    <!-- Service add-ons -->
                    <div class="mt-3 flex flex-wrap gap-1 text-[10px]">
                        @if($stu->has_daycare)
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-900 font-bold border border-amber-200">
                                <i class="fa-solid fa-cubes-stacked mr-1"></i> Daycare (+₹{{ number_format($stu->monthly_daycare_fee) }})
                            </span>
                        @endif
                        @if($stu->has_transport)
                            <span class="px-2 py-0.5 rounded-md bg-sky-100 text-sky-900 font-bold border border-sky-200">
                                <i class="fa-solid fa-bus mr-1"></i> Transport (+₹{{ number_format($stu->monthly_transport_fee) }})
                            </span>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.students.id-card', $stu) }}" target="_blank" class="text-xs font-bold text-purple-700 hover:text-purple-900 flex items-center gap-1">
                        <i class="fa-solid fa-id-badge text-amber-500"></i> ID Card
                    </a>
                    <div class="space-x-1">
                        <a href="{{ route('admin.students.edit', $stu) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]">
                            Edit
                        </a>
                        <a href="{{ route('admin.students.show', $stu) }}" class="px-3 py-1 rounded-lg bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-[11px]">
                            Profile & Billing →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200">
                <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-2">
                    <i class="fa-solid fa-children"></i>
                </div>
                <h4 class="font-bold text-slate-700 text-sm">No student records found</h4>
                <p class="text-xs text-slate-500 mt-1">Enroll your first student or convert an admission inquiry.</p>
            </div>
        @endforelse
    </div>

    @if($students->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200">
            {{ $students->links() }}
        </div>
    @endif

</div>
@endsection
