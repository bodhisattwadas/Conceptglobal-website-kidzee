@extends('layouts.admin')

@section('title', 'Edit Student: ' . $student->full_name)
@section('page_title', 'Edit Student Record')
@section('page_subtitle', $student->admission_number . ' — ' . $student->full_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.students.show', $student) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Student Profile
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Gender *</label>
                    <select name="gender" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $student->gender == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth *</label>
                    <input type="date" name="dob" value="{{ old('dob', $student->dob->format('Y-m-d')) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Blood Group</label>
                    <input type="text" name="blood_group" value="{{ old('blood_group', $student->blood_group) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ $student->status == 'graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="transferred" {{ $student->status == 'transferred' ? 'selected' : '' }}>Transferred</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program *</label>
                    <select name="program" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="Nuvo" {{ $student->program == 'Nuvo' ? 'selected' : '' }}>Nuvo</option>
                        <option value="Playgroup" {{ $student->program == 'Playgroup' ? 'selected' : '' }}>Playgroup</option>
                        <option value="Nursery" {{ $student->program == 'Nursery' ? 'selected' : '' }}>Nursery</option>
                        <option value="Junior KG" {{ $student->program == 'Junior KG' ? 'selected' : '' }}>Junior KG</option>
                        <option value="Senior KG" {{ $student->program == 'Senior KG' ? 'selected' : '' }}>Senior KG</option>
                        <option value="Daycare" {{ $student->program == 'Daycare' ? 'selected' : '' }}>Day Care</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Section</label>
                    <input type="text" name="section" value="{{ old('section', $student->section) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>

            <!-- Parents Contact -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Name *</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Phone *</label>
                    <input type="tel" name="father_phone" value="{{ old('father_phone', $student->father_phone) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Email</label>
                    <input type="email" name="father_email" value="{{ old('father_email', $student->father_email) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Address *</label>
                <textarea name="address" required rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Contact Person</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Contact Phone</label>
                    <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Medical Conditions / Allergies</label>
                <textarea name="medical_notes" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">{{ old('medical_notes', $student->medical_notes) }}</textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
