<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\Inquiry;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentSubscription;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $openingDate = Carbon::parse('2026-11-20 09:00:00');
        $now = Carbon::now();

        if ($now->greaterThanOrEqualTo($openingDate)) {
            $daysRemaining = 0;
            $hoursRemaining = 0;
            $minsRemaining = 0;
            $daysToOpening = 0;
        } else {
            $diff = $now->diff($openingDate);
            $daysRemaining = (int) $diff->days;
            $hoursRemaining = (int) $diff->h;
            $minsRemaining = (int) $diff->i;
            $daysToOpening = $daysRemaining;
        }

        $totalQueries = Inquiry::count();
        $newQueries = Inquiry::where('status', 'new')->count();

        $totalApplications = AdmissionApplication::count();
        $pendingApplications = AdmissionApplication::where('status', 'submitted')->orWhere('status', 'under_review')->count();

        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();

        $totalFeeCollected = Payment::where('status', 'completed')->sum('amount');
        $pendingFeeAmount = Invoice::where('status', 'pending')->orWhere('status', 'partially_paid')->sum('total_amount') - Invoice::where('status', 'partially_paid')->sum('paid_amount');

        $activeSubscriptionsCount = StudentSubscription::where('status', 'active')->count();

        $recentInquiries = Inquiry::latest()->take(5)->get();
        $recentApplications = AdmissionApplication::latest()->take(5)->get();
        $recentInvoices = Invoice::with('student', 'application')->latest()->take(5)->get();

        // Program distribution
        $programStats = Student::selectRaw('program, count(*) as count')
            ->groupBy('program')
            ->pluck('count', 'program')
            ->toArray();

        return view('admin.dashboard', compact(
            'openingDate',
            'daysToOpening',
            'daysRemaining',
            'hoursRemaining',
            'minsRemaining',
            'totalQueries',
            'newQueries',
            'totalApplications',
            'pendingApplications',
            'totalStudents',
            'activeStudents',
            'totalFeeCollected',
            'pendingFeeAmount',
            'activeSubscriptionsCount',
            'recentInquiries',
            'recentApplications',
            'recentInvoices',
            'programStats'
        ));
    }
}
