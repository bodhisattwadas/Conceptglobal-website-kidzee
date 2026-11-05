<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $program = $request->query('program');
        $status = $request->query('status', 'active');
        $feeFilter = $request->query('fee_status');
        $search = $request->query('search');

        $currentMonth = (int) $request->query('month', Carbon::now()->month);
        $currentYear = (int) $request->query('year', Carbon::now()->year);

        $query = Student::with(['subscriptions.plan', 'invoices' => function ($q) use ($currentMonth, $currentYear) {
            $q->where('billing_month', $currentMonth)
                ->where('billing_year', $currentYear);
        }])->latest('admission_date');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($program && $program !== 'all') {
            $query->where('program', $program);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('father_phone', 'like', "%{$search}%");
            });
        }

        $allMatching = $query->get();

        // Calculate student fee statuses for this month
        $studentFeeStatuses = [];
        $today = Carbon::today();

        foreach ($allMatching as $stu) {
            $inv = $stu->invoices->first();
            if (! $inv) {
                $s = 'unbilled';
            } elseif ($inv->status === 'paid') {
                $s = 'paid';
            } elseif ($today->isAfter($inv->due_date)) {
                $s = 'overdue';
            } else {
                $s = 'pending';
            }
            $studentFeeStatuses[$stu->id] = $s;
        }

        // Apply fee status filter if given
        if ($feeFilter && $feeFilter !== 'all') {
            $filteredIds = $allMatching->filter(fn ($s) => ($studentFeeStatuses[$s->id] ?? '') === $feeFilter)->pluck('id');
            $students = Student::whereIn('id', $filteredIds)->latest('admission_date')->paginate(12)->withQueryString();
        } else {
            $students = $query->paginate(12)->withQueryString();
        }

        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'daycare' => Student::where('has_daycare', true)->count(),
            'transport' => Student::where('has_transport', true)->count(),
            'nuvo' => Student::where('program', 'Nuvo')->count(),
            'playgroup' => Student::where('program', 'Playgroup')->count(),
            'nursery' => Student::where('program', 'Nursery')->count(),
            'junior_kg' => Student::where('program', 'Junior KG')->count(),
            'senior_kg' => Student::where('program', 'Senior KG')->count(),
        ];

        return view('admin.students.index', compact(
            'students',
            'program',
            'status',
            'feeFilter',
            'search',
            'stats',
            'currentMonth',
            'currentYear',
            'studentFeeStatuses'
        ));
    }

    public function create(): View
    {
        return view('admin.students.create');
    }

    public function store(Request $request): RedirectResponse
    {
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
            'mother_email' => 'nullable|email|max:150',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:30',
            'medical_notes' => 'nullable|string',
            // Billing & Referral
            'monthly_tuition_fee' => 'nullable|numeric|min:0',
            'has_daycare' => 'nullable|boolean',
            'monthly_daycare_fee' => 'nullable|numeric|min:0',
            'has_transport' => 'nullable|boolean',
            'monthly_transport_fee' => 'nullable|numeric|min:0',
            'monthly_discount' => 'nullable|numeric|min:0',
            'referral_source' => 'nullable|string|max:150',
        ]);

        $validated['admission_number'] = Student::generateAdmissionNumber();
        $validated['roll_number'] = strtoupper(substr($validated['program'], 0, 2)).'-'.rand(10, 99);
        $validated['section'] = $validated['section'] ?? 'A';
        $validated['status'] = 'active';
        $validated['photo_url'] = 'https://api.dicebear.com/7.x/bottts/svg?seed='.urlencode($validated['first_name']).'&backgroundColor=ffd54f';
        $validated['monthly_tuition_fee'] = $validated['monthly_tuition_fee'] ?? 6500.00;
        $validated['has_daycare'] = $request->boolean('has_daycare');
        $validated['monthly_daycare_fee'] = $validated['has_daycare'] ? ($validated['monthly_daycare_fee'] ?? 6000.00) : 0.00;
        $validated['has_transport'] = $request->boolean('has_transport');
        $validated['monthly_transport_fee'] = $validated['has_transport'] ? ($validated['monthly_transport_fee'] ?? 3200.00) : 0.00;
        $validated['monthly_discount'] = $validated['monthly_discount'] ?? 0.00;
        $validated['is_billing_active'] = true;

        $student = Student::create($validated);

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student {$student->full_name} enrolled with Admission No: {$student->admission_number}. Configured for monthly billing (₹".number_format($student->total_monthly_fee, 2).'/month)!');
    }

    public function show(Student $student): View
    {
        $student->load(['inquiry', 'subscriptions.plan', 'invoices' => function ($q) {
            $q->latest('created_at');
        }, 'attendances' => function ($q) {
            $q->latest('date')->take(10);
        }]);

        $availablePlans = SubscriptionPlan::where('is_active', true)->get();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $currentMonthStatus = $student->getMonthlyFeeStatus($currentMonth, $currentYear);

        return view('admin.students.show', compact('student', 'availablePlans', 'currentMonthStatus', 'currentMonth', 'currentYear'));
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'blood_group' => 'nullable|string|max:10',
            'program' => 'required|string|max:100',
            'section' => 'nullable|string|max:10',
            'father_name' => 'required|string|max:150',
            'father_phone' => 'required|string|max:30',
            'father_email' => 'nullable|email|max:150',
            'mother_name' => 'nullable|string|max:150',
            'mother_phone' => 'nullable|string|max:30',
            'mother_email' => 'nullable|email|max:150',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_contact_phone' => 'nullable|string|max:30',
            'medical_notes' => 'nullable|string',
            'status' => 'required|in:active,inactive,graduated,transferred',
            // Billing updates
            'monthly_tuition_fee' => 'required|numeric|min:0',
            'has_daycare' => 'nullable|boolean',
            'monthly_daycare_fee' => 'nullable|numeric|min:0',
            'has_transport' => 'nullable|boolean',
            'monthly_transport_fee' => 'nullable|numeric|min:0',
            'monthly_discount' => 'nullable|numeric|min:0',
            'billing_cycle_day' => 'required|integer|min:1|max:28',
            'is_billing_active' => 'nullable|boolean',
        ]);

        $validated['has_daycare'] = $request->boolean('has_daycare');
        $validated['has_transport'] = $request->boolean('has_transport');
        $validated['is_billing_active'] = $request->boolean('is_billing_active');

        $student->update($validated);

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student profile for {$student->full_name} updated successfully.");
    }

    public function idCard(Student $student): View
    {
        return view('admin.students.id-card', compact('student'));
    }

    public function attendanceIndex(Request $request): View
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        $program = $request->query('program');

        $query = Student::where('status', 'active');
        if ($program && $program !== 'all') {
            $query->where('program', $program);
        }
        $students = $query->orderBy('first_name')->get();

        $attendances = Attendance::where('date', $date)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('admin.attendance.index', compact('students', 'attendances', 'date', 'program'));
    }

    public function bulkMarkAttendance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,half_day',
            'attendance.*.remarks' => 'nullable|string|max:255',
        ]);

        $date = $validated['date'];

        foreach ($validated['attendance'] as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'remarks' => $data['remarks'] ?? null,
                    'check_in_time' => in_array($data['status'], ['present', 'late', 'half_day']) ? '08:45:00' : null,
                    'check_out_time' => in_array($data['status'], ['present']) ? '13:00:00' : null,
                ]
            );
        }

        return redirect()->back()->with('success', "Attendance for {$date} saved successfully!");
    }
}
