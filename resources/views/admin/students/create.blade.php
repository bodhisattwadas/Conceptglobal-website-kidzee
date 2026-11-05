@extends('layouts.admin')

@section('title', 'Direct Student Enrollment')
@section('page_title', 'Enroll New Student')
@section('page_subtitle', 'Add student directly to Kidzee database')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Student Directory
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Child Info -->
            <div>
                <h3 class="font-heading text-lg font-bold text-purple-950 mb-4 pb-2 border-b border-slate-100">
                    Child's Personal Data
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Gender *</label>
                        <select name="gender" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth *</label>
                        <input type="date" name="dob" value="{{ old('dob') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Blood Group</label>
                        <input type="text" name="blood_group" value="{{ old('blood_group') }}" placeholder="e.g. B+" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Admission Date *</label>
                        <input type="date" name="admission_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program *</label>
                        <select name="program" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                            <option value="Nuvo">Nuvo (1.5 - 2 Yrs)</option>
                            <option value="Playgroup">Playgroup (2 - 3 Yrs)</option>
                            <option value="Nursery" selected>Nursery (3 - 4 Yrs)</option>
                            <option value="Junior KG">Junior KG (4 - 5 Yrs)</option>
                            <option value="Senior KG">Senior KG (5 - 6 Yrs)</option>
                            <option value="Daycare">Day Care & Activity Club</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Section</label>
                        <input type="text" name="section" value="A" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>
            </div>

            <!-- Parents Info -->
            <div>
                <h3 class="font-heading text-lg font-bold text-purple-950 mb-4 pb-2 border-b border-slate-100">
                    Parents & Emergency Contact Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Name *</label>
                        <input type="text" name="father_name" required value="{{ old('father_name') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Phone *</label>
                        <input type="tel" name="father_phone" required value="{{ old('father_phone') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Email</label>
                        <input type="email" name="father_email" value="{{ old('father_email') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Name</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Phone</label>
                        <input type="tel" name="mother_phone" value="{{ old('mother_phone') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Email</label>
                        <input type="email" name="mother_email" value="{{ old('mother_email') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Residential Address *</label>
                    <textarea name="address" required rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Contact Person</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Contact Phone</label>
                        <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>
            </div>

            <!-- Medical Notes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Medical Notes & Allergies</label>
                <textarea name="medical_notes" rows="2" placeholder="Any allergy or medical details..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"></textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-8 py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-extrabold text-sm shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Confirm Enrollment & Generate Admission ID</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
