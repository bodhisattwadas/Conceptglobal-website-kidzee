@extends('layouts.admin')

@section('title', 'Inquiry Details: ' . $inquiry->inquiry_number)
@section('page_title', 'Inquiry ' . $inquiry->inquiry_number)
@section('page_subtitle', 'Parent lead details, referral attribution & conversion')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Inquiries
        </a>
        <div class="flex items-center gap-2">
            @if($inquiry->status === 'converted' && $inquiry->convertedStudent)
                <a href="{{ route('admin.students.show', $inquiry->convertedStudent) }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-user-check"></i> View Admitted Student ({{ $inquiry->convertedStudent->admission_number }})
                </a>
            @else
                <a href="{{ route('admin.inquiries.convertForm', $inquiry) }}" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-user-plus"></i> Admit & Convert Lead to Student →
                </a>
            @endif
            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50 text-xs">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Inquiry Details Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Inquiry Reference</span>
                <h2 class="text-2xl font-black text-slate-900">{{ $inquiry->inquiry_number }}</h2>
                <p class="text-xs text-slate-500">Received: {{ $inquiry->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $inquiry->status === 'new' ? 'bg-rose-100 text-rose-700' : ($inquiry->status === 'converted' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800') }}">
                    Status: {{ ucfirst(str_replace('_', ' ', $inquiry->status)) }}
                </span>
                @if($inquiry->status !== 'converted')
                    <a href="{{ route('admin.inquiries.convertForm', $inquiry) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-400 text-purple-950 hover:bg-amber-300 transition">
                        Convert Lead Now
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Parent & Referral info -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2.5">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Parent Information</span>
                <p class="text-base font-bold text-slate-900">{{ $inquiry->parent_name }}</p>
                <p class="text-xs text-slate-700"><i class="fa-solid fa-phone text-[10px] mr-1 text-slate-400"></i> {{ $inquiry->phone }}</p>
                @if($inquiry->email)
                    <p class="text-xs text-slate-700"><i class="fa-solid fa-envelope text-[10px] mr-1 text-slate-400"></i> {{ $inquiry->email }}</p>
                @endif
                @if($inquiry->address)
                    <p class="text-xs text-slate-700"><i class="fa-solid fa-location-dot text-[10px] mr-1 text-slate-400"></i> {{ $inquiry->address }} {{ $inquiry->city ? ', ' . $inquiry->city : '' }} {{ $inquiry->pincode ? '- ' . $inquiry->pincode : '' }}</p>
                @endif
                <p class="text-xs text-slate-500"><i class="fa-solid fa-building text-[10px] mr-1 text-slate-400"></i> Preferred Campus: {{ $inquiry->branch }}</p>

                <!-- Source / Learned from -->
                <div class="pt-2 border-t border-slate-200/80">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Learnt About Kidzee Via:</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 mt-1 rounded-xl text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                        <i class="fa-solid fa-bullhorn text-amber-600 text-[10px]"></i>
                        {{ $inquiry->referral_source ?: 'Direct / Walk-in' }}
                    </span>
                    @if($inquiry->referral_details)
                        <p class="text-xs text-slate-600 mt-1 font-medium italic">"{{ $inquiry->referral_details }}"</p>
                    @endif
                </div>
            </div>

            <!-- Child Info -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2.5">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Child & Academic Program</span>
                <p class="text-base font-bold text-purple-900">{{ $inquiry->child_name }}</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase">Age</span>
                        <span class="font-semibold text-slate-800">{{ $inquiry->child_age ?: ($inquiry->child_dob ? $inquiry->child_dob->age . ' yrs' : 'Not specified') }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase">Gender</span>
                        <span class="font-semibold text-slate-800">{{ $inquiry->child_gender ?: 'Not stated' }}</span>
                    </div>
                    @if($inquiry->child_dob)
                        <div class="col-span-2">
                            <span class="text-slate-400 block text-[10px] uppercase">Date of Birth</span>
                            <span class="font-semibold text-slate-800">{{ $inquiry->child_dob->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
                <div class="pt-2 border-t border-slate-200/80">
                    <span class="text-slate-400 block text-[10px] uppercase">Interested Program</span>
                    <span class="inline-block px-2.5 py-1 mt-1 rounded-lg text-xs font-bold bg-purple-100 text-purple-900 border border-purple-200">
                        {{ $inquiry->program_interested }}
                    </span>
                </div>
                @if($inquiry->preferred_visit_date)
                    <p class="text-xs font-bold text-purple-900 pt-1">
                        <i class="fa-solid fa-calendar-check mr-1 text-amber-500"></i> Scheduled Visit: {{ $inquiry->preferred_visit_date->format('d M Y') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Parent's message -->
        <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200">
            <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider block mb-1">Parent's Message / Specific Inquiry</span>
            <p class="text-xs text-slate-800 leading-relaxed">{{ $inquiry->message ?: 'No additional message provided.' }}</p>
        </div>

        <!-- Counselor Follow-Up Update Form -->
        <div class="pt-4 border-t border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm mb-3">Update Follow-Up & Counselor Notes</h3>
            <form action="{{ route('admin.inquiries.status', $inquiry) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lead Status</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                            <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New Lead</option>
                            <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>Contacted / Spoke to Parent</option>
                            <option value="campus_visit_scheduled" {{ $inquiry->status === 'campus_visit_scheduled' ? 'selected' : '' }}>Campus Visit Scheduled</option>
                            <option value="visited" {{ $inquiry->status === 'visited' ? 'selected' : '' }}>Visited Campus</option>
                            <option value="converted" {{ $inquiry->status === 'converted' ? 'selected' : '' }}>Converted to Admitted Student</option>
                            <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Closed / Not Interested</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Schedule / Reschedule Campus Visit</label>
                        <input type="date" name="preferred_visit_date" value="{{ $inquiry->preferred_visit_date ? $inquiry->preferred_visit_date->format('Y-m-d') : '' }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Counselor Discussion Notes</label>
                    <textarea name="counselor_notes" rows="3" placeholder="Enter notes from parent call, discussion points, batch preference..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600">{{ $inquiry->counselor_notes }}</textarea>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition">
                        Save Notes & Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
