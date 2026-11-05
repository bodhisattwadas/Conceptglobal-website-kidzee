@extends('layouts.admin')

@section('title', 'Admission Applications')
@section('page_title', '2. Admission Applications')
@section('page_subtitle', 'Review online admission submissions & approve enrollments')

@section('content')
<div class="space-y-6">

    <!-- Filters -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 p-1.5 bg-slate-200/70 rounded-2xl text-xs font-bold">
            <a href="{{ route('admin.admissions.index', ['status' => 'all', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ empty($status) || $status === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.admissions.index', ['status' => 'submitted', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'submitted' ? 'bg-purple-700 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Submitted ({{ $counts['submitted'] }})
            </a>
            <a href="{{ route('admin.admissions.index', ['status' => 'under_review', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'under_review' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Under Review ({{ $counts['under_review'] }})
            </a>
            <a href="{{ route('admin.admissions.index', ['status' => 'approved', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'approved' ? 'bg-sky-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Approved ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.admissions.index', ['status' => 'admitted', 'program' => $program, 'search' => $search]) }}" class="px-3 py-1.5 rounded-xl transition {{ $status === 'admitted' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Enrolled ({{ $counts['admitted'] }})
            </a>
        </div>

        <!-- Search & Program Filter -->
        <form action="{{ route('admin.admissions.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            @if($status) <input type="hidden" name="status" value="{{ $status }}"> @endif

            <select name="program" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs font-semibold text-slate-800 focus:outline-none">
                <option value="all">All Programs</option>
                <option value="Nuvo" {{ $program === 'Nuvo' ? 'selected' : '' }}>Nuvo</option>
                <option value="Playgroup" {{ $program === 'Playgroup' ? 'selected' : '' }}>Playgroup</option>
                <option value="Nursery" {{ $program === 'Nursery' ? 'selected' : '' }}>Nursery</option>
                <option value="Junior KG" {{ $program === 'Junior KG' ? 'selected' : '' }}>Junior KG</option>
                <option value="Senior KG" {{ $program === 'Senior KG' ? 'selected' : '' }}>Senior KG</option>
                <option value="Daycare" {{ $program === 'Daycare' ? 'selected' : '' }}>Day Care</option>
            </select>

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search child, app ID, parent..." class="pl-9 pr-3 py-1.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">
                Search
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">Application ID</th>
                        <th class="py-3.5 px-4">Child Details</th>
                        <th class="py-3.5 px-4">Program & Year</th>
                        <th class="py-3.5 px-4">Parent Contact</th>
                        <th class="py-3.5 px-4">Invoices & Dues</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $app)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-bold text-purple-900 whitespace-nowrap">
                                <a href="{{ route('admin.admissions.show', $app) }}" class="hover:underline">
                                    {{ $app->application_number }}
                                </a>
                                <span class="block text-[10px] text-slate-400 font-normal">{{ $app->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block text-sm">{{ $app->child_full_name }}</span>
                                <span class="text-[11px] text-slate-500">{{ $app->gender }} • DOB: {{ $app->dob->format('d M Y') }} (Blood: {{ $app->blood_group ?: 'N/A' }})</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-purple-900 block">{{ $app->program_applied }}</span>
                                <span class="text-[11px] text-slate-400">AY: {{ $app->academic_year }}</span>
                                @if($app->transport_required)
                                    <span class="inline-block text-[9px] px-1.5 py-0.5 rounded bg-sky-100 text-sky-800 font-bold mr-1">Bus</span>
                                @endif
                                @if($app->daycare_required)
                                    <span class="inline-block text-[9px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold">Daycare</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $app->father_name }}</span>
                                <span class="text-[11px] text-slate-500"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $app->father_phone }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($app->invoices->count() > 0)
                                    @php $paidAll = $app->invoices->every(fn($i) => $i->status === 'paid'); @endphp
                                    <span class="font-bold block {{ $paidAll ? 'text-emerald-600' : 'text-amber-600' }}">
                                        ₹{{ number_format($app->invoices->sum('total_amount')) }}
                                    </span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.2 rounded {{ $paidAll ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $paidAll ? 'Fee Paid' : 'Pending Payment' }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400">No invoice</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $app->status === 'admitted' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($app->status === 'approved' ? 'bg-sky-100 text-sky-800 border border-sky-300' : ($app->status === 'under_review' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-purple-100 text-purple-800 border border-purple-300')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('admin.admissions.show', $app) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]">
                                    Review
                                </a>
                                @if($app->status !== 'admitted')
                                    <form action="{{ route('admin.admissions.enroll', $app) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition shadow-2xs">
                                            <i class="fa-solid fa-user-plus text-[10px]"></i> Enroll Child
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.students.show', $app->student) }}" class="px-2.5 py-1 rounded-lg bg-purple-100 text-purple-900 font-bold text-[11px] hover:bg-purple-200">
                                        View Student →
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No admission applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $applications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
