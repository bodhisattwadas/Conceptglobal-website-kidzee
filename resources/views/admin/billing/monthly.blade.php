@extends('layouts.admin')

@section('title', 'Monthly Billing & Fee Generation')
@section('page_title', 'Monthly Billing & Fee Generation')
@section('page_subtitle', 'Generate month-end fee invoices and track payment status flags')

@section('content')
<div class="space-y-6">

    <!-- Month Selector & Global Bulk Generator Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Month Picker Form -->
        <form action="{{ route('admin.billing.monthly') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Billing Month</label>
                <select name="month" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create(2026, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Year</label>
                <select name="year" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                    @for($y = 2025; $y <= 2028; $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="pt-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-purple-50 text-purple-900 text-xs font-black">
                    <i class="fa-solid fa-calendar-day text-amber-500"></i> Active Period: {{ $billingDate->format('F Y') }}
                </span>
            </div>
        </form>

        <!-- Bulk Action Button -->
        <div class="flex items-center gap-2">
            <button type="button" onclick="document.getElementById('bulk-generate-modal').classList.remove('hidden')" class="px-5 py-2.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-extrabold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-bolt text-amber-400"></i>
                <span>Generate Bills for {{ $billingDate->format('F Y') }}</span>
            </button>
        </div>
    </div>

    <!-- Monthly Statistics KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <!-- Eligible Students -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Eligible Students</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['total_students'] }}</span>
            <span class="text-[11px] text-slate-500">Active enrollments</span>
        </div>

        <!-- Invoiced Count -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Invoices Billed</span>
            <span class="text-2xl font-black text-purple-900 mt-1 block">{{ $stats['billed_count'] }}</span>
            <span class="text-[11px] text-slate-500">{{ $stats['unbilled_count'] }} unbilled</span>
        </div>

        <!-- Paid Status -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Fees Paid (Flag: Paid)</span>
            <span class="text-2xl font-black text-emerald-600 mt-1 block">₹{{ number_format($stats['paid_amount'], 2) }}</span>
            <span class="text-[11px] font-bold text-emerald-700">{{ $stats['paid_count'] }} student(s)</span>
        </div>

        <!-- Due / Pending -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Fees Pending (Due)</span>
            <span class="text-2xl font-black text-amber-600 mt-1 block">₹{{ number_format($stats['due_amount'], 2) }}</span>
            <span class="text-[11px] font-bold text-amber-700">{{ $stats['due_count'] }} student(s)</span>
        </div>

        <!-- Overdue -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider block">Overdue Fees</span>
            <span class="text-2xl font-black text-rose-600 mt-1 block">₹{{ number_format($stats['overdue_amount'], 2) }}</span>
            <span class="text-[11px] font-bold text-rose-700">{{ $stats['overdue_count'] }} student(s)</span>
        </div>
    </div>

    <!-- Status Filters & Search Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 p-1.5 bg-slate-200/70 rounded-2xl text-xs font-bold">
            <a href="{{ route('admin.billing.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'status' => 'all', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ empty($statusFilter) || $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                All ({{ $stats['total_students'] }})
            </a>
            <a href="{{ route('admin.billing.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'status' => 'paid', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $statusFilter === 'paid' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Paid ({{ $stats['paid_count'] }})
            </a>
            <a href="{{ route('admin.billing.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'status' => 'pending', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Due / Pending ({{ $stats['due_count'] }})
            </a>
            <a href="{{ route('admin.billing.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'status' => 'overdue', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $statusFilter === 'overdue' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Overdue ({{ $stats['overdue_count'] }})
            </a>
            <a href="{{ route('admin.billing.monthly', ['month' => $selectedMonth, 'year' => $selectedYear, 'status' => 'unbilled', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $statusFilter === 'unbilled' ? 'bg-slate-700 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Unbilled ({{ $stats['unbilled_count'] }})
            </a>
        </div>

        <form action="{{ route('admin.billing.monthly') }}" method="GET" class="flex items-center gap-2">
            <input type="hidden" name="month" value="{{ $selectedMonth }}">
            <input type="hidden" name="year" value="{{ $selectedYear }}">
            @if($statusFilter) <input type="hidden" name="status" value="{{ $statusFilter }}"> @endif

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search student name, phone..." class="pl-9 pr-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                Search
            </button>
        </form>
    </div>

    <!-- Student Monthly Billing Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">Student Particulars</th>
                        <th class="py-3.5 px-4">Program & Add-ons</th>
                        <th class="py-3.5 px-4">Monthly Fee Breakdown</th>
                        <th class="py-3.5 px-4">Invoice # & Due Date</th>
                        <th class="py-3.5 px-4">Fee Status Flag</th>
                        <th class="py-3.5 px-4 text-right">Action / Settlement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($filteredStudents as $stu)
                        @php
                            $feeInfo = $studentStatuses[$stu->id] ?? ['status' => 'unbilled', 'label' => 'Unbilled', 'badge' => 'bg-slate-100 text-slate-600', 'invoice' => null, 'amount' => $stu->total_monthly_fee];
                            $inv = $feeInfo['invoice'];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Student Info -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $stu->photo_url ?: 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($stu->first_name) }}" alt="{{ $stu->full_name }}" class="w-10 h-10 rounded-xl bg-amber-100 object-cover shrink-0">
                                    <div>
                                        <a href="{{ route('admin.students.show', $stu) }}" class="font-bold text-slate-900 hover:text-purple-900 block">
                                            {{ $stu->full_name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $stu->admission_number }}</span>
                                        <span class="block text-[11px] text-slate-500">{{ $stu->father_phone }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Program & Addons -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-purple-900 block">{{ $stu->program }} (Sec {{ $stu->section }})</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @if($stu->has_daycare)
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">Daycare</span>
                                    @endif
                                    @if($stu->has_transport)
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-sky-100 text-sky-800">Bus</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Fee Breakdown -->
                            <td class="py-3.5 px-4">
                                <span class="font-black text-slate-900 block font-heading text-sm">
                                    ₹{{ number_format($stu->total_monthly_fee, 2) }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">
                                    Tuition: ₹{{ number_format($stu->monthly_tuition_fee) }}
                                    @if($stu->has_daycare) + Daycare: ₹{{ number_format($stu->monthly_daycare_fee) }} @endif
                                    @if($stu->has_transport) + Bus: ₹{{ number_format($stu->monthly_transport_fee) }} @endif
                                    @if($stu->monthly_discount > 0) - Disc: ₹{{ number_format($stu->monthly_discount) }} @endif
                                </span>
                            </td>

                            <!-- Invoice info -->
                            <td class="py-3.5 px-4">
                                @if($inv)
                                    <a href="{{ route('admin.invoices.show', $inv) }}" class="font-mono font-bold text-purple-900 hover:underline block">
                                        {{ $inv->invoice_number }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block">Due: {{ $inv->due_date->format('d M Y') }}</span>
                                @else
                                    <span class="text-slate-400 text-xs italic">Not generated yet</span>
                                @endif
                            </td>

                            <!-- Flag / Status -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $feeInfo['badge'] }}">
                                    @if($feeInfo['status'] === 'paid')
                                        <i class="fa-solid fa-check-circle mr-0.5 text-emerald-600"></i> PAID
                                    @elseif($feeInfo['status'] === 'overdue')
                                        <i class="fa-solid fa-triangle-exclamation mr-0.5 text-rose-600"></i> OVERDUE
                                    @elseif($feeInfo['status'] === 'pending')
                                        <i class="fa-solid fa-clock mr-0.5 text-amber-600"></i> DUE / PENDING
                                    @else
                                        <i class="fa-solid fa-file-circle-question mr-0.5 text-slate-400"></i> UNBILLED
                                    @endif
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                @if($feeInfo['status'] === 'unbilled')
                                    <form action="{{ route('admin.billing.monthly.single', $stu) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                        <input type="hidden" name="year" value="{{ $selectedYear }}">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-[11px] shadow-2xs transition">
                                            <i class="fa-solid fa-plus text-[10px]"></i> Bill Now
                                        </button>
                                    </form>
                                @elseif($feeInfo['status'] === 'paid')
                                    <a href="{{ route('admin.invoices.receipt', $inv) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-purple-900 text-amber-300 font-bold text-[11px] hover:bg-purple-800 transition">
                                        <i class="fa-solid fa-receipt text-[10px]"></i> View Receipt
                                    </a>
                                @else
                                    <!-- Quick Record Payment Trigger -->
                                    <button type="button" onclick="openPayModal('{{ $inv->id }}', '{{ $inv->invoice_number }}', '{{ $stu->full_name }}', '{{ $inv->balance_due }}')" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-2xs transition">
                                        <i class="fa-solid fa-indian-rupee-sign text-[10px]"></i> Record Payment
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No students match the selected billing criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal 1: Bulk Generate Invoices Confirmation -->
<div id="bulk-generate-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border-4 border-amber-300">
        <button type="button" onclick="document.getElementById('bulk-generate-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-400 text-purple-950 font-bold text-2xl flex items-center justify-center shadow">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
                <h3 class="font-heading text-xl font-bold text-purple-950">Bulk Monthly Billing</h3>
                <p class="text-xs text-slate-500">Run billing for {{ $billingDate->format('F Y') }}</p>
            </div>
        </div>

        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
            This will automatically generate monthly fee invoices for all <strong>{{ $stats['unbilled_count'] }} unbilled active student(s)</strong> with their configured tuition, daycare, and transport charges. Students who have already been billed will be skipped safely.
        </p>

        <form action="{{ route('admin.billing.monthly.bulk') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="month" value="{{ $selectedMonth }}">
            <input type="hidden" name="year" value="{{ $selectedYear }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Set Payment Due Date</label>
                <select name="due_day" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                    <option value="10" selected>10th of {{ $billingDate->format('F Y') }}</option>
                    <option value="5">5th of {{ $billingDate->format('F Y') }}</option>
                    <option value="15">15th of {{ $billingDate->format('F Y') }}</option>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-extrabold text-xs shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-bolt"></i>
                    <span>Execute Monthly Billing Run</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Quick Record Payment Modal -->
<div id="quick-pay-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border-4 border-emerald-400">
        <button type="button" onclick="document.getElementById('quick-pay-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="font-heading text-xl font-bold text-emerald-950 mb-1">Record Fee Payment</h3>
        <p class="text-xs text-slate-500 mb-4" id="modal-invoice-subtitle">Settle invoice</p>

        <form id="quick-pay-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Amount Paid (₹) *</label>
                <input type="number" step="0.01" name="amount" id="modal-pay-amount" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Payment Method *</label>
                <select name="payment_method" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                    <option value="upi">UPI / GPay / PhonePe</option>
                    <option value="card">Credit / Debit Card</option>
                    <option value="cash">Counter Cash</option>
                    <option value="bank_transfer">NetBanking / NEFT</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Transaction Ref / Txn ID</label>
                    <input type="text" name="transaction_reference" placeholder="e.g. UPI/123456" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Confirm Payment & Update Status Flag to Paid</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPayModal(invoiceId, invoiceNumber, studentName, dueAmount) {
        document.getElementById('quick-pay-form').action = '/admin/billing/monthly/invoice/' + invoiceId + '/quick-pay';
        document.getElementById('modal-invoice-subtitle').innerText = 'Invoice #' + invoiceNumber + ' • Student: ' + studentName;
        document.getElementById('modal-pay-amount').value = dueAmount;
        document.getElementById('quick-pay-modal').classList.remove('hidden');
    }
</script>
@endsection
