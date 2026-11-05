@extends('layouts.app')

@section('title', 'Online Admission Application | Kidzee Preschool 2026-2027')

@section('content')
<div class="py-12 bg-amber-50/40 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-900 text-xs font-bold uppercase tracking-wider mb-2">
                Official Admission Form 2026-2027
            </span>
            <h1 class="font-heading text-3xl sm:text-4xl font-extrabold text-slate-900">
                Kidzee Student Admission Application
            </h1>
            <p class="text-slate-600 text-sm mt-2 max-w-xl mx-auto">
                Secure your child's admission for the November 20 inauguration batch. Fill in the details below to receive your immediate application registration ID and fee invoice.
            </p>
        </div>

        <!-- Early Bird Banner -->
        <div class="mb-8 p-4 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-300 text-purple-950 font-bold flex flex-col sm:flex-row items-center justify-between gap-3 shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-900 text-amber-300 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-gift text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black">November 20 Grand Opening Privilege</h3>
                    <p class="text-xs text-purple-900/80">Flat ₹1,500 concession automatically deducted from registration & kit invoice!</p>
                </div>
            </div>
            <span class="px-3 py-1 bg-purple-900 text-amber-300 text-xs font-black rounded-full shrink-0">
                Auto-Applied
            </span>
        </div>

        <!-- Application Form -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border-4 border-amber-300">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900">
                    <h4 class="font-bold text-sm mb-1">Please correct the following errors:</h4>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admissions.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Section 1: Child Information -->
                <div>
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-4">
                        <span class="w-7 h-7 rounded-full bg-purple-900 text-amber-300 font-bold text-xs flex items-center justify-center">1</span>
                        <h2 class="font-heading text-xl font-bold text-purple-950">Child's Personal Details</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">First Name *</label>
                            <input type="text" name="child_first_name" value="{{ old('child_first_name') }}" required placeholder="e.g. Aarav" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Last Name *</label>
                            <input type="text" name="child_last_name" value="{{ old('child_last_name') }}" required placeholder="e.g. Sharma" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Gender *</label>
                            <select name="gender" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm bg-white">
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth *</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Blood Group</label>
                            <select name="blood_group" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm bg-white">
                                <option value="">Unknown / To Be Tested</option>
                                <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program Applied *</label>
                            <select name="program_applied" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm bg-white">
                                @php $selectedProg = request('program') ?? old('program_applied'); @endphp
                                <option value="Playgroup" {{ $selectedProg == 'Playgroup' ? 'selected' : '' }}>Playgroup (2 – 3 Years)</option>
                                <option value="Nursery" {{ $selectedProg == 'Nursery' ? 'selected' : '' }}>Nursery (3 – 4 Years)</option>
                                <option value="Junior KG" {{ $selectedProg == 'Junior KG' ? 'selected' : '' }}>Junior KG (4 – 5 Years)</option>
                                <option value="Senior KG" {{ $selectedProg == 'Senior KG' ? 'selected' : '' }}>Senior KG (5 – 6 Years)</option>
                                <option value="Nuvo" {{ $selectedProg == 'Nuvo' ? 'selected' : '' }}>Nuvo (1.5 – 2 Years)</option>
                                <option value="Daycare" {{ $selectedProg == 'Day Care & Activity Club' || $selectedProg == 'Daycare' ? 'selected' : '' }}>Day Care & Activity Club</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Academic Year</label>
                            <input type="text" name="academic_year" value="2026-2027" readonly class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Parent / Guardian Information -->
                <div>
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-4">
                        <span class="w-7 h-7 rounded-full bg-purple-900 text-amber-300 font-bold text-xs flex items-center justify-center">2</span>
                        <h2 class="font-heading text-xl font-bold text-purple-950">Parents / Guardian Details</h2>
                    </div>

                    <!-- Father -->
                    <h3 class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-2">Father / Primary Guardian Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Full Name *</label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" required placeholder="e.g. Vikram Mehra" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Primary Mobile Phone *</label>
                            <input type="tel" name="father_phone" value="{{ old('father_phone') }}" required placeholder="+91 98450 12345" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's Email</label>
                            <input type="email" name="father_email" value="{{ old('father_email') }}" placeholder="father@example.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation / Organization</label>
                            <input type="text" name="father_occupation" value="{{ old('father_occupation') }}" placeholder="e.g. Software Engineer, Amazon" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                    </div>

                    <!-- Mother -->
                    <h3 class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-2">Mother Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Full Name</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name') }}" placeholder="e.g. Radhika Mehra" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Mobile Phone</label>
                            <input type="tel" name="mother_phone" value="{{ old('mother_phone') }}" placeholder="+91 98450 67890" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Email</label>
                            <input type="email" name="mother_email" value="{{ old('mother_email') }}" placeholder="mother@example.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation</label>
                            <input type="text" name="mother_occupation" value="{{ old('mother_occupation') }}" placeholder="e.g. Architect" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Residential Address -->
                <div>
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-4">
                        <span class="w-7 h-7 rounded-full bg-purple-900 text-amber-300 font-bold text-xs flex items-center justify-center">3</span>
                        <h2 class="font-heading text-xl font-bold text-purple-950">Residential Address & Emergency Contact</h2>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Residential Address *</label>
                        <textarea name="address" required rows="2" placeholder="House / Flat No., Apartment / Society name, Street / Area" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">City *</label>
                            <input type="text" name="city" value="{{ old('city', 'Bengaluru') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Postal Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" placeholder="e.g. 560103" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Contact Person Name</label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Grandparent or relative name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Contact Phone</label>
                            <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="+91 99999 88888" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Health & Facilities -->
                <div>
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-4">
                        <span class="w-7 h-7 rounded-full bg-purple-900 text-amber-300 font-bold text-xs flex items-center justify-center">4</span>
                        <h2 class="font-heading text-xl font-bold text-purple-950">Medical Information & Add-On Services</h2>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Known Allergies / Medical Notes</label>
                        <textarea name="medical_conditions" rows="2" placeholder="e.g. Peanut allergy, asthma inhaler, or none" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm">{{ old('medical_conditions') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-amber-50/50 border border-amber-200">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="transport_required" value="1" {{ old('transport_required') ? 'checked' : '' }} class="mt-1 w-4 h-4 text-purple-900 rounded border-slate-300 focus:ring-amber-400">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">Opt for GPS Safe Bus Transport</span>
                                <span class="block text-[11px] text-slate-500">Doorstep pickup & drop with female caregiver.</span>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="daycare_required" value="1" {{ old('daycare_required') ? 'checked' : '' }} class="mt-1 w-4 h-4 text-purple-900 rounded border-slate-300 focus:ring-amber-400">
                            <div>
                                <span class="block text-xs font-bold text-slate-900">Opt for Extended Daycare</span>
                                <span class="block text-[11px] text-slate-500">Half or full-day care with hot nutritious snacks.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Fee Billing Summary -->
                <div class="p-5 rounded-2xl bg-purple-950 text-white">
                    <h3 class="font-heading text-lg font-bold text-amber-300 mb-2">Admission Registration Summary</h3>
                    <div class="flex items-center justify-between text-xs py-1 border-b border-purple-900">
                        <span>Admission & Pentamind Kit Fee:</span>
                        <span class="font-bold">₹8,000.00</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-1 border-b border-purple-900 text-amber-300">
                        <span>Nov 20 Opening Early Bird Waiver:</span>
                        <span class="font-bold">- ₹1,500.00</span>
                    </div>
                    <div class="flex items-center justify-between text-sm pt-2 font-bold">
                        <span>Net Payable on Confirmation:</span>
                        <span class="text-amber-400 text-lg">₹6,500.00</span>
                    </div>
                    <p class="text-[11px] text-purple-300 mt-2">
                        * A digital invoice will be generated instantly upon submitting this application. You can pay online or at the campus counter.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Cancel & Return Home
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-purple-950 font-heading font-black text-lg shadow-xl hover:shadow-2xl transition transform hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <span>Submit Application & Generate Invoice</span>
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
