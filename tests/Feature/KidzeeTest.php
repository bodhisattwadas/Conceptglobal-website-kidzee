<?php

namespace Tests\Feature;

use App\Models\AdmissionApplication;
use App\Models\Inquiry;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class KidzeeTest extends TestCase
{
    public function test_homepage_loads_successfully_with_kidzee_branding_and_opening_date(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('kidzee');
        $response->assertSee('November 20');
        $response->assertSee('Pentamind');
        $response->assertSee('Nuvo');
        $response->assertSee('Playgroup');
        $response->assertSee('Nursery');
    }

    public function test_programs_and_subscriptions_pages_render(): void
    {
        $responseProg = $this->get('/programs');
        $responseProg->assertStatus(200);
        $responseProg->assertSee('Nuvo Program');

        $responseSub = $this->get('/subscriptions');
        $responseSub->assertStatus(200);
        $responseSub->assertSee('Day Care');
    }

    public function test_parent_can_submit_inquiry_query(): void
    {
        $payload = [
            'parent_name' => 'Kavita Chawla',
            'phone' => '+91 99887 66554',
            'email' => 'kavita@example.com',
            'child_name' => 'Vivaan Chawla',
            'child_age' => '2.5 years',
            'program_interested' => 'Playgroup',
            'referral_source' => 'Google Search',
            'referral_details' => 'Found Kidzee website on Google',
            'message' => 'Looking for Playgroup admission for November 20 inauguration.',
        ];

        $response = $this->post('/inquiries', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', [
            'parent_name' => 'Kavita Chawla',
            'phone' => '+91 99887 66554',
            'child_name' => 'Vivaan Chawla',
            'program_interested' => 'Playgroup',
            'referral_source' => 'Google Search',
            'status' => 'new',
        ]);
    }

    public function test_parent_can_submit_admission_form_and_it_generates_invoice(): void
    {
        $payload = [
            'child_first_name' => 'Samar',
            'child_last_name' => 'Gupta',
            'gender' => 'Male',
            'dob' => '2023-04-15',
            'blood_group' => 'O+',
            'program_applied' => 'Nursery',
            'father_name' => 'Manoj Gupta',
            'father_phone' => '+91 98111 22334',
            'father_email' => 'manoj@example.com',
            'address' => 'Flat 502, Green Glen Layout, Bellandur',
            'city' => 'Bengaluru',
            'transport_required' => 1,
            'daycare_required' => 1,
        ];

        $response = $this->post('/admissions/apply', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admission_applications', [
            'child_first_name' => 'Samar',
            'father_name' => 'Manoj Gupta',
            'program_applied' => 'Nursery',
            'status' => 'submitted',
        ]);

        $app = AdmissionApplication::where('father_phone', '+91 98111 22334')->first();
        $this->assertNotNull($app);

        // Verification of auto-generated invoice
        $this->assertDatabaseHas('invoices', [
            'admission_application_id' => $app->id,
            'invoice_type' => 'admission',
            'status' => 'pending',
        ]);
    }

    public function test_parent_can_track_admission_application_by_number_and_phone(): void
    {
        $response = $this->get('/track-application?query=KDZ-APP-2026-101');
        $response->assertStatus(200);
        $response->assertSee('KDZ-APP-2026-101');
        $response->assertSee('Reyansh Sengupta');

        $responsePhone = $this->get('/track-application?query=+91 98712 34567');
        $responsePhone->assertStatus(200);
        $responsePhone->assertSee('Reyansh Sengupta');
    }

    public function test_login_page_renders_cleanly_without_demo_placeholders_or_prefilled_credentials(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Administrator Portal');
        $response->assertSee('Admin Email Address');
        $response->assertSee('Sign In as Administrator');

        // Confirm all demo placeholders and hardcoded credentials are removed for live production
        $response->assertDontSee('Demo: admin@kidzee.com');
        $response->assertDontSee('admin123');
        $response->assertDontSee('Staff Email Address');
    }

    public function test_non_admin_cannot_access_admin_portal(): void
    {
        $nonAdmin = User::create([
            'name' => 'General Visitor',
            'email' => 'visitor@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $loginResponse = $this->post('/admin/login', [
            'email' => 'visitor@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertSessionHasErrors('email');
        $this->assertGuest();

        $directResponse = $this->actingAs($nonAdmin)->get('/admin');
        $directResponse->assertRedirect('/admin/login');

        $nonAdmin->delete();
    }

    public function test_admin_authentication_and_dashboard_access(): void
    {
        $responseLogin = $this->post('/admin/login', [
            'email' => 'admin@kidzee.com',
            'password' => 'admin123',
        ]);

        $responseLogin->assertRedirect('/admin');

        $admin = User::where('email', 'admin@kidzee.com')->first();
        $this->assertNotNull($admin);

        $responseDash = $this->actingAs($admin)->get('/admin');
        $responseDash->assertStatus(200);
        $responseDash->assertSee('Campus Overview');
        $responseDash->assertSee('Inquiries');
        $responseDash->assertSee('Admission Forms');
        $responseDash->assertSee('Subscriptions');
        $responseDash->assertSee('Days');
        $responseDash->assertSee('Hours');
        $responseDash->assertSee('Mins');
    }

    public function test_admin_can_update_inquiry_and_convert_to_application(): void
    {
        $admin = User::where('email', 'admin@kidzee.com')->first();
        $inquiry = Inquiry::create([
            'inquiry_number' => Inquiry::generateInquiryNumber(),
            'parent_name' => 'Shalini Rao',
            'phone' => '+91 97777 88888',
            'child_name' => 'Tanya Rao',
            'program_interested' => 'Nursery',
            'status' => 'new',
        ]);

        $responseUpdate = $this->actingAs($admin)->put("/admin/inquiries/{$inquiry->id}/status", [
            'status' => 'campus_visit_scheduled',
            'counselor_notes' => 'Parent called back. Visit on Nov 15.',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'status' => 'campus_visit_scheduled',
        ]);

        $responseConvert = $this->actingAs($admin)->post("/admin/inquiries/{$inquiry->id}/convert");
        $responseConvert->assertRedirect();
        $this->assertDatabaseHas('admission_applications', [
            'father_name' => 'Shalini Rao',
            'program_applied' => 'Nursery',
        ]);
    }

    public function test_admin_can_enroll_applicant_into_student_database(): void
    {
        $admin = User::where('email', 'admin@kidzee.com')->first();
        $app = AdmissionApplication::where('status', 'approved')->first();

        if (! $app) {
            $app = AdmissionApplication::create([
                'application_number' => AdmissionApplication::generateApplicationNumber(),
                'child_first_name' => 'Daksh',
                'child_last_name' => 'Malhotra',
                'gender' => 'Male',
                'dob' => '2023-01-10',
                'program_applied' => 'Playgroup',
                'father_name' => 'Alok Malhotra',
                'father_phone' => '+91 98888 11111',
                'address' => 'HSR Layout',
                'city' => 'Bengaluru',
                'status' => 'approved',
            ]);
        }

        $response = $this->actingAs($admin)->post("/admin/admissions/{$app->id}/enroll");
        $response->assertRedirect();

        $this->assertDatabaseHas('students', [
            'admission_application_id' => $app->id,
            'first_name' => $app->child_first_name,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_create_invoice_and_record_payment_and_view_receipt(): void
    {
        $admin = User::where('email', 'admin@kidzee.com')->first();
        $student = Student::first();

        // Create invoice
        $responseCreate = $this->actingAs($admin)->post('/admin/invoices', [
            'student_id' => $student->id,
            'parent_name' => $student->father_name,
            'parent_phone' => $student->father_phone,
            'title' => 'Testing Term 2 Tuition',
            'invoice_type' => 'tuition',
            'subtotal' => 20000,
            'discount' => 1000,
            'due_date' => Carbon::now()->addDays(10)->toDateString(),
        ]);

        $responseCreate->assertRedirect();
        $invoice = Invoice::where('title', 'Testing Term 2 Tuition')->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(19000, $invoice->total_amount);

        // Record payment
        $responsePay = $this->actingAs($admin)->post("/admin/invoices/{$invoice->id}/payments", [
            'amount' => 19000,
            'payment_method' => 'upi',
            'transaction_reference' => 'UPI/9988776655/TEST',
            'payment_date' => Carbon::today()->toDateString(),
        ]);

        $responsePay->assertRedirect();
        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);

        // Check printable receipt
        $responseReceipt = $this->actingAs($admin)->get("/admin/invoices/{$invoice->id}/receipt");
        $responseReceipt->assertStatus(200);
        $responseReceipt->assertSee('Official Fee Receipt');
        $responseReceipt->assertSee($invoice->invoice_number);
    }

    public function test_admin_can_assign_subscription_and_bulk_mark_attendance(): void
    {
        $admin = User::where('email', 'admin@kidzee.com')->first();
        $student = Student::first();
        $plan = SubscriptionPlan::first();

        // Assign subscription
        $responseSub = $this->actingAs($admin)->post('/admin/subscriptions/assign', [
            'student_id' => $student->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => Carbon::today()->toDateString(),
            'auto_renew' => 1,
        ]);
        $responseSub->assertRedirect();

        // Bulk attendance
        $today = Carbon::today()->toDateString();
        $responseAtt = $this->actingAs($admin)->post('/admin/attendance/bulk-mark', [
            'date' => $today,
            'attendance' => [
                $student->id => [
                    'status' => 'present',
                    'remarks' => 'Active and cheerful',
                ],
            ],
        ]);
        $responseAtt->assertRedirect();

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'date' => $today,
            'status' => 'present',
        ]);
    }

    public function test_complete_inquiry_lead_conversion_to_admitted_student_and_monthly_billing_flow(): void
    {
        $admin = User::where('email', 'admin@kidzee.com')->first();
        $testPhone = '+91 91234 99999';

        // Clean up previous runs if any
        Inquiry::where('phone', $testPhone)->delete();
        Student::where('father_phone', $testPhone)->delete();

        // 1. Parent submits detailed inquiry with "Where they learnt about us"
        $inquiryResponse = $this->post('/inquiries', [
            'parent_name' => 'Dr. Ananya Roy',
            'phone' => $testPhone,
            'email' => 'ananya.roy@example.com',
            'child_name' => 'Aarav Roy',
            'child_age' => '3 years',
            'child_gender' => 'Male',
            'child_dob' => '2023-08-15',
            'program_interested' => 'Nursery',
            'referral_source' => 'Word of Mouth',
            'referral_details' => 'Recommended by Dr. Priya in neighborhood',
            'address' => 'Villa 12, Palm Meadows, Whitefield',
            'city' => 'Bengaluru',
            'pincode' => '560066',
            'message' => 'Interested in Nursery + Daycare after Nov 20 launch.',
        ]);

        $inquiryResponse->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'parent_name' => 'Dr. Ananya Roy',
            'phone' => $testPhone,
            'referral_source' => 'Word of Mouth',
            'referral_details' => 'Recommended by Dr. Priya in neighborhood',
        ]);

        $inquiry = Inquiry::where('phone', $testPhone)->first();
        $this->assertNotNull($inquiry);

        // 2. Admin views inquiry conversion form
        $responseConvertForm = $this->actingAs($admin)->get("/admin/inquiries/{$inquiry->id}/convert-student");
        $responseConvertForm->assertStatus(200);
        $responseConvertForm->assertSee('Admit Student');
        $responseConvertForm->assertSee('Dr. Ananya Roy');

        // 3. Admin converts lead to admitted student with monthly tuition + daycare setup
        $convertResponse = $this->actingAs($admin)->post("/admin/inquiries/{$inquiry->id}/convert-student", [
            'first_name' => 'Aarav',
            'last_name' => 'Roy',
            'gender' => 'Male',
            'dob' => '2023-08-15',
            'blood_group' => 'B+',
            'program' => 'Nursery',
            'section' => 'A',
            'admission_date' => Carbon::today()->toDateString(),
            'father_name' => 'Dr. Ananya Roy',
            'father_phone' => $testPhone,
            'father_email' => 'ananya.roy@example.com',
            'address' => 'Villa 12, Palm Meadows, Whitefield, Bengaluru - 560066',
            'monthly_tuition_fee' => 7000.00,
            'has_daycare' => 1,
            'monthly_daycare_fee' => 6000.00,
            'has_transport' => 0,
            'monthly_transport_fee' => 0.00,
            'monthly_discount' => 500.00,
            'billing_cycle_day' => 1,
            'generate_kit_invoice' => 1,
            'kit_fee' => 5000.00,
        ]);

        $convertResponse->assertRedirect();

        $inquiry->refresh();
        $this->assertEquals('converted', $inquiry->status);
        $this->assertNotNull($inquiry->converted_student_id);

        $student = Student::find($inquiry->converted_student_id);
        $this->assertNotNull($student);
        $this->assertEquals('Aarav', $student->first_name);
        $this->assertEquals('Roy', $student->last_name);
        $this->assertEquals('Nursery', $student->program);
        $this->assertEquals(7000.00, (float) $student->monthly_tuition_fee);
        $this->assertTrue((bool) $student->has_daycare);
        $this->assertEquals(6000.00, (float) $student->monthly_daycare_fee);
        $this->assertEquals(500.00, (float) $student->monthly_discount);
        $this->assertTrue((bool) $student->is_billing_active);
        $this->assertEquals('Word of Mouth', $student->referral_source);
        $this->assertEquals(12500.00, $student->total_monthly_fee); // 7000 + 6000 - 500 = 12500

        // 4. Verify student profile renders properly and appears with 'unbilled' flag for the month
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $this->assertEquals('unbilled', $student->getMonthlyFeeStatus($month, $year)['status']);

        $studentShowResponse = $this->actingAs($admin)->get(route('admin.students.show', $student));
        $studentShowResponse->assertStatus(200);
        $studentShowResponse->assertSee('Aarav Roy');
        $studentShowResponse->assertSee('Unbilled');
        $studentShowResponse->assertSee('Bill Now');

        // 5. Month-end fee bill generation
        $singleBillResponse = $this->actingAs($admin)->post(route('admin.billing.monthly.single', $student), [
            'month' => $month,
            'year' => $year,
        ]);
        $singleBillResponse->assertRedirect();

        // Invoice is generated with recurring monthly flags
        $this->assertDatabaseHas('invoices', [
            'student_id' => $student->id,
            'billing_month' => $month,
            'billing_year' => $year,
            'is_recurring_monthly' => true,
            'total_amount' => 12500.00,
            'status' => 'pending',
        ]);

        $monthlyInvoice = $student->getMonthlyInvoice($month, $year);
        $this->assertNotNull($monthlyInvoice);
        $this->assertEquals('pending', $student->getMonthlyFeeStatus($month, $year)['status']);

        // 6. Admin records quick monthly fee payment
        $quickPayResponse = $this->actingAs($admin)->post(route('admin.billing.monthly.pay', $monthlyInvoice), [
            'amount' => 12500.00,
            'payment_method' => 'upi',
            'payment_date' => Carbon::today()->toDateString(),
            'transaction_reference' => 'UPI/NOV20/KIDZEE/AARAV',
            'notes' => 'Received via Google Pay QR at front desk',
        ]);

        $quickPayResponse->assertRedirect();
        $monthlyInvoice->refresh();
        $this->assertEquals('paid', $monthlyInvoice->status);
        $this->assertEquals(0, $monthlyInvoice->balance_due);

        // Fee flag is now verified as 'paid'
        $this->assertEquals('paid', $student->getMonthlyFeeStatus($month, $year)['status']);

        // Check Monthly Billing Console displays student and payment
        $consoleResponse = $this->actingAs($admin)->get("/admin/billing/monthly?month={$month}&year={$year}&status=paid");
        $consoleResponse->assertStatus(200);
        $consoleResponse->assertSee('Aarav Roy');
        $consoleResponse->assertSee('Paid');
    }
}
