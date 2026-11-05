@extends('layouts.admin')

@section('title', 'Billing & Invoices')
@section('page_title', 'Billing & Fee Invoices')
@section('page_subtitle', 'Fee collection, daycare billing, and receipts register')

@section('content')
<div class="space-y-6">

    <!-- Financial Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Billed</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block">₹{{ number_format($stats['total_billed'], 2) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Collected</span>
            <span class="text-2xl font-black text-emerald-600 mt-1 block">₹{{ number_format($stats['total_collected'], 2) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Paid Invoices</span>
            <span class="text-2xl font-black text-purple-900 mt-1 block">{{ $stats['paid_count'] }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pending / Due</span>
            <span class="text-2xl font-black text-amber-600 mt-1 block">{{ $stats['pending_count'] }}</span>
        </div>
    </div>

    <!-- Filters & Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 p-1.5 bg-slate-200/70 rounded-2xl text-xs font-bold">
            <a href="{{ route('admin.invoices.index', ['status' => 'all', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ empty($status) || $status === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                All
            </a>
            <a href="{{ route('admin.invoices.index', ['status' => 'paid', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'paid' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Paid
            </a>
            <a href="{{ route('admin.invoices.index', ['status' => 'pending', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Pending
            </a>
            <a href="{{ route('admin.invoices.index', ['status' => 'partially_paid', 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'partially_paid' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Partial
            </a>
        </div>

        <div class="flex items-center gap-2">
            <form action="{{ route('admin.invoices.index') }}" method="GET" class="flex items-center gap-2">
                @if($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search invoice, parent..." class="pl-9 pr-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </form>

            <a href="{{ route('admin.invoices.create') }}" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i>
                <span>Create Invoice</span>
            </a>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">Invoice #</th>
                        <th class="py-3.5 px-4">Parent & Contact</th>
                        <th class="py-3.5 px-4">Student / Linked App</th>
                        <th class="py-3.5 px-4">Invoice Title & Type</th>
                        <th class="py-3.5 px-4">Total Amount</th>
                        <th class="py-3.5 px-4">Paid / Due</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-bold text-purple-900 whitespace-nowrap">
                                <a href="{{ route('admin.invoices.show', $inv) }}" class="hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                                <span class="block text-[10px] text-slate-400 font-normal">Due: {{ $inv->due_date->format('d M Y') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $inv->parent_name }}</span>
                                <span class="text-[11px] text-slate-500">{{ $inv->parent_phone }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($inv->student)
                                    <a href="{{ route('admin.students.show', $inv->student) }}" class="font-bold text-slate-900 hover:text-purple-800 block">
                                        {{ $inv->student->full_name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400">Roll: {{ $inv->student->roll_number ?: $inv->student->admission_number }}</span>
                                @elseif($inv->application)
                                    <a href="{{ route('admin.admissions.show', $inv->application) }}" class="font-bold text-purple-900 hover:underline block">
                                        {{ $inv->application->child_full_name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400">App: {{ $inv->application->application_number }}</span>
                                @else
                                    <span class="text-slate-400">Direct Invoice</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block truncate max-w-xs">{{ $inv->title }}</span>
                                <span class="text-[10px] uppercase font-bold px-1.5 py-0.2 rounded bg-slate-100 text-slate-600">
                                    {{ ucfirst($inv->invoice_type) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-heading font-black text-sm text-slate-900">
                                ₹{{ number_format($inv->total_amount, 2) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-emerald-600 block">Paid: ₹{{ number_format($inv->paid_amount, 2) }}</span>
                                @if($inv->balance_due > 0)
                                    <span class="text-[11px] font-bold text-rose-600">Due: ₹{{ number_format($inv->balance_due, 2) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                                    {{ ucfirst($inv->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('admin.invoices.show', $inv) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]">
                                    View
                                </a>
                                @if($inv->status === 'paid')
                                    <a href="{{ route('admin.invoices.receipt', $inv) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-purple-900 text-amber-300 font-bold text-[11px] hover:bg-purple-800">
                                        <i class="fa-solid fa-receipt text-[10px]"></i> Receipt
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">No invoices match your selection.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
