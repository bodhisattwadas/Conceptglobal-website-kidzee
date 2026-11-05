@extends('layouts.app')

@section('title', 'Day Care & Subscription Fee Packages | Kidzee Preschool')

@section('content')
<div class="py-12 bg-amber-50/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-bold uppercase tracking-wider mb-2">
                Transparent & Flexible Pricing
            </span>
            <h1 class="font-heading text-4xl sm:text-5xl font-black text-slate-900">
                Day Care & Add-On <span class="text-purple-800">Subscriptions</span>
            </h1>
            <p class="text-slate-600 text-sm sm:text-base mt-2">
                Choose the exact services your family needs: full or extended daycare, door-to-door GPS van transport, fresh organic meals, and STEM hobby clubs. Zero hidden registration penalties.
            </p>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @foreach($plans as $plan)
                <div class="bg-white rounded-3xl p-7 border-2 border-slate-200/90 shadow-md hover:shadow-xl transition flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $plan->category === 'daycare' ? 'bg-orange-100 text-orange-800' : ($plan->category === 'transport' ? 'bg-sky-100 text-sky-800' : ($plan->category === 'meal' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800')) }}">
                                {{ ucfirst($plan->category) }}
                            </span>
                            <span class="text-xs text-slate-500 font-extrabold uppercase">
                                {{ ucfirst($plan->billing_cycle) }}
                            </span>
                        </div>

                        <h3 class="font-heading text-2xl font-bold text-slate-900 mb-2 group-hover:text-purple-900 transition">
                            {{ $plan->name }}
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-6">
                            {{ $plan->description }}
                        </p>

                        <div class="mb-6 flex items-baseline gap-1">
                            <span class="text-4xl font-heading font-black text-purple-950">₹{{ number_format($plan->price) }}</span>
                            <span class="text-xs text-slate-500 font-bold">/ {{ $plan->billing_cycle }}</span>
                        </div>

                        <!-- Included Features -->
                        <div class="space-y-3 mb-6 pt-4 border-t border-slate-100">
                            @if(!empty($plan->features))
                                @foreach($plan->features as $feat)
                                    <div class="flex items-start gap-2 text-xs text-slate-700">
                                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-sm"></i>
                                        <span>{{ $feat }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('admissions.apply') }}" class="w-full py-3 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <span>Enroll with this Package</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- FAQ Section for Daycare -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-lg max-w-4xl mx-auto">
            <h2 class="font-heading text-2xl font-bold text-purple-950 mb-6 text-center">Frequently Asked Questions on Subscriptions</h2>

            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <h3 class="text-sm font-bold text-slate-900 mb-1">When does recurring billing start?</h3>
                    <p class="text-xs text-slate-600">
                        Subscriptions begin on your selected start date (e.g. Opening Day November 20). Invoices are generated automatically 5 days prior to the start of each new monthly or term cycle.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Can I pause or adjust daycare hours during vacation?</h3>
                    <p class="text-xs text-slate-600">
                        Yes! Parents can request a pause or temporary shift from Full-Day to Half-Day by notifying the campus administration with 7 days' notice.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <h3 class="text-sm font-bold text-slate-900 mb-1">How do parents make payments?</h3>
                    <p class="text-xs text-slate-600">
                        Invoices can be settled directly online via UPI, NetBanking, Debit/Credit cards, or at the school counter via Cheque or Card swipe. Instant printable receipts are delivered to your portal.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
