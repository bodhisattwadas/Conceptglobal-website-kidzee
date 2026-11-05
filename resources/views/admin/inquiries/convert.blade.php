@extends('layouts.admin')

@section('title', 'Convert Lead to Student: ' . $inquiry->inquiry_number)
@section('page_title', 'Convert Lead to Student')
@section('page_subtitle', 'Admit applicant & configure monthly fee billing eligibility')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Inquiry {{ $inquiry->inquiry_number }}
        </a>
    </div>

    <!-- Lead Info Banner -->
    <div class="p-5 rounded-3xl bg-gradient-to-r from-purple-900 to-indigo-950 text-white shadow-md flex items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-amber-300 uppercase tracking-wider block">Lead Conversion Workflow</span>
            <h2 class="text-xl font-extrabold mt-0.5">Admitting Child: {{ $inquiry->child_name }}</h2>
            <p class="text-xs text-purple-200">
                Inquiry Ref: <strong>{{ $inquiry->inquiry_number }}</strong> • Parent: <strong>{{ $inquiry->parent_name }}</strong> ({{ $inquiry->phone }})
            </p>
        </div>
        <div class="text-right">
            <span class="inline-block px-3 py-1 rounded-full bg-amber-400 text-purple-950 font-bold text-xs">
                Source: {{ $inquiry->referral_source ?: 'Direct' }}
            </span>
        </div>
    </div>

    <!-- Admission & Billing Configuration Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        @php
            $nameParts = explode(' ', trim($inquiry->child_name));
            $firstName = $nameParts[0];
            $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'Kid';
        @endphp

        <form action="{{ route('admin.inquiries.convertToStudent', $inquiry) }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: Child Profile -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-4">
                    <span class="w-7 h-7 rounded-full bg-purple-900 text-amber-300 font-bold text-xs flex items-center justify-center">1</span>
                    <h3 class="font-heading text-lg font-bold text-purple-950">Student Personal Details</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Gender *</label>
                        <select name="gender" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                            <option value="Male" {{ ($inquiry->child_gender == 'Male') ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ ($inquiry->child_gender == 'Female') ? 'selected' : '' }}>Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth *</label>
                        <input type="date" name="dob" value="{{ old('dob', $inquiry->child_dob ? $inquiry->child_dob->format('Y-m-d') : date('Y-m-d', strtotime('-3 years'))) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Blood Group</label>
                        <input type="text" name="blood_group" placeholder="e.g. B+" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Admission Date *</label>
                        <input type="date" name="admission_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Program / Grade *</label>
                        <select name="program" id="program_select" onchange="updateDefaultTuition(this)" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                            <option value="Nuvo" {{ $inquiry->program_interested == 'Nuvo' ? 'selected' : '' }} data-fee="5500">Nuvo (1.5 - 2 Yrs)</option>
                            <option value="Playgroup" {{ $inquiry->program_interested == 'Playgroup' ? 'selected' : '' }} data-fee="6000">Playgroup (2 - 3 Yrs)</option>
                            <option value="Nursery" {{ $inquiry->program_interested == 'Nursery' ? 'selected' : '' }} data-fee="6500">Nursery (3 - 4 Yrs)</option>
                            <option value="Junior KG" {{ $inquiry->program_interested == 'Junior KG' ? 'selected' : '' }} data-fee="7000">Junior KG (4 - 5 Yrs)</option>
                            <option value="Senior KG" {{ $inquiry->program_interested == 'Senior KG' ? 'selected' : '' }} data-fee="7500">Senior KG (5 - 6 Yrs)</option>
                            <option value="Daycare" {{ $inquiry->program_interested == 'Daycare' ? 'selected' : '' }} data-fee="12500">Day Care & Club</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Class Section</label>
                        <input type="text" name="section" value="A" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>
            </div>

            <!-- Section 2: Parents Details -->
            <div>
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-4">
                    <span class="w-7 h-7 rounded-full bg-purple-900 text-amber-300 font-bold text-xs flex items-center justify-center">2</span>
                    <h3 class="font-heading text-lg font-bold text-purple-950">Parent & Contact Details</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Father's / Guardian Name *</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $inquiry->parent_name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone *</label>
                        <input type="tel" name="father_phone" value="{{ old('father_phone', $inquiry->phone) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="father_email" value="{{ old('father_email', $inquiry->email) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Name</label>
                        <input type="text" name="mother_name" placeholder="Mother's full name" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mother's Phone</label>
                        <input type="tel" name="mother_phone" placeholder="+91 ..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Residential Address *</label>
                    <textarea name="address" required rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">{{ old('address', $inquiry->address ?: 'Bengaluru') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Contact Person</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $inquiry->parent_name) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Emergency Phone Number</label>
                        <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $inquiry->phone) }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>
            </div>

            <!-- Section 3: Monthly Billing & Fee Eligibility Setup -->
            <div class="p-6 rounded-3xl bg-amber-50/60 border-2 border-amber-300 space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-amber-200">
                    <span class="w-7 h-7 rounded-full bg-purple-900 text-amber-300 font-bold text-xs flex items-center justify-center">3</span>
                    <h3 class="font-heading text-lg font-bold text-purple-950">Monthly Billing & Recurring Fee Setup</h3>
                </div>
                <p class="text-xs text-slate-600">
                    This student will be registered into the monthly billing engine. Invoices can be generated at each month-end.
                </p>

                <!-- Base Tuition Fee -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">Monthly Tuition Fee (₹) *</label>
                        <input type="number" step="0.01" name="monthly_tuition_fee" id="tuition_input" value="{{ old('monthly_tuition_fee', $suggestedTuition) }}" required oninput="calcNet()" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">Billing Due Day of Each Month *</label>
                        <select name="billing_cycle_day" class="w-full px-3 py-2 rounded-xl border border-slate-300 bg-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                            <option value="5" selected>5th of every month</option>
                            <option value="1">1st of every month</option>
                            <option value="10">10th of every month</option>
                        </select>
                    </div>
                </div>

                <!-- Add-on Services: Daycare & Transport -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-3.5 rounded-2xl bg-white border border-amber-200 space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="has_daycare" id="has_daycare" value="1" onchange="toggleDaycare(this); calcNet()" class="w-4 h-4 rounded text-purple-900">
                            <span class="text-xs font-bold text-slate-900">Include Extended Daycare</span>
                        </label>
                        <div id="daycare_amount_box" class="hidden">
                            <label class="block text-[11px] text-slate-500 font-semibold mb-0.5">Monthly Daycare Charge (₹)</label>
                            <input type="number" step="0.01" name="monthly_daycare_fee" id="daycare_input" value="6000" oninput="calcNet()" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-300 text-xs font-bold">
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-white border border-amber-200 space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="has_transport" id="has_transport" value="1" onchange="toggleTransport(this); calcNet()" class="w-4 h-4 rounded text-purple-900">
                            <span class="text-xs font-bold text-slate-900">Include GPS Van Transport</span>
                        </label>
                        <div id="transport_amount_box" class="hidden">
                            <label class="block text-[11px] text-slate-500 font-semibold mb-0.5">Monthly Transport Charge (₹)</label>
                            <input type="number" step="0.01" name="monthly_transport_fee" id="transport_input" value="3200" oninput="calcNet()" class="w-full px-2.5 py-1.5 rounded-xl border border-slate-300 text-xs font-bold">
                        </div>
                    </div>
                </div>

                <!-- Monthly Concession / Discount -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">Monthly Concession / Sibling Discount (₹)</label>
                    <input type="number" step="0.01" name="monthly_discount" id="discount_input" value="0" oninput="calcNet()" class="w-full sm:w-1/2 px-3 py-2 rounded-xl border border-slate-300 bg-white text-xs font-bold text-emerald-700">
                </div>

                <!-- Live Total Preview Box -->
                <div class="p-4 rounded-2xl bg-purple-950 text-white flex items-center justify-between">
                    <div>
                        <span class="text-xs text-amber-300 uppercase tracking-wider font-bold block">Recurring Monthly Total</span>
                        <p class="text-[11px] text-purple-200">Amount to be invoiced automatically at each month-end</p>
                    </div>
                    <span class="font-heading text-2xl font-black text-amber-300" id="total_monthly_display">₹{{ number_format($suggestedTuition, 2) }}</span>
                </div>

                <!-- Initial Admission Kit Invoice Checkbox -->
                <label class="flex items-start gap-2.5 pt-2 cursor-pointer">
                    <input type="checkbox" name="generate_initial_invoice" value="1" checked class="mt-0.5 w-4 h-4 rounded text-purple-900">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Generate Admission Registration & Welcome Kit Invoice immediately</span>
                        <span class="text-[11px] text-slate-500">Issues an invoice of ₹6,500 (with Nov 20 early bird concession applied) payable now.</span>
                    </div>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                    Cancel & Return to Inquiry
                </a>

                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-purple-950 font-heading font-black text-base shadow-xl hover:shadow-2xl transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Admit Student & Enable Monthly Billing</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function toggleDaycare(cb) {
        document.getElementById('daycare_amount_box').classList.toggle('hidden', !cb.checked);
    }
    function toggleTransport(cb) {
        document.getElementById('transport_amount_box').classList.toggle('hidden', !cb.checked);
    }
    function updateDefaultTuition(sel) {
        const opt = sel.options[sel.selectedIndex];
        const fee = opt.getAttribute('data-fee');
        if (fee) {
            document.getElementById('tuition_input').value = fee;
            calcNet();
        }
    }
    function calcNet() {
        const tuition = parseFloat(document.getElementById('tuition_input').value) || 0;
        const hasDaycare = document.getElementById('has_daycare').checked;
        const daycare = hasDaycare ? (parseFloat(document.getElementById('daycare_input').value) || 0) : 0;
        const hasTransport = document.getElementById('has_transport').checked;
        const transport = hasTransport ? (parseFloat(document.getElementById('transport_input').value) || 0) : 0;
        const discount = parseFloat(document.getElementById('discount_input').value) || 0;

        const total = Math.max(0, (tuition + daycare + transport) - discount);
        document.getElementById('total_monthly_display').innerText = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
    }
</script>
@endsection
