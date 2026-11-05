<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonthlyBillingController extends Controller
{
    public function index(Request $request): View
    {
        $selectedMonth = (int) $request->query('month', Carbon::now()->month);
        $selectedYear = (int) $request->query('year', Carbon::now()->year);
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search');

        $billingDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);

        // Fetch all active students eligible for billing
        $studentsQuery = Student::with(['invoices' => function ($q) use ($selectedMonth, $selectedYear) {
            $q->where('billing_month', $selectedMonth)
                ->where('billing_year', $selectedYear);
        }])
            ->where('status', 'active')
            ->where('is_billing_active', true);

        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%")
                    ->orWhere('father_phone', 'like', "%{$search}%");
            });
        }

        $allStudents = $studentsQuery->orderBy('first_name')->get();

        // Calculate statistics & attach fee status
        $stats = [
            'total_students' => $allStudents->count(),
            'billed_count' => 0,
            'unbilled_count' => 0,
            'paid_count' => 0,
            'paid_amount' => 0.0,
            'due_count' => 0,
            'due_amount' => 0.0,
            'overdue_count' => 0,
            'overdue_amount' => 0.0,
            'projected_total' => 0.0,
        ];

        $studentStatuses = [];
        $today = Carbon::today();

        foreach ($allStudents as $student) {
            $stats['projected_total'] += $student->total_monthly_fee;

            $invoice = $student->invoices->first();

            if (! $invoice) {
                $status = 'unbilled';
                $stats['unbilled_count']++;
                $studentStatuses[$student->id] = [
                    'status' => 'unbilled',
                    'label' => 'Unbilled',
                    'badge' => 'bg-slate-100 text-slate-700 border-slate-300',
                    'invoice' => null,
                    'amount' => $student->total_monthly_fee,
                ];
            } elseif ($invoice->status === 'paid') {
                $status = 'paid';
                $stats['billed_count']++;
                $stats['paid_count']++;
                $stats['paid_amount'] += (float) $invoice->paid_amount;
                $studentStatuses[$student->id] = [
                    'status' => 'paid',
                    'label' => 'Paid',
                    'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                    'invoice' => $invoice,
                    'amount' => (float) $invoice->paid_amount,
                ];
            } elseif ($today->isAfter($invoice->due_date)) {
                $status = 'overdue';
                $stats['billed_count']++;
                $stats['overdue_count']++;
                $stats['overdue_amount'] += (float) $invoice->balance_due;
                $studentStatuses[$student->id] = [
                    'status' => 'overdue',
                    'label' => 'Overdue',
                    'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
                    'invoice' => $invoice,
                    'amount' => (float) $invoice->balance_due,
                ];
            } else {
                $status = 'pending';
                $stats['billed_count']++;
                $stats['due_count']++;
                $stats['due_amount'] += (float) $invoice->balance_due;
                $studentStatuses[$student->id] = [
                    'status' => 'pending',
                    'label' => 'Due / Pending',
                    'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
                    'invoice' => $invoice,
                    'amount' => (float) $invoice->balance_due,
                ];
            }
        }

        // Filter students list if a status filter is requested
        if ($statusFilter && $statusFilter !== 'all') {
            $filteredStudents = $allStudents->filter(function ($student) use ($studentStatuses, $statusFilter) {
                return ($studentStatuses[$student->id]['status'] ?? '') === $statusFilter;
            });
        } else {
            $filteredStudents = $allStudents;
        }

        return view('admin.billing.monthly', compact(
            'filteredStudents',
            'studentStatuses',
            'stats',
            'selectedMonth',
            'selectedYear',
            'billingDate',
            'statusFilter',
            'search'
        ));
    }

    /**
     * Bulk generate monthly invoices for all eligible active students
     */
    public function generateBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2035',
            'due_day' => 'nullable|integer|min:1|max:28',
        ]);

        $month = (int) $validated['month'];
        $year = (int) $validated['year'];
        $dueDay = (int) ($validated['due_day'] ?? 10);

        $monthName = Carbon::createFromDate($year, $month, 1)->format('F Y');
        $dueDate = Carbon::createFromDate($year, $month, min($dueDay, Carbon::createFromDate($year, $month, 1)->daysInMonth));

        $students = Student::where('status', 'active')
            ->where('is_billing_active', true)
            ->get();

        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($students as $student) {
            // Check if invoice already exists for this student and month/year
            $existing = Invoice::where('student_id', $student->id)
                ->where('billing_month', $month)
                ->where('billing_year', $year)
                ->first();

            if ($existing) {
                $skippedCount++;

                continue;
            }

            $tuition = (float) $student->monthly_tuition_fee;
            $daycare = $student->has_daycare ? (float) $student->monthly_daycare_fee : 0.0;
            $transport = $student->has_transport ? (float) $student->monthly_transport_fee : 0.0;
            $discount = (float) $student->monthly_discount;
            $subtotal = $tuition + $daycare + $transport;
            $totalAmount = max(0, $subtotal - $discount);

            Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'student_id' => $student->id,
                'parent_name' => $student->father_name ?: $student->mother_name,
                'parent_phone' => $student->father_phone ?: $student->mother_phone,
                'parent_email' => $student->father_email ?: $student->mother_email,
                'title' => "Monthly Fee ({$student->program}) - {$monthName}",
                'invoice_type' => 'monthly_fee',
                'billing_month' => $month,
                'billing_year' => $year,
                'subtotal' => $subtotal,
                'tuition_fee_amount' => $tuition,
                'daycare_fee_amount' => $daycare,
                'transport_fee_amount' => $transport,
                'other_fee_amount' => 0.0,
                'discount' => $discount,
                'tax' => 0.0,
                'total_amount' => $totalAmount,
                'paid_amount' => 0.0,
                'due_date' => $dueDate,
                'status' => 'pending',
                'is_recurring_monthly' => true,
                'notes' => "Monthly billing generated for {$student->full_name} ({$student->program}).",
            ]);

            $generatedCount++;
        }

        return redirect()->route('admin.billing.monthly', ['month' => $month, 'year' => $year])
            ->with('success', "Monthly billing run completed for {$monthName}! {$generatedCount} invoice(s) generated. {$skippedCount} already existed.");
    }

    /**
     * Generate bill for a single student for the selected month
     */
    public function generateSingle(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2035',
        ]);

        $month = (int) $validated['month'];
        $year = (int) $validated['year'];
        $monthName = Carbon::createFromDate($year, $month, 1)->format('F Y');

        $existing = Invoice::where('student_id', $student->id)
            ->where('billing_month', $month)
            ->where('billing_year', $year)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', "Invoice #{$existing->invoice_number} already exists for {$student->full_name} for {$monthName}.");
        }

        $tuition = (float) $student->monthly_tuition_fee;
        $daycare = $student->has_daycare ? (float) $student->monthly_daycare_fee : 0.0;
        $transport = $student->has_transport ? (float) $student->monthly_transport_fee : 0.0;
        $discount = (float) $student->monthly_discount;
        $subtotal = $tuition + $daycare + $transport;
        $totalAmount = max(0, $subtotal - $discount);

        $dueDate = Carbon::createFromDate($year, $month, min(10, Carbon::createFromDate($year, $month, 1)->daysInMonth));

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'student_id' => $student->id,
            'parent_name' => $student->father_name ?: $student->mother_name,
            'parent_phone' => $student->father_phone ?: $student->mother_phone,
            'parent_email' => $student->father_email ?: $student->mother_email,
            'title' => "Monthly Fee ({$student->program}) - {$monthName}",
            'invoice_type' => 'monthly_fee',
            'billing_month' => $month,
            'billing_year' => $year,
            'subtotal' => $subtotal,
            'tuition_fee_amount' => $tuition,
            'daycare_fee_amount' => $daycare,
            'transport_fee_amount' => $transport,
            'other_fee_amount' => 0.0,
            'discount' => $discount,
            'tax' => 0.0,
            'total_amount' => $totalAmount,
            'paid_amount' => 0.0,
            'due_date' => $dueDate,
            'status' => 'pending',
            'is_recurring_monthly' => true,
            'notes' => "Individual monthly invoice generated for {$student->full_name}.",
        ]);

        return redirect()->back()
            ->with('success', "Invoice #{$invoice->invoice_number} generated for {$student->full_name} ({$monthName})!");
    }

    /**
     * Quick record payment for a monthly bill
     */
    public function quickPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:cash,upi,card,bank_transfer,cheque,online',
            'transaction_reference' => 'nullable|string|max:100',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $paymentDate = $validated['payment_date'] ?? Carbon::today()->toDateString();

        Payment::create([
            'invoice_id' => $invoice->id,
            'payment_reference' => Payment::generatePaymentReference(),
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'payment_date' => $paymentDate,
            'status' => 'completed',
            'notes' => $validated['notes'] ?? 'Monthly fee settled',
        ]);

        $newPaidAmount = (float) $invoice->paid_amount + (float) $validated['amount'];
        $invoice->paid_amount = $newPaidAmount;
        $invoice->payment_method = $validated['payment_method'];

        if ($newPaidAmount >= (float) $invoice->total_amount) {
            $invoice->status = 'paid';
            $invoice->paid_at = Carbon::parse($paymentDate);
        } else {
            $invoice->status = 'partially_paid';
        }
        $invoice->save();

        return redirect()->back()
            ->with('success', 'Fee payment of ₹'.number_format($validated['amount'], 2)." received for Invoice #{$invoice->invoice_number}. Marked as {$invoice->status}!");
    }
}
