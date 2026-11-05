@extends('layouts.admin')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('page_title', 'Invoice ' . $invoice->invoice_number)
@section('page_subtitle', 'Fee statement & payment collection details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.invoices.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Invoices
        </a>
        <div class="flex items-center gap-2">
            @if($invoice->status === 'paid')
                <a href="{{ route('admin.invoices.receipt', $invoice) }}" target="_blank" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Official Receipt</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Invoice Statement</span>
                <h2 class="text-2xl font-black text-slate-900">{{ $invoice->invoice_number }}</h2>
                <p class="text-xs text-slate-500">Created: {{ $invoice->created_at->format('d M Y') }} • Due Date: {{ $invoice->due_date->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                    {{ ucfirst($invoice->status) }}
                </span>
                @if($invoice->paid_at)
                    <p class="text-[10px] text-slate-400 mt-1">Paid on: {{ $invoice->paid_at->format('d M Y, h:i A') }}</p>
                @endif
            </div>
        </div>

        <!-- Particulars & Student Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase block">Parent Details</span>
                <p class="text-sm font-bold text-slate-900">{{ $invoice->parent_name }}</p>
                <p class="text-xs text-slate-600"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $invoice->parent_phone }}</p>
                @if($invoice->parent_email)
                    <p class="text-xs text-slate-600"><i class="fa-solid fa-envelope text-[9px] mr-1"></i> {{ $invoice->parent_email }}</p>
                @endif
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase block">Student / Application Link</span>
                @if($invoice->student)
                    <p class="text-sm font-bold text-purple-900">{{ $invoice->student->full_name }}</p>
                    <p class="text-xs text-slate-600">Admission No: {{ $invoice->student->admission_number }} • Program: {{ $invoice->student->program }}</p>
                    <a href="{{ route('admin.students.show', $invoice->student) }}" class="text-[11px] font-bold text-purple-700 hover:underline inline-block mt-1">
                        View Student Dossier →
                    </a>
                @elseif($invoice->application)
                    <p class="text-sm font-bold text-purple-900">{{ $invoice->application->child_full_name }}</p>
                    <p class="text-xs text-slate-600">App No: {{ $invoice->application->application_number }} • Program: {{ $invoice->application->program_applied }}</p>
                    <a href="{{ route('admin.admissions.show', $invoice->application) }}" class="text-[11px] font-bold text-purple-700 hover:underline inline-block mt-1">
                        View Application Dossier →
                    </a>
                @else
                    <p class="text-xs text-slate-400">Direct Invoice</p>
                @endif
            </div>
        </div>

        <!-- Line item breakdown -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-xs font-bold text-slate-700 uppercase tracking-wider">
                <span>Fee Particular</span>
                <span>Amount</span>
            </div>
            <div class="p-4 flex items-center justify-between text-xs border-b border-slate-100">
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ $invoice->title }}</h4>
                    <p class="text-slate-500">{{ $invoice->notes ?: 'General preschool fee transaction.' }}</p>
                </div>
                <span class="font-bold text-sm text-slate-900">₹{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->discount > 0)
                <div class="p-3 px-4 flex items-center justify-between text-xs text-emerald-700 border-b border-slate-100 bg-emerald-50/20">
                    <span>Opening Concession / Early Bird Discount</span>
                    <span class="font-bold">- ₹{{ number_format($invoice->discount, 2) }}</span>
                </div>
            @endif
            <div class="p-4 flex items-center justify-between bg-slate-50/60 font-black text-slate-900 text-base">
                <span>Total Net Payable</span>
                <span class="text-purple-950 font-heading text-lg">₹{{ number_format($invoice->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Payment History -->
        <div>
            <h3 class="font-heading text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-amber-500"></i> Payment Transactions Recorded
            </h3>

            @forelse($invoice->payments as $pay)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs mb-2">
                    <div>
                        <span class="font-bold text-purple-900">{{ $pay->payment_reference }}</span>
                        <span class="text-slate-500 ml-2">via {{ strtoupper($pay->payment_method) }}</span>
                        @if($pay->transaction_reference)
                            <span class="text-slate-400 block text-[10px]">Ref: {{ $pay->transaction_reference }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="font-black text-emerald-600 block text-sm">₹{{ number_format($pay->amount, 2) }}</span>
                        <span class="text-[10px] text-slate-400">{{ $pay->payment_date->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-2">No payment transactions recorded yet.</p>
            @endforelse
        </div>

        <!-- Record Payment Box (if balance due) -->
        @if($invoice->balance_due > 0)
            <div class="p-6 rounded-3xl bg-amber-50/50 border-2 border-amber-300">
                <div class="flex items-center justify-between pb-3 border-b border-amber-200 mb-4">
                    <h4 class="font-heading text-base font-bold text-purple-950">Record Fee Payment</h4>
                    <span class="text-xs font-bold text-rose-600">Balance Due: ₹{{ number_format($invoice->balance_due, 2) }}</span>
                </div>

                <form action="{{ route('admin.invoices.payment', $invoice) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Amount to Pay (₹) *</label>
                            <input type="number" step="0.01" name="amount" value="{{ $invoice->balance_due }}" max="{{ $invoice->balance_due }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Payment Method *</label>
                            <select name="payment_method" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                                <option value="upi">UPI / GPay / PhonePe</option>
                                <option value="card">Credit / Debit Card</option>
                                <option value="cash">Counter Cash Payment</option>
                                <option value="bank_transfer">Direct Bank Transfer (NEFT/IMPS)</option>
                                <option value="cheque">Bank Cheque</option>
                                <option value="online">Online Payment Gateway</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Payment Date *</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Transaction Ref / Cheque No.</label>
                            <input type="text" name="transaction_reference" placeholder="e.g. UPI/3948293847 or CHQ #120934" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Remarks</label>
                            <input type="text" name="notes" placeholder="e.g. Received at reception counter" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                        </div>
                    </div>

                    <div class="flex justify-end pt-1">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-check"></i>
                            <span>Confirm & Record Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

</div>
@endsection
