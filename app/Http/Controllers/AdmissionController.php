<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function create(): View
    {
        return view('admissions.apply');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'child_first_name' => 'required|string|max:100',
            'child_last_name' => 'required|string|max:100',
            'gender' => 'required|string|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'blood_group' => 'nullable|string|max:10',
            'program_applied' => 'required|string|max:100',
            'academic_year' => 'nullable|string|max:20',
            'father_name' => 'required|string|max:150',
            'father_phone' => 'required|string|max:30',
            'father_email' => 'nullable|email|max:150',
            'father_occupation' => 'nullable|string|max:150',
            'mother_name' => 'nullable|string|max:150',
            'mother_phone' => 'nullable|string|max:30',
            'mother_email' => 'nullable|email|max:150',
            'mother_occupation' => 'nullable|string|max:150',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:30',
            'medical_conditions' => 'nullable|string|max:500',
            'previous_school' => 'nullable|string|max:255',
            'transport_required' => 'nullable|boolean',
            'daycare_required' => 'nullable|boolean',
        ]);

        $validated['application_number'] = AdmissionApplication::generateApplicationNumber();
        $validated['academic_year'] = $validated['academic_year'] ?? '2026-2027';
        $validated['status'] = 'submitted';
        $validated['transport_required'] = $request->boolean('transport_required');
        $validated['daycare_required'] = $request->boolean('daycare_required');

        $application = AdmissionApplication::create($validated);

        // Automatically generate Admission Registration & Welcome Kit Invoice
        $registrationFee = 8000.00;
        $earlyBirdDiscount = 1500.00; // Grand opening incentive
        $totalAmount = $registrationFee - $earlyBirdDiscount;

        Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'admission_application_id' => $application->id,
            'parent_name' => $application->father_name ?: $application->mother_name,
            'parent_phone' => $application->father_phone ?: $application->mother_phone,
            'parent_email' => $application->father_email ?: $application->mother_email,
            'title' => "Admission Registration & Welcome Kit - {$application->program_applied}",
            'invoice_type' => 'admission',
            'subtotal' => $registrationFee,
            'discount' => $earlyBirdDiscount,
            'tax' => 0.00,
            'total_amount' => $totalAmount,
            'paid_amount' => 0.00,
            'due_date' => Carbon::now()->addDays(7),
            'status' => 'pending',
            'notes' => 'Early bird welcome discount applied for November 20 Grand Opening batch.',
        ]);

        return redirect()->route('admissions.track', ['query' => $application->application_number])
            ->with('success', "Admission Application #{$application->application_number} has been submitted successfully! Please save your Application ID to track your child's enrollment progress.");
    }

    public function track(Request $request): View
    {
        $search = $request->input('query');
        $application = null;

        if ($search) {
            $trimmed = trim($search);
            $cleanedDigits = preg_replace('/[^0-9]/', '', $trimmed);

            $query = AdmissionApplication::with(['invoices.payments', 'student'])
                ->where('application_number', $trimmed)
                ->orWhere('father_phone', $trimmed)
                ->orWhere('father_phone', 'like', "%{$trimmed}%")
                ->orWhere('mother_phone', $trimmed)
                ->orWhere('mother_phone', 'like', "%{$trimmed}%");

            if (strlen($cleanedDigits) >= 6) {
                $query->orWhereRaw("REGEXP_REPLACE(father_phone, '[^0-9]', '', 'g') LIKE ?", ["%{$cleanedDigits}%"])
                    ->orWhereRaw("REGEXP_REPLACE(mother_phone, '[^0-9]', '', 'g') LIKE ?", ["%{$cleanedDigits}%"]);
            }

            $application = $query->first();
        }

        return view('admissions.track', compact('application', 'search'));
    }
}
