<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $program = $request->query('program');
        $search = $request->query('search');

        $query = AdmissionApplication::with('invoices', 'student')->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($program && $program !== 'all') {
            $query->where('program_applied', $program);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('child_first_name', 'like', "%{$search}%")
                    ->orWhere('child_last_name', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('father_phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => AdmissionApplication::count(),
            'submitted' => AdmissionApplication::where('status', 'submitted')->count(),
            'under_review' => AdmissionApplication::where('status', 'under_review')->count(),
            'approved' => AdmissionApplication::where('status', 'approved')->count(),
            'admitted' => AdmissionApplication::where('status', 'admitted')->count(),
        ];

        return view('admin.admissions.index', compact('applications', 'status', 'program', 'search', 'counts'));
    }

    public function show(AdmissionApplication $application): View
    {
        $application->load(['invoices.payments', 'student']);

        return view('admin.admissions.show', compact('application'));
    }

    public function updateStatus(Request $request, AdmissionApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:submitted,under_review,approved,admitted,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'approved' && ! $application->approved_at) {
            $application->approved_at = Carbon::now();
        }

        $application->status = $validated['status'];
        $application->admin_notes = $validated['admin_notes'];
        $application->save();

        return redirect()->back()->with('success', "Application #{$application->application_number} status updated to ".ucfirst(str_replace('_', ' ', $application->status)));
    }

    public function enrollAsStudent(AdmissionApplication $application): RedirectResponse
    {
        if ($application->student) {
            return redirect()->route('admin.students.show', $application->student)
                ->with('info', 'Student profile already exists for this application.');
        }

        $admissionNumber = Student::generateAdmissionNumber();

        $defaultFees = [
            'Nuvo' => 6000.00,
            'Playgroup' => 6500.00,
            'Nursery' => 7000.00,
            'Junior KG' => 7500.00,
            'Senior KG' => 8000.00,
            'Daycare' => 6500.00,
        ];

        $student = Student::create([
            'admission_application_id' => $application->id,
            'admission_number' => $admissionNumber,
            'roll_number' => strtoupper(substr($application->program_applied, 0, 2)).'-'.rand(10, 99),
            'first_name' => $application->child_first_name,
            'last_name' => $application->child_last_name,
            'gender' => $application->gender,
            'dob' => $application->dob,
            'blood_group' => $application->blood_group,
            'program' => $application->program_applied,
            'section' => 'A',
            'admission_date' => Carbon::today(),
            'father_name' => $application->father_name,
            'father_phone' => $application->father_phone,
            'father_email' => $application->father_email,
            'mother_name' => $application->mother_name,
            'mother_phone' => $application->mother_phone,
            'mother_email' => $application->mother_email,
            'address' => $application->address.', '.$application->city.($application->pincode ? ' - '.$application->pincode : ''),
            'emergency_contact_name' => $application->emergency_contact_name ?: $application->father_name,
            'emergency_contact_phone' => $application->emergency_contact_phone ?: $application->father_phone,
            'photo_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($application->child_first_name).'&backgroundColor=ffd54f',
            'medical_notes' => $application->medical_conditions,
            'status' => 'active',
            'monthly_tuition_fee' => $defaultFees[$application->program_applied] ?? 6500.00,
            'has_daycare' => $application->program_applied === 'Daycare',
            'monthly_daycare_fee' => $application->program_applied === 'Daycare' ? 6000.00 : 0.00,
            'has_transport' => false,
            'monthly_transport_fee' => 0.00,
            'monthly_discount' => 0.00,
            'billing_cycle_day' => 1,
            'is_billing_active' => true,
            'referral_source' => 'Direct Admission Form',
        ]);

        // Link existing invoices to student
        foreach ($application->invoices as $inv) {
            if (! $inv->student_id) {
                $inv->student_id = $student->id;
                $inv->save();
            }
        }

        $application->status = 'admitted';
        $application->admin_notes = ($application->admin_notes ? $application->admin_notes."\n" : '')."Enrolled into active student directory with Admission No {$student->admission_number}.";
        $application->save();

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student {$student->full_name} enrolled successfully with Admission Number: {$student->admission_number}!");
    }
}
