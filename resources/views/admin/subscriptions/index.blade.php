@extends('layouts.admin')

@section('title', 'Subscriptions & Daycare Packages')
@section('page_title', '3. Subscriptions & Daycare Engine')
@section('page_subtitle', 'Manage recurring packages, daycare enrollment, and billing renewals')

@section('content')
<div class="space-y-8">

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Active Subscriptions</span>
                <span class="text-3xl font-black text-purple-900 mt-1 block">{{ $activeCount }}</span>
                <span class="text-xs text-slate-500 mt-1 block">Students enrolled in recurring care</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-arrows-rotate"></i>
            </div>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Monthly Run Rate</span>
                <span class="text-3xl font-black text-emerald-600 mt-1 block">₹{{ number_format($monthlyRevenue, 2) }}</span>
                <span class="text-xs text-slate-500 mt-1 block">Projected subscription turnover</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-chart-line"></i>
            </div>
        </div>

        <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Available Plans</span>
                <span class="text-3xl font-black text-amber-500 mt-1 block">{{ $plans->count() }}</span>
                <span class="text-xs text-slate-500 mt-1 block">Daycare, Meals, Bus & Tuition</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-cubes"></i>
            </div>
        </div>
    </div>

    <!-- Section 1: Active Student Subscriptions -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Enrolled Student Subscriptions</h3>
                <p class="text-xs text-slate-500">Live recurring plans linked to student profiles</p>
            </div>

            <button type="button" onclick="document.getElementById('assign-subscription-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i>
                <span>Subscribe Student to Plan</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4">Student</th>
                        <th class="py-3.5 px-4">Package Plan</th>
                        <th class="py-3.5 px-4">Billing Cycle & Fee</th>
                        <th class="py-3.5 px-4">Validity Period</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.students.show', $sub->student) }}" class="font-bold text-slate-900 hover:text-purple-800 block">
                                    {{ $sub->student->full_name }}
                                </a>
                                <span class="text-[11px] text-slate-500">No: {{ $sub->student->admission_number }} • {{ $sub->student->program }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-purple-900 block">{{ $sub->plan->name }}</span>
                                <span class="text-[10px] uppercase font-bold px-1.5 py-0.2 rounded bg-slate-100 text-slate-600">
                                    {{ ucfirst($sub->plan->category) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                ₹{{ number_format($sub->amount, 2) }}
                                <span class="text-[11px] font-normal text-slate-400 block">/ {{ $sub->plan->billing_cycle }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-slate-800 font-semibold block">From: {{ $sub->start_date->format('d M Y') }}</span>
                                <span class="text-slate-500">To: {{ $sub->end_date ? $sub->end_date->format('d M Y') : 'Ongoing' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.subscriptions.status', $sub) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold px-2 py-1 rounded-xl border border-slate-200 focus:outline-none focus:ring-1 focus:ring-purple-600 bg-white">
                                        <option value="active" {{ $sub->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="paused" {{ $sub->status === 'paused' ? 'selected' : '' }}>Paused</option>
                                        <option value="cancelled" {{ $sub->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="expired" {{ $sub->status === 'expired' ? 'selected' : '' }}>Expired</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <form action="{{ route('admin.subscriptions.generateInvoice', $sub) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Generate Invoice for next cycle" class="px-3 py-1.5 rounded-lg bg-amber-400 text-purple-950 font-bold text-[11px] hover:bg-amber-500 shadow-2xs transition">
                                        <i class="fa-solid fa-file-invoice-dollar mr-1"></i> Bill Next Cycle
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No active student subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Manage Subscription Packages -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Subscription Packages Catalog</h3>
                <p class="text-xs text-slate-500">Master templates for Daycare, Transport, Meals, and Clubs</p>
            </div>

            <button type="button" onclick="document.getElementById('create-plan-modal').classList.remove('hidden')" class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-800 font-bold text-xs shadow-2xs transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-amber-500"></i>
                <span>Add Package</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="p-5 rounded-2xl border border-slate-200 hover:border-purple-300 transition space-y-3 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $plan->category === 'daycare' ? 'bg-orange-100 text-orange-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($plan->category) }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-400">{{ $plan->student_subscriptions_count }} Active Kid(s)</span>
                        </div>
                        <h4 class="font-heading text-lg font-bold text-slate-900 mt-2">{{ $plan->name }}</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $plan->description }}</p>
                        <div class="mt-3 flex items-baseline gap-1">
                            <span class="text-2xl font-black text-purple-950 font-heading">₹{{ number_format($plan->price) }}</span>
                            <span class="text-xs text-slate-400 font-bold">/ {{ $plan->billing_cycle }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-[11px] font-mono text-slate-400">{{ $plan->code }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $plan->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Modal 1: Assign Subscription to Student -->
<div id="assign-subscription-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border-4 border-amber-300">
        <button type="button" onclick="document.getElementById('assign-subscription-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="font-heading text-xl font-bold text-purple-950 mb-1">Enroll Student in Subscription</h3>
        <p class="text-xs text-slate-500 mb-4">Attach a daycare package, bus transport, or meal plan</p>

        <form action="{{ route('admin.subscriptions.assign') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Student *</label>
                <select name="student_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                    <option value="">-- Select Active Student --</option>
                    @foreach($students as $stu)
                        <option value="{{ $stu->id }}">{{ $stu->full_name }} ({{ $stu->admission_number }} - {{ $stu->program }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subscription Package *</label>
                <select name="subscription_plan_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                    <option value="">-- Select Package --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price) }} / {{ $plan->billing_cycle }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Start Date *</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">End Date (Optional)</label>
                    <input type="date" name="end_date" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Custom Fee Amount (Leave blank for default)</label>
                <input type="number" step="0.01" name="amount" placeholder="Default package fee" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="checkbox" name="auto_renew" value="1" checked class="w-4 h-4 rounded text-purple-900">
                <span>Enable automatic invoice generation on renewal</span>
            </label>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition">
                    Confirm Subscription
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Create New Package -->
<div id="create-plan-modal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative border-4 border-amber-300">
        <button type="button" onclick="document.getElementById('create-plan-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="font-heading text-xl font-bold text-purple-950 mb-1">New Subscription Package</h3>
        <p class="text-xs text-slate-500 mb-4">Create a reusable recurring fee template</p>

        <form action="{{ route('admin.subscriptions.plans.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Package Name *</label>
                <input type="text" name="name" required placeholder="e.g. Evening Daycare 4pm-7pm" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Unique Code *</label>
                    <input type="text" name="code" required placeholder="e.g. DAYCARE_EVENING" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono uppercase focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Category *</label>
                    <select name="category" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="daycare">Daycare</option>
                        <option value="tuition">Tuition</option>
                        <option value="transport">Transport</option>
                        <option value="meal">Nutritious Meals</option>
                        <option value="activity">STEM / Activity Club</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Billing Cycle *</label>
                    <select name="billing_cycle" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600 bg-white">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="term">Term (4 Months)</option>
                        <option value="annual">Annual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Fee (₹) *</label>
                    <input type="number" step="0.01" name="price" required placeholder="6500" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-purple-600">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Brief outline of services covered..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Features (One per line)</label>
                <textarea name="features" rows="3" placeholder="Hours: 4:00 PM – 7:00 PM&#10;Fresh fruit snack included&#10;Evening storytelling" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-purple-600"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition">
                    Save Package Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
