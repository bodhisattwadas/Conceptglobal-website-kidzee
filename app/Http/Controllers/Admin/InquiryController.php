<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\Inquiry;
use App\Models\Invoice;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $source = $request->query('source');

        $query = Inquiry::query()->with('convertedStudent')->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($source && $source !== 'all') {
            $query->where('referral_source', $source);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('parent_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('child_name', 'like', "%{$search}%")
                    ->orWhere('inquiry_number', 'like', "%{$search}%")
                    ->orWhere('referral_source', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Inquiry::count(),
            'new' => Inquiry::where('status', 'new')->count(),
            'contacted' => Inquiry::where('status', 'contacted')->count(),
            'campus_visit_scheduled' => Inquiry::where('status', 'campus_visit_scheduled')->count(),
            'converted' => Inquiry::where('status', 'converted')->count(),
            'closed' => Inquiry::where('status', 'closed')->count(),
        ];

        $sources = Inquiry::whereNotNull('referral_source')
            ->selectRaw('referral_source, count(*) as count')
            ->groupBy('referral_source')
            ->pluck('count', 'referral_source')
            ->toArray();

        return view('admin.inquiries.index', compact('inquiries', 'status', 'search', 'source', 'counts', 'sources'));
    }

    public function show(Inquiry $inquiry): View
    {
        $inquiry->load('convertedStudent');

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:new,contacted,campus_visit_scheduled,visited,converted,closed',
            'counselor_notes' => 'nullable|string',
            'preferred_visit_date' => 'nullable|date',
        ]);

        if ($validated['status'] !== $inquiry->status && in_array($validated['status'], ['contacted', 'campus_visit_scheduled', 'visited'])) {
            $inquiry->last_contacted_at = Carbon::now();
        }

        $inquiry->status = $validated['status'];
        $inquiry->counselor_notes = $validated['counselor_notes'];
        if (isset($validated['preferred_visit_date'])) {
            $inquiry->preferred_visit_date = $validated['preferred_visit_date'];
        }
        $inquiry->save();

        return redirect()->back()->with('success', "Inquiry {$inquiry->inquiry_number} status updated to ".ucfirst(str_replace('_', ' ', $inquiry->status)));
    }

    /**
     * Show form to convert lead into an admitted Student with monthly billing setup
     */
    public function showConvertForm(Inquiry $inquiry): View|RedirectResponse
    {
        if ($inquiry->converted_student_id && $inquiry->convertedStudent) {
            return redirect()->route('admin.students.show', $inquiry->convertedStudent)
                ->with('info', "This inquiry has already been converted to Student #{$inquiry->convertedStudent->admission_number}.");
        }

        // Program defaults for tuition fee
        $tuitionDefaults = [
            'Nuvo' => 5500.00,
            'Playgroup' => 6000.00,
            'Nursery' => 6500.00,
            'Junior KG' => 7000.00,
            'Senior KG' => 7500.00,
            'Daycare' => 12500.00,
        ];

        $suggestedTuition = $tuitionDefaults[$inquiry->program_interested] ?? 6500.00;

        return view('admin.inquiries.convert', compact('inquiry', 'suggestedTuition'));
    }

    /**
     * Directly convert lead to active student with billing eligibility
     */
    public function convertToStudent(Request $request, Inquiry $inquiry): RedirectResponse
    {
        if ($inquiry->converted_student_id && $inquiry->convertedStudent) {
            return redirect()->route('admin.students.show', $inquiry->convertedStudent);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'blood_group' => 'nullable|string|max:10',
            'program' => 'required|string|max:100',
            'section' => 'nullable|string|max:10',
            'admission_date' => 'required|date',
            'father_name' => 'required|string|max:150',
            'father_phone' => 'required|string|max:30',
            'father_email' => 'nullable|email|max:150',
            'mother_name' => 'nullable|string|max:150',
            'mother_phone' => 'nullable|string|max:30',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:30',
            'medical_notes' => 'nullable|string',
            // Billing fields
            'monthly_tuition_fee' => 'required|numeric|min:0',
            'has_daycare' => 'nullable|boolean',
            'monthly_daycare_fee' => 'nullable|numeric|min:0',
            'has_transport' => 'nullable|boolean',
            'monthly_transport_fee' => 'nullable|numeric|min:0',
            'monthly_discount' => 'nullable|numeric|min:0',
            'billing_cycle_day' => 'required|integer|min:1|max:28',
            'generate_initial_invoice' => 'nullable|boolean',
        ]);

        $hasDaycare = $request->boolean('has_daycare');
        $hasTransport = $request->boolean('has_transport');

        $student = Student::create([
            'inquiry_id' => $inquiry->id,
            'admission_number' => Student::generateAdmissionNumber(),
            'roll_number' => strtoupper(substr($validated['program'], 0, 2)).'-'.rand(10, 99),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'blood_group' => $validated['blood_group'] ?? null,
            'program' => $validated['program'],
            'section' => $validated['section'] ?: 'A',
            'admission_date' => $validated['admission_date'],
            'father_name' => $validated['father_name'],
            'father_phone' => $validated['father_phone'],
            'father_email' => $validated['father_email'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'mother_phone' => $validated['mother_phone'] ?? null,
            'address' => $validated['address'],
            'emergency_contact_name' => ! empty($validated['emergency_contact_name']) ? $validated['emergency_contact_name'] : $validated['father_name'],
            'emergency_contact_phone' => ! empty($validated['emergency_contact_phone']) ? $validated['emergency_contact_phone'] : $validated['father_phone'],
            'photo_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($validated['first_name']).'&backgroundColor=ffd54f',
            'medical_notes' => $validated['medical_notes'] ?? null,
            'monthly_tuition_fee' => $validated['monthly_tuition_fee'],
            'has_daycare' => $hasDaycare,
            'monthly_daycare_fee' => $hasDaycare ? ($validated['monthly_daycare_fee'] ?? 6000.00) : 0.00,
            'has_transport' => $hasTransport,
            'monthly_transport_fee' => $hasTransport ? ($validated['monthly_transport_fee'] ?? 3200.00) : 0.00,
            'monthly_discount' => $validated['monthly_discount'] ?? 0.00,
            'billing_cycle_day' => $validated['billing_cycle_day'] ?? 5,
            'is_billing_active' => true,
            'referral_source' => $inquiry->referral_source,
            'status' => 'active',
        ]);

        // Link back to inquiry
        $inquiry->status = 'converted';
        $inquiry->converted_student_id = $student->id;
        $inquiry->counselor_notes = ($inquiry->counselor_notes ? $inquiry->counselor_notes."\n" : '')."Lead converted to enrolled student #{$student->admission_number} on ".Carbon::today()->format('d M Y');
        $inquiry->save();

        // Optionally generate initial admission kit invoice
        if ($request->boolean('generate_initial_invoice')) {
            Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'student_id' => $student->id,
                'parent_name' => $student->father_name,
                'parent_phone' => $student->father_phone,
                'parent_email' => $student->father_email,
                'title' => "Admission Registration & Pentamind Kit - {$student->program}",
                'invoice_type' => 'admission',
                'subtotal' => 8000.00,
                'discount' => 1500.00, // Nov 20 early bird concession
                'tax' => 0.00,
                'total_amount' => 6500.00,
                'paid_amount' => 0.00,
                'due_date' => Carbon::today()->addDays(7),
                'status' => 'pending',
                'notes' => 'Admission registration fee invoice generated upon lead conversion.',
            ]);
        }

        return redirect()->route('admin.students.show', $student)
            ->with('success', "🎉 Lead successfully converted! Student {$student->full_name} enrolled with Admission ID: {$student->admission_number}. Configured for monthly billing (₹".number_format($student->total_monthly_fee, 2).'/month)!');
    }

    public function convertToApplication(Inquiry $inquiry): RedirectResponse
    {
        $app = AdmissionApplication::create([
            'application_number' => AdmissionApplication::generateApplicationNumber(),
            'inquiry_id' => $inquiry->id,
            'child_first_name' => explode(' ', $inquiry->child_name)[0] ?? $inquiry->child_name,
            'child_last_name' => explode(' ', $inquiry->child_name, 2)[1] ?? '',
            'gender' => $inquiry->child_gender ?: 'Other',
            'dob' => $inquiry->child_dob ?: Carbon::today()->subYears(3),
            'program_applied' => $inquiry->program_interested,
            'father_name' => $inquiry->parent_name,
            'father_phone' => $inquiry->phone,
            'father_email' => $inquiry->email,
            'address' => $inquiry->address ?: 'Kidzee Neighborhood',
            'city' => $inquiry->city ?: 'Bengaluru',
            'pincode' => $inquiry->pincode ?: '560001',
            'status' => 'submitted',
        ]);

        $inquiry->status = 'converted';
        $inquiry->counselor_notes = ($inquiry->counselor_notes ? $inquiry->counselor_notes."\n" : '')."Converted to Admission Application #{$app->application_number}";
        $inquiry->save();

        return redirect()->route('admin.admissions.show', $app)
            ->with('success', "Inquiry converted to Admission Application #{$app->application_number}");
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry removed successfully.');
    }
}
