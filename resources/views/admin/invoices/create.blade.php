@extends('layouts.admin')

@section('title', 'Create New Invoice')
@section('page_title', 'Create New Fee Invoice')
@section('page_subtitle', 'Generate an invoice for admission fee, daycare subscription or tuition')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.invoices.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Invoices
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.invoices.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Associate with student or applicant -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Enrolled Student (Optional)</label>
                    <select name="student_id" id="student_select" onchange="autoFillStudent(this)" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="">-- None / Select Enrolled Student --</option>
                        @foreach($students as $stu)
                            <option value="{{ $stu->id }}" {{ ($selectedStudent && $selectedStudent->id == $stu->id) ? 'selected' : '' }} data-parent="{{ $stu->father_name ?: $stu->mother_name }}" data-phone="{{ $stu->father_phone ?: $stu->mother_phone }}" data-email="{{ $stu->father_email ?: $stu->mother_email }}">
                                {{ $stu->full_name }} ({{ $stu->admission_number }} - {{ $stu->program }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Select Admission Applicant (Optional)</label>
                    <select name="admission_application_id" id="app_select" onchange="autoFillApp(this)" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="">-- None / Select Applicant --</option>
                        @foreach($applications as $app)
                            <option value="{{ $app->id }}" {{ ($selectedApplication && $selectedApplication->id == $app->id) ? 'selected' : '' }} data-parent="{{ $app->father_name }}" data-phone="{{ $app->father_phone }}" data-email="{{ $app->father_email }}">
                                {{ $app->child_full_name }} ({{ $app->application_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Parent Details -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Parent's Full Name *</label>
                    <input type="text" name="parent_name" id="parent_name" value="{{ $selectedStudent ? ($selectedStudent->father_name ?: $selectedStudent->mother_name) : ($selectedApplication ? $selectedApplication->father_name : old('parent_name')) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Parent Phone *</label>
                    <input type="tel" name="parent_phone" id="parent_phone" value="{{ $selectedStudent ? ($selectedStudent->father_phone ?: $selectedStudent->mother_phone) : ($selectedApplication ? $selectedApplication->father_phone : old('parent_phone')) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Parent Email</label>
                    <input type="email" name="parent_email" id="parent_email" value="{{ $selectedStudent ? ($selectedStudent->father_email ?: $selectedStudent->mother_email) : ($selectedApplication ? $selectedApplication->father_email : old('parent_email')) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>

            <!-- Invoice Particulars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Invoice Title *</label>
                    <input type="text" name="title" value="{{ old('title', 'Monthly Day Care & Activity Fee') }}" required placeholder="e.g. Term 1 Tuition & Pentamind Kit Fee" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Invoice Category *</label>
                    <select name="invoice_type" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="admission">Admission Registration Fee</option>
                        <option value="tuition">Tuition Fee</option>
                        <option value="subscription" selected>Daycare / Recurring Subscription</option>
                        <option value="transport">GPS Safe Van Transport</option>
                        <option value="activity">STEM & Activity Club</option>
                        <option value="other">Other Fee</option>
                    </select>
                </div>
            </div>

            <!-- Amounts Calculation -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subtotal (₹) *</label>
                        <input type="number" step="0.01" name="subtotal" id="subtotal" value="{{ old('subtotal', 12500) }}" required oninput="calcTotal()" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Discount (₹)</label>
                        <input type="number" step="0.01" name="discount" id="discount" value="{{ old('discount', 0) }}" oninput="calcTotal()" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-emerald-700 focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Due Date *</label>
                        <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                    <span class="text-xs font-bold text-slate-600 uppercase">Estimated Total Amount:</span>
                    <span class="text-lg font-heading font-black text-purple-950" id="total_display">₹12,500.00</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Notes / Instructions</label>
                <textarea name="notes" rows="2" placeholder="e.g. November 2026 Day Care Billing. GPS transport Indiranagar route included." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-600"></textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Generate & Issue Invoice</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function autoFillStudent(sel) {
        const opt = sel.options[sel.selectedIndex];
        if (opt.value) {
            document.getElementById('parent_name').value = opt.getAttribute('data-parent') || '';
            document.getElementById('parent_phone').value = opt.getAttribute('data-phone') || '';
            document.getElementById('parent_email').value = opt.getAttribute('data-email') || '';
        }
    }

    function autoFillApp(sel) {
        const opt = sel.options[sel.selectedIndex];
        if (opt.value) {
            document.getElementById('parent_name').value = opt.getAttribute('data-parent') || '';
            document.getElementById('parent_phone').value = opt.getAttribute('data-phone') || '';
            document.getElementById('parent_email').value = opt.getAttribute('data-email') || '';
        }
    }

    function calcTotal() {
        const sub = parseFloat(document.getElementById('subtotal').value) || 0;
        const disc = parseFloat(document.getElementById('discount').value) || 0;
        const tot = Math.max(0, sub - disc);
        document.getElementById('total_display').innerText = '₹' + tot.toLocaleString('en-IN', {minimumFractionDigits: 2});
    }
</script>
@endsection
