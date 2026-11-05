<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $type = $request->query('type');
        $search = $request->query('search');

        $query = Invoice::with(['student', 'application'])->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($type && $type !== 'all') {
            $query->where('invoice_type', $type);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('parent_name', 'like', "%{$search}%")
                    ->orWhere('parent_phone', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $invoices = $query->paginate(15)->withQueryString();

        $stats = [
            'total_billed' => Invoice::sum('total_amount'),
            'total_collected' => Payment::where('status', 'completed')->sum('amount'),
            'pending_count' => Invoice::where('status', 'pending')->count(),
            'paid_count' => Invoice::where('status', 'paid')->count(),
        ];

        return view('admin.invoices.index', compact('invoices', 'status', 'type', 'search', 'stats'));
    }

    public function create(Request $request): View
    {
        $studentId = $request->query('student_id');
        $applicationId = $request->query('application_id');

        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $applications = AdmissionApplication::where('status', '!=', 'admitted')->orderByDesc('id')->get();

        $selectedStudent = $studentId ? Student::find($studentId) : null;
        $selectedApplication = $applicationId ? AdmissionApplication::find($applicationId) : null;

        return view('admin.invoices.create', compact('students', 'applications', 'selectedStudent', 'selectedApplication'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'admission_application_id' => 'nullable|exists:admission_applications,id',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:30',
            'parent_email' => 'nullable|email|max:255',
            'title' => 'required|string|max:255',
            'invoice_type' => 'required|string|in:admission,tuition,subscription,transport,activity,other',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $subtotal = (float) $validated['subtotal'];
        $discount = (float) ($validated['discount'] ?? 0);
        $tax = (float) ($validated['tax'] ?? 0);
        $totalAmount = max(0, $subtotal - $discount + $tax);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'student_id' => $validated['student_id'] ?? null,
            'admission_application_id' => $validated['admission_application_id'] ?? null,
            'parent_name' => $validated['parent_name'],
            'parent_phone' => $validated['parent_phone'],
            'parent_email' => $validated['parent_email'] ?? null,
            'title' => $validated['title'],
            'invoice_type' => $validated['invoice_type'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'paid_amount' => 0.00,
            'due_date' => $validated['due_date'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', "Invoice #{$invoice->invoice_number} created successfully!");
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['student', 'application', 'payments']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function printReceipt(Invoice $invoice): View
    {
        $invoice->load(['student', 'application', 'payments']);

        return view('admin.invoices.receipt', compact('invoice'));
    }

    public function recordPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:'.($invoice->total_amount - $invoice->paid_amount),
            'payment_method' => 'required|string|in:cash,upi,card,bank_transfer,cheque,online',
            'transaction_reference' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_reference' => Payment::generatePaymentReference(),
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'payment_date' => $validated['payment_date'],
            'status' => 'completed',
            'notes' => $validated['notes'] ?? null,
        ]);

        $newPaidAmount = (float) $invoice->paid_amount + (float) $validated['amount'];
        $invoice->paid_amount = $newPaidAmount;
        $invoice->payment_method = $validated['payment_method'];

        if ($newPaidAmount >= (float) $invoice->total_amount) {
            $invoice->status = 'paid';
            $invoice->paid_at = Carbon::now();
        } else {
            $invoice->status = 'partially_paid';
        }
        $invoice->save();

        return redirect()->back()->with('success', 'Payment of ₹'.number_format($payment->amount, 2)." recorded successfully under ref {$payment->payment_reference}!");
    }
}
