@extends('layouts.admin')

@section('title', 'Application: ' . $application->application_number)
@section('page_title', 'Application Dossier')
@section('page_subtitle', $application->application_number . ' — ' . $application->child_full_name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('admin.admissions.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Admissions List
        </a>

        <div class="flex items-center gap-2">
            @if($application->status !== 'admitted')
                <form action="{{ route('admin.admissions.enroll', $application) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Confirm Admission & Enroll as Student</span>
                    </button>
                </form>
            @else
                <a href="{{ route('admin.students.show', $application->student) }}" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-id-card"></i> View Active Student Profile
                </a>
            @endif
        </div>
    </div>

    <!-- Main Application File Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-8">
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Application Reference</span>
                <h2 class="text-3xl font-black text-slate-900">{{ $application->child_full_name }}</h2>
                <p class="text-xs text-slate-500">
                    Application ID: <strong class="text-purple-900">{{ $application->application_number }}</strong> • Submitted on {{ $application->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="text-right">
                <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider {{ $application->status === 'admitted' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : ($application->status === 'approved' ? 'bg-sky-100 text-sky-800 border border-sky-300' : 'bg-amber-100 text-amber-800 border border-amber-300') }}">
                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                </span>
                @if($application->approved_at)
                    <p class="text-[10px] text-slate-400 mt-1">Approved: {{ $application->approved_at->format('d M Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Section 1: Child Details -->
        <div>
            <h3 class="font-heading text-lg font-bold text-purple-950 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-child text-amber-500"></i> Child Details
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Gender</span>
                    <span class="text-xs font-bold text-slate-800">{{ $application->gender }}</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Date of Birth</span>
                    <span class="text-xs font-bold text-slate-800">{{ $application->dob->format('d M Y') }} ({{ Carbon\Carbon::parse($application->dob)->age }} yrs)</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Blood Group</span>
                    <span class="text-xs font-bold text-slate-800">{{ $application->blood_group ?: 'Not provided' }}</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Program</span>
                    <span class="text-xs font-bold text-purple-900">{{ $application->program_applied }}</span>
                </div>
            </div>
        </div>

        <!-- Section 2: Parents & Contacts -->
        <div>
            <h3 class="font-heading text-lg font-bold text-purple-950 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-users text-purple-600"></i> Parents & Residential Information
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Father -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Father Details</span>
                    <p class="text-xs font-bold text-slate-900">{{ $application->father_name }}</p>
                    <p class="text-xs text-slate-600"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $application->father_phone }}</p>
                    @if($application->father_email)
                        <p class="text-xs text-slate-600"><i class="fa-solid fa-envelope text-[9px] mr-1"></i> {{ $application->father_email }}</p>
                    @endif
                    @if($application->father_occupation)
                        <p class="text-xs text-slate-500">Occupation: {{ $application->father_occupation }}</p>
                    @endif
                </div>

                <!-- Mother -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Mother Details</span>
                    <p class="text-xs font-bold text-slate-900">{{ $application->mother_name ?: 'Not provided' }}</p>
                    @if($application->mother_phone)
                        <p class="text-xs text-slate-600"><i class="fa-solid fa-phone text-[9px] mr-1"></i> {{ $application->mother_phone }}</p>
                    @endif
                    @if($application->mother_email)
                        <p class="text-xs text-slate-600"><i class="fa-solid fa-envelope text-[9px] mr-1"></i> {{ $application->mother_email }}</p>
                    @endif
                    @if($application->mother_occupation)
                        <p class="text-xs text-slate-500">Occupation: {{ $application->mother_occupation }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Home Address</span>
                    <p class="text-xs text-slate-800 mt-1">{{ $application->address }}, {{ $application->city }} {{ $application->pincode ? ' - ' . $application->pincode : '' }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold text-slate-400 uppercase block">Emergency Contact</span>
                    <p class="text-xs text-slate-800 mt-1 font-semibold">{{ $application->emergency_contact_name ?: $application->father_name }}</p>
                    <p class="text-xs text-slate-600">{{ $application->emergency_contact_phone ?: $application->father_phone }}</p>
                </div>
            </div>
        </div>

        <!-- Section 3: Medical & Add-Ons -->
        <div>
            <h3 class="font-heading text-lg font-bold text-purple-950 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-heart-pulse text-rose-500"></i> Medical Notes & Requested Services
            </h3>
            <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-200 text-xs text-slate-800">
                <strong>Medical Notes:</strong> {{ $application->medical_conditions ?: 'No allergies or conditions declared.' }}
            </div>
            <div class="flex items-center gap-4 mt-3 text-xs font-bold text-slate-700">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid {{ $application->transport_required ? 'fa-check text-emerald-600' : 'fa-xmark text-slate-400' }}"></i>
                    GPS Van Transport: {{ $application->transport_required ? 'Requested' : 'Not required' }}
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid {{ $application->daycare_required ? 'fa-check text-emerald-600' : 'fa-xmark text-slate-400' }}"></i>
                    Daycare Service: {{ $application->daycare_required ? 'Requested' : 'Not required' }}
                </span>
            </div>
        </div>

        <!-- Section 4: Billing & Invoices Linked -->
        <div class="pt-6 border-t border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-heading text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-amber-500"></i> Linked Billing & Fee Invoices
                </h3>
                <a href="{{ route('admin.invoices.create', ['application_id' => $application->id]) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-800 shadow-2xs">
                    + Add Fee Invoice
                </a>
            </div>

            <div class="space-y-3">
                @forelse($application->invoices as $inv)
                    <div class="p-4 rounded-2xl border {{ $inv->status === 'paid' ? 'border-emerald-200 bg-emerald-50/20' : 'border-amber-200 bg-amber-50/20' }} flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="font-bold text-purple-900">{{ $inv->invoice_number }}</span>
                            <h4 class="font-bold text-slate-900">{{ $inv->title }}</h4>
                            <p class="text-slate-500">Due: {{ $inv->due_date->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <span class="font-heading font-black text-sm text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</span>
                                <span class="block uppercase font-bold text-[10px] {{ $inv->status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ ucfirst($inv->status) }}
                                </span>
                            </div>
                            <a href="{{ route('admin.invoices.show', $inv) }}" class="px-3 py-1.5 rounded-xl bg-slate-900 text-white font-bold text-[11px] hover:bg-slate-800">
                                View Invoice
                            </a>
                            @if($inv->status === 'paid')
                                <a href="{{ route('admin.invoices.receipt', $inv) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-purple-900 text-amber-300 font-bold text-[11px] hover:bg-purple-800">
                                    Receipt
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-3">No invoices created for this application yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Section 5: Status Review & Admin Notes -->
        <div class="pt-6 border-t border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm mb-3">Update Review Decision & Notes</h3>
            <form action="{{ route('admin.admissions.status', $application) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Application Status</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                            <option value="submitted" {{ $application->status === 'submitted' ? 'selected' : '' }}>Submitted (Pending Review)</option>
                            <option value="under_review" {{ $application->status === 'under_review' ? 'selected' : '' }}>Under Review / Documents Verification</option>
                            <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Approved (Seat Reserved)</option>
                            <option value="admitted" {{ $application->status === 'admitted' ? 'selected' : '' }}>Admitted & Enrolled in System</option>
                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected / Application Withdrawn</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Administrative Notes</label>
                        <input type="text" name="admin_notes" value="{{ $application->admin_notes }}" placeholder="e.g. Birth certificate verified, photo received" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition">
                        Update Application Status
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
