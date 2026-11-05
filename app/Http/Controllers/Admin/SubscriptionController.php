<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\StudentSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $plans = SubscriptionPlan::withCount('studentSubscriptions')->get();

        $subscriptions = StudentSubscription::with(['student', 'plan'])
            ->latest()
            ->paginate(15);

        $students = Student::where('status', 'active')->orderBy('first_name')->get();

        $activeCount = StudentSubscription::where('status', 'active')->count();
        $monthlyRevenue = StudentSubscription::where('status', 'active')->sum('amount');

        return view('admin.subscriptions.index', compact('plans', 'subscriptions', 'students', 'activeCount', 'monthlyRevenue'));
    }

    public function storePlan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subscription_plans,code',
            'category' => 'required|string|in:daycare,tuition,transport,meal,activity',
            'billing_cycle' => 'required|string|in:monthly,quarterly,term,annual',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
        ]);

        $featuresArray = [];
        if (! empty($validated['features'])) {
            $featuresArray = array_values(array_filter(array_map('trim', explode("\n", $validated['features']))));
        }

        SubscriptionPlan::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'category' => $validated['category'],
            'billing_cycle' => $validated['billing_cycle'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'features' => $featuresArray,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Subscription package {$validated['name']} created successfully!");
    }

    public function assignStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'amount' => 'nullable|numeric|min:0',
            'auto_renew' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);
        $amount = $validated['amount'] ?? $plan->price;

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = ! empty($validated['end_date']) ? Carbon::parse($validated['end_date']) : (
            $plan->billing_cycle === 'monthly' ? $startDate->copy()->addMonth()->subDay() : $startDate->copy()->addMonths(3)->subDay()
        );

        $subscription = StudentSubscription::create([
            'student_id' => $validated['student_id'],
            'subscription_plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'amount' => $amount,
            'status' => 'active',
            'auto_renew' => $request->boolean('auto_renew', true),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', "Student subscribed to {$plan->name} successfully!");
    }

    public function updateStatus(Request $request, StudentSubscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,paused,cancelled,expired',
        ]);

        $subscription->status = $validated['status'];
        $subscription->save();

        return redirect()->back()->with('success', "Subscription status updated to {$subscription->status}.");
    }

    public function generateInvoice(StudentSubscription $subscription): RedirectResponse
    {
        $student = $subscription->student;
        $plan = $subscription->plan;

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'student_id' => $student->id,
            'parent_name' => $student->father_name ?: $student->mother_name,
            'parent_phone' => $student->father_phone ?: $student->mother_phone,
            'parent_email' => $student->father_email ?: $student->mother_email,
            'title' => "{$plan->name} ({$subscription->start_date->format('M Y')})",
            'invoice_type' => 'subscription',
            'subtotal' => $subscription->amount,
            'discount' => 0.00,
            'tax' => 0.00,
            'total_amount' => $subscription->amount,
            'paid_amount' => 0.00,
            'due_date' => Carbon::now()->addDays(5),
            'status' => 'pending',
            'notes' => "Recurring subscription invoice generated automatically for {$plan->billing_cycle} billing cycle.",
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', "Subscription Invoice #{$invoice->invoice_number} generated for {$student->full_name}!");
    }
}
