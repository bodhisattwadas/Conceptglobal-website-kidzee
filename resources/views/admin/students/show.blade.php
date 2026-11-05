@extends('layouts.admin')

@section('title', 'Student Profile: ' . $student->full_name)
@section('page_title', 'Student Profile')
@section('page_subtitle', $student->admission_number . ' — ' . $student->full_name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Top Navigation & Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Student Directory
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.id-card', $student) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-id-badge"></i> Print Student ID Card
            </a>
            <a href="{{ route('admin.students.edit', $student) }}" class="px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
            <a href="{{ route('admin.invoices.create', ['student_id' => $student->id]) }}" class="px-3.5 py-2 rounded-xl bg-amber-400 hover:bg-amber-500 text-purple-950 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-invoice-dollar"></i> New Fee Bill
            </a>
        </div>
    </div>

    <!-- Student Header Dossier Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-5">
                <img src="{{ $student->photo_url ?: 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($student->first_name) }}" alt="{{ $student->full_name }}" class="w-20 h-20 rounded-3xl bg-amber-100 object-cover shadow-sm border-2 border-amber-300">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $student->full_name }}</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase {{ $student->status === 'active' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                    <p class="text-xs font-mono font-bold text-purple-900 mt-1">
                        Admission No: {{ $student->admission_number }} • Roll: {{ $student->roll_number ?: 'N/A' }}
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Program: <strong class="text-purple-800">{{ $student->program }}</strong> (Section {{ $student->section }}) • Admitted: {{ $student->admission_date->format('d M Y') }}
                    </p>
                </div>
            </div>

            <!-- Fast Stats -->
            <div class="flex items-center gap-4 bg-slate-50 p-3.5 rounded-2xl border border-slate-200 text-center">
                <div>
                    <span class="text-xs text-slate-400 font-bold block uppercase">Age</span>
                    <span class="font-bold text-slate-900 text-sm">{{ Carbon\Carbon::parse($student->dob)->age }} Yrs</span>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-xs text-slate-400 font-bold block uppercase">Blood Group</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $student->blood_group ?: 'N/A' }}</span>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-xs text-slate-400 font-bold block uppercase">Gender</span>
                    <span class="font-bold text-slate-900 text-sm">{{ $student->gender }}</span>
                </div>
            </div>
        </div>

        <!-- Personal & Contacts Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6">
            <!-- Parent Info -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Parents Details</span>
                <p class="text-xs font-bold text-slate-900">Father: {{ $student->father_name }}</p>
                <p class="text-xs text-slate-600"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $student->father_phone }}</p>
                @if($student->mother_name)
                    <p class="text-xs font-bold text-slate-900 pt-1">Mother: {{ $student->mother_name }}</p>
                    <p class="text-xs text-slate-600"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $student->mother_phone }}</p>
                @endif
            </div>

            <!-- Address & Emergency -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Address & Emergency</span>
                <p class="text-xs text-slate-700 leading-relaxed">{{ $student->address }}</p>
                <div class="pt-1 text-xs">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Emergency SOS:</span>
                    <strong class="text-slate-900">{{ $student->emergency_contact_name ?: 'Parent Contact' }}</strong>
                    <span class="text-slate-600 block">{{ $student->emergency_contact_phone ?: $student->father_phone }}</span>
                </div>
            </div>

            <!-- Health & Referral Source -->
            <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200 space-y-2">
                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider block">Referral & Origin</span>
                @if($student->referral_source)
                    <p class="text-xs text-slate-800">
                        Learnt about us: <strong class="text-purple-900">{{ $student->referral_source }}</strong>
                    </p>
                @endif
                @if($student->inquiry)
                    <p class="text-xs text-slate-600">
                        Origin Query: <a href="{{ route('admin.inquiries.show', $student->inquiry) }}" class="font-bold text-purple-700 hover:underline">{{ $student->inquiry->inquiry_number }}</a>
                    </p>
                @endif
                <div class="pt-1 border-t border-amber-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Health Notes:</span>
                    <p class="text-[11px] text-slate-700">{{ $student->medical_notes ?: 'No special health conditions reported.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Billing & Current Fee Flag (Flow Step 4 & 5) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-purple-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-purple-900 text-amber-300 font-bold text-[10px] uppercase">
                        Flow Step 4 & 5
                    </span>
                    <h3 class="font-heading font-black text-slate-900 text-lg">
                        Eligible for Billing • Monthly Fee Engine
                    </h3>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Recurring tuition, daycare, transport, discounts and live payment status</p>
            </div>

            @php
                $currentInv = $student->getMonthlyInvoice($currentMonth, $currentYear);
            @endphp

            <!-- Current Month Flag -->
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-600">
                    {{ DateTime::createFromFormat('!m', $currentMonth)->format('F') }} {{ $currentYear }} Flag:
                </span>
                @if($currentMonthStatus['status'] === 'paid')
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Fee Paid
                    </span>
                @elseif($currentMonthStatus['status'] === 'pending')
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300 flex items-center gap-1.5 animate-pulse">
                        <i class="fa-solid fa-clock text-amber-600"></i> Fee Due (Pending)
                    </span>
                @elseif($currentMonthStatus['status'] === 'overdue')
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i> Fee Overdue
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-slate-100 text-slate-600 border border-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-hourglass-start text-slate-400"></i> Unbilled
                    </span>
                @endif
            </div>
        </div>

        <!-- Monthly Fee Structure Breakdown -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
            <div class="p-3.5 rounded-2xl bg-purple-50/70 border border-purple-100">
                <span class="text-[10px] font-bold text-purple-900 uppercase tracking-wider block">Base Tuition</span>
                <span class="text-base font-black text-slate-900 mt-1 block">₹{{ number_format($student->monthly_tuition_fee, 2) }}</span>
                <span class="text-[10px] text-slate-400">Monthly</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200">
                <span class="text-[10px] font-bold text-amber-900 uppercase tracking-wider block">Day Care Service</span>
                <span class="text-base font-black text-slate-900 mt-1 block">
                    {{ $student->has_daycare ? '₹' . number_format($student->monthly_daycare_fee, 2) : 'Not Subscribed' }}
                </span>
                <span class="text-[10px] {{ $student->has_daycare ? 'text-amber-700 font-bold' : 'text-slate-400' }}">
                    {{ $student->has_daycare ? 'Active Add-on' : 'None' }}
                </span>
            </div>

            <div class="p-3.5 rounded-2xl bg-sky-50/70 border border-sky-100">
                <span class="text-[10px] font-bold text-sky-900 uppercase tracking-wider block">Bus Transport</span>
                <span class="text-base font-black text-slate-900 mt-1 block">
                    {{ $student->has_transport ? '₹' . number_format($student->monthly_transport_fee, 2) : 'Not Subscribed' }}
                </span>
                <span class="text-[10px] {{ $student->has_transport ? 'text-sky-700 font-bold' : 'text-slate-400' }}">
                    {{ $student->has_transport ? 'Active Add-on' : 'None' }}
                </span>
            </div>

            <div class="p-3.5 rounded-2xl bg-rose-50/70 border border-rose-100">
                <span class="text-[10px] font-bold text-rose-900 uppercase tracking-wider block">Monthly Discount</span>
                <span class="text-base font-black text-rose-700 mt-1 block">- ₹{{ number_format($student->monthly_discount, 2) }}</span>
                <span class="text-[10px] text-slate-400">Sibling / Merit</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-purple-900 text-white shadow">
                <span class="text-[10px] font-bold text-amber-300 uppercase tracking-wider block">Total Monthly Bill</span>
                <span class="text-lg font-black text-white mt-1 block">₹{{ number_format($student->total_monthly_fee, 2) }}</span>
                <span class="text-[10px] text-purple-200">Per Month</span>
            </div>
        </div>

        <!-- Action bar for Current Month Bill -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
            <div>
                <span class="font-bold text-slate-800">
                    Cycle: {{ DateTime::createFromFormat('!m', $currentMonth)->format('F') }} {{ $currentYear }} Bill
                </span>
                @if($currentInv)
                    <p class="text-slate-500 text-[11px] mt-0.5">
                        Invoice {{ $currentInv->invoice_number }} • Total: ₹{{ number_format($currentInv->total_amount, 2) }} • Paid: ₹{{ number_format($currentInv->paid_amount, 2) }} • Due: ₹{{ number_format($currentInv->balance_due, 2) }}
                    </p>
                @else
                    <p class="text-slate-500 text-[11px] mt-0.5">
                        Bill for this cycle has not been generated yet. You can generate it now or run month-end bulk billing.
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                @if(!$currentInv)
                    <form action="{{ route('admin.billing.generateSingle', $student) }}" method="POST">
                        @csrf
                        <input type="hidden" name="month" value="{{ $currentMonth }}">
                        <input type="hidden" name="year" value="{{ $currentYear }}">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                            <i class="fa-solid fa-bolt"></i> Generate {{ DateTime::createFromFormat('!m', $currentMonth)->format('M') }} Bill Now
                        </button>
                    </form>
                @elseif($currentInv->status !== 'paid')
                    <a href="{{ route('admin.invoices.show', $currentInv) }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-money-bill-wave"></i> Collect Payment (₹{{ number_format($currentInv->balance_due, 2) }})
                    </a>
                @else
                    <a href="{{ route('admin.invoices.receipt', $currentInv) }}" target="_blank" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        <i class="fa-solid fa-receipt"></i> Download Official Receipt
                    </a>
                @endif
                <a href="{{ route('admin.billing.monthly', ['month' => $currentMonth, 'year' => $currentYear]) }}" class="px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
                    Month-End Console →
                </a>
            </div>
        </div>
    </div>

    <!-- Active Subscriptions & Daycare Services (Feature 3) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate text-purple-700"></i> Active Subscriptions & Special Plans (Feature 3)
                </h3>
                <p class="text-xs text-slate-500">Daycare, hobby clubs, and supplemental packages</p>
            </div>

            <button type="button" onclick="document.getElementById('quick-add-sub').classList.toggle('hidden')" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-800 shadow-2xs">
                + Enroll in Package
            </button>
        </div>

        <!-- Quick Add Sub Panel (Toggleable) -->
        <div id="quick-add-sub" class="hidden mb-6 p-4 rounded-2xl bg-purple-50/60 border border-purple-200">
            <h4 class="text-xs font-bold text-purple-900 uppercase mb-3">Add Subscription for {{ $student->first_name }}</h4>
            <form action="{{ route('admin.subscriptions.assign') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Select Package</label>
                    <select name="subscription_plan_id" required class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs bg-white">
                        @foreach($availablePlans as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (₹{{ number_format($p->price) }} / {{ $p->billing_cycle }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs">
                </div>
                <div>
                    <button type="submit" class="w-full py-2 rounded-xl bg-purple-900 text-amber-300 font-bold text-xs hover:bg-purple-800">
                        Enroll
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-3">
            @forelse($student->subscriptions as $sub)
                <div class="p-4 rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-slate-900 text-sm">{{ $sub->plan->name }}</h4>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sub->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $sub->status }}
                            </span>
                        </div>
                        <p class="text-slate-500 mt-0.5">
                            Valid: {{ $sub->start_date->format('d M Y') }} to {{ $sub->end_date ? $sub->end_date->format('d M Y') : 'Ongoing' }} • Cycle: {{ ucfirst($sub->plan->billing_cycle) }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="font-heading font-black text-sm text-slate-900">₹{{ number_format($sub->amount, 2) }}</span>
                        <form action="{{ route('admin.subscriptions.generateInvoice', $sub) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-amber-400 text-purple-950 font-bold text-[11px] hover:bg-amber-500 shadow-2xs">
                                Generate Bill
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-3">No active subscription plans attached. Base tuition fee is billed via the Monthly Fee Engine.</p>
            @endforelse
        </div>
    </div>

    <!-- Fee Invoices & Payment History -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i> Fee Statements & Invoices
                </h3>
                <p class="text-xs text-slate-500">Transaction history and receipt downloads</p>
            </div>

            <a href="{{ route('admin.invoices.create', ['student_id' => $student->id]) }}" class="px-3.5 py-1.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-2xs">
                + New Custom Invoice
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                        <th class="py-2.5 px-3">Invoice #</th>
                        <th class="py-2.5 px-3">Description</th>
                        <th class="py-2.5 px-3">Billing Cycle</th>
                        <th class="py-2.5 px-3">Total Amount</th>
                        <th class="py-2.5 px-3">Paid Amount</th>
                        <th class="py-2.5 px-3">Status Flag</th>
                        <th class="py-2.5 px-3 text-right">Receipt / Pay</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($student->invoices as $inv)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3 px-3 font-bold text-purple-900">
                                <a href="{{ route('admin.invoices.show', $inv) }}" class="hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td class="py-3 px-3 font-medium text-slate-800">{{ $inv->title }}</td>
                            <td class="py-3 px-3 text-slate-500">
                                @if($inv->billing_month && $inv->billing_year)
                                    {{ DateTime::createFromFormat('!m', $inv->billing_month)->format('M') }} {{ $inv->billing_year }}
                                @else
                                    {{ $inv->invoice_date->format('M Y') }}
                                @endif
                            </td>
                            <td class="py-3 px-3 font-bold text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</td>
                            <td class="py-3 px-3 font-bold text-emerald-600">₹{{ number_format($inv->paid_amount, 2) }}</td>
                            <td class="py-3 px-3">
                                @if($inv->status === 'paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                        🟢 Paid
                                    </span>
                                @elseif($inv->status === 'cancelled')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-600">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                        🟡 {{ ucfirst($inv->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right">
                                @if($inv->status === 'paid')
                                    <a href="{{ route('admin.invoices.receipt', $inv) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-purple-900 text-amber-300 font-bold text-[11px] hover:bg-purple-800 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-receipt text-[10px]"></i> Receipt
                                    </a>
                                @else
                                    <a href="{{ route('admin.invoices.show', $inv) }}" class="px-2.5 py-1 rounded-lg bg-amber-400 hover:bg-amber-500 text-purple-950 font-bold text-[11px]">
                                        Pay →
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-slate-400">No invoices generated yet for this student.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attendance History Log -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-teal-600"></i> Recent Attendance Log
                </h3>
                <p class="text-xs text-slate-500">Daily check-in and check-out tracking</p>
            </div>
            <a href="{{ route('admin.attendance.index') }}" class="text-xs font-bold text-teal-700 hover:underline">
                Mark Today's Register →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            @forelse($student->attendances as $att)
                <div class="p-3 rounded-2xl border {{ $att->status === 'present' ? 'border-emerald-200 bg-emerald-50/40 text-emerald-900' : 'border-rose-200 bg-rose-50/40 text-rose-900' }} text-center text-xs">
                    <span class="block font-bold text-slate-700">{{ $att->date->format('d M') }}</span>
                    <span class="inline-block my-1 px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $att->status === 'present' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                        {{ $att->status }}
                    </span>
                    <span class="block text-[10px] text-slate-400">{{ $att->check_in_time ? date('h:i A', strtotime($att->check_in_time)) : '-' }}</span>
                </div>
            @empty
                <div class="col-span-5 text-center py-4 text-xs text-slate-400">
                    No attendance records for recent days.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
