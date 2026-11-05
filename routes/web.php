<?php

use App\Http\Controllers\Admin\AdmissionController as AdminAdmissionController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\MonthlyBillingController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Parent-Facing Routes (Kidzee Preschool Portal)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programs', [HomeController::class, 'programs'])->name('programs');
Route::get('/subscriptions', [HomeController::class, 'subscriptions'])->name('subscriptions');

// Queries / Leads
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

// Admission Application Form & Live Status Tracking
Route::get('/admissions/apply', [AdmissionController::class, 'create'])->name('admissions.apply');
Route::post('/admissions/apply', [AdmissionController::class, 'store'])->name('admissions.store');
Route::get('/track-application', [AdmissionController::class, 'track'])->name('admissions.track');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Dashboard & Management Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        // Feature 1: Query (Inquiries / Leads)
        Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::put('/inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.status');
        Route::get('/inquiries/{inquiry}/convert-student', [AdminInquiryController::class, 'showConvertForm'])->name('inquiries.convertForm');
        Route::post('/inquiries/{inquiry}/convert-student', [AdminInquiryController::class, 'convertToStudent'])->name('inquiries.convertToStudent');
        Route::post('/inquiries/{inquiry}/convert', [AdminInquiryController::class, 'convertToApplication'])->name('inquiries.convert');
        Route::delete('/inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

        // Feature 2: Admission Form & Billing
        Route::get('/admissions', [AdminAdmissionController::class, 'index'])->name('admissions.index');
        Route::get('/admissions/{application}', [AdminAdmissionController::class, 'show'])->name('admissions.show');
        Route::put('/admissions/{application}/status', [AdminAdmissionController::class, 'updateStatus'])->name('admissions.status');
        Route::post('/admissions/{application}/enroll', [AdminAdmissionController::class, 'enrollAsStudent'])->name('admissions.enroll');

        // Month-End & Monthly Billing Generation Engine
        Route::get('/billing/monthly', [MonthlyBillingController::class, 'index'])->name('billing.monthly');
        Route::post('/billing/monthly/bulk-generate', [MonthlyBillingController::class, 'generateBulk'])->name('billing.monthly.bulk');
        Route::post('/billing/monthly/student/{student}/generate', [MonthlyBillingController::class, 'generateSingle'])->name('billing.monthly.single');
        Route::post('/billing/monthly/invoice/{invoice}/quick-pay', [MonthlyBillingController::class, 'quickPayment'])->name('billing.monthly.pay');

        // General Billing & Invoices
        Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [AdminInvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [AdminInvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}', [AdminInvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/receipt', [AdminInvoiceController::class, 'printReceipt'])->name('invoices.receipt');
        Route::post('/invoices/{invoice}/payments', [AdminInvoiceController::class, 'recordPayment'])->name('invoices.payment');

        // Feature 3: Subscription Engine
        Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions/plans', [AdminSubscriptionController::class, 'storePlan'])->name('subscriptions.plans.store');
        Route::post('/subscriptions/assign', [AdminSubscriptionController::class, 'assignStudent'])->name('subscriptions.assign');
        Route::put('/subscriptions/{subscription}/status', [AdminSubscriptionController::class, 'updateStatus'])->name('subscriptions.status');
        Route::post('/subscriptions/{subscription}/generate-invoice', [AdminSubscriptionController::class, 'generateInvoice'])->name('subscriptions.generateInvoice');

        // Feature 4: Student Database
        Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [AdminStudentController::class, 'create'])->name('students.create');
        Route::post('/students', [AdminStudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/edit', [AdminStudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [AdminStudentController::class, 'update'])->name('students.update');
        Route::get('/students/{student}/id-card', [AdminStudentController::class, 'idCard'])->name('students.id-card');

        // Attendance
        Route::get('/attendance', [AdminStudentController::class, 'attendanceIndex'])->name('attendance.index');
        Route::post('/attendance/bulk-mark', [AdminStudentController::class, 'bulkMarkAttendance'])->name('attendance.bulk');
    });
});
