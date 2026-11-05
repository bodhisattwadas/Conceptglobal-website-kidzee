@extends('layouts.admin')

@section('title', 'Queries & Inquiries')
@section('page_title', '1. Inquiries & Queries (Leads)')
@section('page_subtitle', 'Parent inquiries for November 20 Grand Opening & Admissions')

@section('content')
<div class="space-y-6">

    <!-- Source Insights Ribbon -->
    @if(!empty($sources))
        <div class="bg-gradient-to-r from-purple-900 to-indigo-950 p-4 rounded-3xl text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <span class="text-amber-400 font-bold uppercase tracking-wider text-[11px] block">Lead Discovery Channels</span>
                <h4 class="font-heading font-black text-base text-white">Where Parents Learn About Us</h4>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.inquiries.index', ['status' => $status]) }}" class="px-3 py-1 rounded-xl text-xs font-bold transition {{ empty($source) || $source === 'all' ? 'bg-amber-400 text-purple-950 shadow' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    All Channels
                </a>
                @foreach($sources as $sName => $sCount)
                    <a href="{{ route('admin.inquiries.index', ['status' => $status, 'source' => $sName]) }}" class="px-3 py-1 rounded-xl text-xs font-semibold transition {{ $source === $sName ? 'bg-amber-400 text-purple-950 shadow font-bold' : 'bg-white/10 text-white hover:bg-white/20' }}">
                        {{ $sName }}: <span class="font-bold">{{ $sCount }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 p-1.5 bg-slate-200/70 rounded-2xl text-xs font-bold">
            <a href="{{ route('admin.inquiries.index', ['status' => 'all', 'source' => $source, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ empty($status) || $status === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'new', 'source' => $source, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'new' ? 'bg-rose-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                New ({{ $counts['new'] }})
            </a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'contacted', 'source' => $source, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'contacted' ? 'bg-sky-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Contacted ({{ $counts['contacted'] }})
            </a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'campus_visit_scheduled', 'source' => $source, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'campus_visit_scheduled' ? 'bg-purple-700 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Visit Scheduled ({{ $counts['campus_visit_scheduled'] }})
            </a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'converted', 'source' => $source, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'converted' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Converted ({{ $counts['converted'] }})
            </a>
        </div>

        <!-- Search input -->
        <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex items-center gap-2">
            @if($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
            @if($source) <input type="hidden" name="source" value="{{ $source }}"> @endif
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search parent, child, source..." class="pl-9 pr-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                Filter
            </button>
            @if($search || $status || $source)
                <a href="{{ route('admin.inquiries.index') }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs hover:bg-slate-200">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Inquiries List Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">Inquiry Ref</th>
                        <th class="py-3.5 px-4">Parent Details</th>
                        <th class="py-3.5 px-4">Child & Program</th>
                        <th class="py-3.5 px-4">Learnt About Us</th>
                        <th class="py-3.5 px-4">Visit / Notes</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Convert / Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inq)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-bold text-purple-900 whitespace-nowrap">
                                <a href="{{ route('admin.inquiries.show', $inq) }}" class="hover:underline">
                                    {{ $inq->inquiry_number }}
                                </a>
                                <span class="block text-[10px] text-slate-400 font-normal">{{ $inq->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $inq->parent_name }}</span>
                                <span class="text-[11px] text-slate-500 block"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $inq->phone }}</span>
                                @if($inq->address)
                                    <span class="text-[10px] text-slate-400 block truncate max-w-[180px]" title="{{ $inq->address }}"><i class="fa-solid fa-location-dot text-[9px] mr-1"></i> {{ $inq->address }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $inq->child_name }}</span>
                                <span class="text-[11px] text-slate-500">{{ $inq->child_age ?: ($inq->child_dob ? $inq->child_dob->age . ' yrs' : 'Age not stated') }}</span>
                                @if($inq->child_gender)
                                    <span class="text-[10px] text-slate-400">({{ $inq->child_gender }})</span>
                                @endif
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-800 border border-purple-200">
                                    {{ $inq->program_interested }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($inq->referral_source)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                        <i class="fa-solid fa-bullhorn text-[9px] text-amber-600"></i>
                                        {{ $inq->referral_source }}
                                    </span>
                                    @if($inq->referral_details)
                                        <span class="block text-[10px] text-slate-500 mt-0.5 truncate max-w-[150px]" title="{{ $inq->referral_details }}">
                                            {{ $inq->referral_details }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-[11px]">Direct Walk-in</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                @if($inq->preferred_visit_date)
                                    <span class="font-bold text-purple-900 block text-[11px]">
                                        <i class="fa-solid fa-calendar-day mr-1 text-amber-500"></i> Visit: {{ $inq->preferred_visit_date->format('d M Y') }}
                                    </span>
                                @endif
                                <p class="text-[11px] text-slate-500 truncate" title="{{ $inq->message }}">{{ $inq->message ?: 'No message' }}</p>
                                @if($inq->counselor_notes)
                                    <p class="text-[10px] text-amber-700 font-semibold truncate mt-0.5" title="{{ $inq->counselor_notes }}">
                                        Note: {{ $inq->counselor_notes }}
                                    </p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.inquiries.status', $inq) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold px-2 py-1 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                                        <option value="new" {{ $inq->status === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="contacted" {{ $inq->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="campus_visit_scheduled" {{ $inq->status === 'campus_visit_scheduled' ? 'selected' : '' }}>Visit Scheduled</option>
                                        <option value="visited" {{ $inq->status === 'visited' ? 'selected' : '' }}>Visited</option>
                                        <option value="converted" {{ $inq->status === 'converted' ? 'selected' : '' }}>Converted</option>
                                        <option value="closed" {{ $inq->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1.5">
                                <a href="{{ route('admin.inquiries.show', $inq) }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]">
                                    Details
                                </a>
                                @if($inq->status === 'converted' && $inq->convertedStudent)
                                    <a href="{{ route('admin.students.show', $inq->convertedStudent) }}" class="px-2.5 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-[11px] hover:bg-emerald-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                        <span>Admitted: {{ $inq->convertedStudent->admission_number }}</span>
                                    </a>
                                @else
                                    <a href="{{ route('admin.inquiries.convertForm', $inq) }}" title="Convert Lead into Admitted Student & Configure Billing" class="px-3 py-1.5 rounded-xl bg-purple-900 text-amber-300 font-bold text-[11px] hover:bg-purple-800 transition shadow-sm inline-flex items-center gap-1">
                                        <i class="fa-solid fa-user-plus text-[10px]"></i>
                                        <span>Admit Student →</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No inquiries match the selected criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
