@extends('layouts.admin')

@section('title', 'Daily Student Attendance')
@section('page_title', 'Daily Student Attendance')
@section('page_subtitle', 'Mark and verify daily classroom attendance records')

@section('content')
<div class="space-y-6">

    <!-- Date & Program Filter Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <form action="{{ route('admin.attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Select Attendance Date</label>
                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Filter Program / Class</label>
                <select name="program" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-800 focus:outline-none bg-white">
                    <option value="all">All Programs</option>
                    <option value="Nuvo" {{ $program === 'Nuvo' ? 'selected' : '' }}>Nuvo</option>
                    <option value="Playgroup" {{ $program === 'Playgroup' ? 'selected' : '' }}>Playgroup</option>
                    <option value="Nursery" {{ $program === 'Nursery' ? 'selected' : '' }}>Nursery</option>
                    <option value="Junior KG" {{ $program === 'Junior KG' ? 'selected' : '' }}>Junior KG</option>
                    <option value="Senior KG" {{ $program === 'Senior KG' ? 'selected' : '' }}>Senior KG</option>
                </select>
            </div>
        </form>

        <div class="flex items-center gap-2">
            <button type="button" onclick="markAll('present')" class="px-3 py-1.5 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-800 text-xs font-bold transition">
                <i class="fa-solid fa-check-double mr-1"></i> Mark All Present
            </button>
        </div>
    </div>

    <!-- Attendance Form -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6">
        <form action="{{ route('admin.attendance.bulk') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <th class="py-3.5 px-4">Student</th>
                            <th class="py-3.5 px-4">Class / Section</th>
                            <th class="py-3.5 px-4">Attendance Status</th>
                            <th class="py-3.5 px-4">Teacher Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $stu)
                            @php
                                $att = $attendances[$stu->id] ?? null;
                                $statusVal = $att ? $att->status : 'present';
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $stu->photo_url ?: 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($stu->first_name) }}" alt="{{ $stu->full_name }}" class="w-10 h-10 rounded-xl bg-amber-100 object-cover">
                                        <div>
                                            <span class="font-bold text-slate-900 block">{{ $stu->full_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $stu->admission_number }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-purple-900 block">{{ $stu->program }}</span>
                                    <span class="text-[11px] text-slate-500">Section {{ $stu->section }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $stu->id }}][status]" value="present" {{ $statusVal === 'present' ? 'checked' : '' }} class="status-radio text-emerald-600">
                                            <span class="font-bold text-emerald-700">Present</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $stu->id }}][status]" value="absent" {{ $statusVal === 'absent' ? 'checked' : '' }} class="status-radio text-rose-600">
                                            <span class="font-bold text-rose-700">Absent</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $stu->id }}][status]" value="late" {{ $statusVal === 'late' ? 'checked' : '' }} class="status-radio text-amber-600">
                                            <span class="font-bold text-amber-700">Late</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $stu->id }}][status]" value="half_day" {{ $statusVal === 'half_day' ? 'checked' : '' }} class="status-radio text-purple-600">
                                            <span class="font-bold text-purple-700">Half Day</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <input type="text" name="attendance[{{ $stu->id }}][remarks]" value="{{ $att ? $att->remarks : '' }}" placeholder="Remarks (optional)" class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-1 focus:ring-purple-600">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">No active students in selected criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->count() > 0)
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">Attendance date: <strong>{{ date('d F Y', strtotime($date)) }}</strong></span>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-extrabold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Save Today's Attendance Sheet</span>
                    </button>
                </div>
            @endif
        </form>
    </div>

</div>

<script>
    function markAll(status) {
        document.querySelectorAll('.status-radio[value="' + status + '"]').forEach(function(radio) {
            radio.checked = true;
        });
    }
</script>
@endsection
