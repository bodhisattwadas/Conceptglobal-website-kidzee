<?php

namespace Database\Seeders;

use App\Models\AdmissionApplication;
use App\Models\Attendance;
use App\Models\Inquiry;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KidzeeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin & Center Staff Users
        User::firstOrCreate(
            ['email' => 'admin@kidzee.com'],
            [
                'name' => 'Kidzee Campus Director',
                'password' => Hash::make('admin123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admissions@kidzee.com'],
            [
                'name' => 'Pooja Sharma (Admissions)',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Subscription Plans
        $plans = [
            [
                'name' => 'Full-Day Care & Learning Package',
                'code' => 'DAYCARE_FULL',
                'category' => 'daycare',
                'billing_cycle' => 'monthly',
                'price' => 12500.00,
                'description' => 'Complete full-day childcare from 8:30 AM to 6:30 PM with structured learning, supervised naptime, fresh snacks, and daily sensory activities.',
                'features' => [
                    'Hours: 8:30 AM – 6:30 PM',
                    'Nutritious morning & evening snacks included',
                    'Live CCTV access for parents',
                    'Nap pod & hygienic sanitized bedding',
                    'Guided evening cognitive & storytelling sessions',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Half-Day Extended Care',
                'code' => 'DAYCARE_HALF',
                'category' => 'daycare',
                'billing_cycle' => 'monthly',
                'price' => 7500.00,
                'description' => 'Extended care from 12:30 PM to 4:30 PM ideal for working parents seeking engaging post-preschool activities.',
                'features' => [
                    'Hours: 12:30 PM – 4:30 PM',
                    'Warm lunch assistance & afternoon snack',
                    'Art, craft & musical play',
                    'Trained caregiver supervision',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Core Pentamind Term Tuition',
                'code' => 'TUITION_TERM',
                'category' => 'tuition',
                'billing_cycle' => 'term',
                'price' => 24000.00,
                'description' => 'Preschool curriculum tuition fee per term covering Pentamind experiential kits, books, learning materials, and digital interactive apps.',
                'features' => [
                    'Pentamind iLLUME curriculum material kit',
                    'Interactive smart-board digital learning',
                    'Quarterly developmental milestone reports',
                    'Kidzee learning kit bag, stationery & uniform',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'GPS-Tracked Safe Van Transport',
                'code' => 'TRANSPORT_MONTHLY',
                'category' => 'transport',
                'billing_cycle' => 'monthly',
                'price' => 3200.00,
                'description' => 'Door-to-door AC transport with female attendant, child safety belts, speed governor, and real-time parent app tracking.',
                'features' => [
                    'Dedicated female caregiver in vehicle',
                    'Real-time GPS bus tracking app',
                    'Speed-governed vehicle (<40 km/h)',
                    'Doorstep pickup and drop',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Healthy Sprouts Nutritious Meal Plan',
                'code' => 'MEAL_MONTHLY',
                'category' => 'meal',
                'billing_cycle' => 'monthly',
                'price' => 2800.00,
                'description' => 'Pediatrician-curated wholesome organic meals, fresh fruits, fortified milk and warm vegetable purees freshly prepared on campus.',
                'features' => [
                    'Hygienic steam-cooked kitchen',
                    'Fresh organic fruits and sprouted salads',
                    'Zero artificial colors or preservatives',
                    'Custom allergy-safe diet tracking',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Weekend STEM & Junior Robotics Club',
                'code' => 'ACTIVITY_CLUB',
                'category' => 'activity',
                'billing_cycle' => 'monthly',
                'price' => 3500.00,
                'description' => 'Saturday morning hands-on discovery club with LEGO robotics, sensory science experiments, drama, and vocal music.',
                'features' => [
                    'Saturdays 10:00 AM – 1:00 PM',
                    'Kid-friendly robotics & coding blocks',
                    'Sensory clay sculpting & phonics theater',
                    'Showcase exhibitions for parents',
                ],
                'is_active' => true,
            ],
        ];

        $createdPlans = [];
        foreach ($plans as $p) {
            $createdPlans[$p['code']] = SubscriptionPlan::updateOrCreate(
                ['code' => $p['code']],
                $p
            );
        }

        // 3. Parent Inquiries / Leads (Grand Opening Nov 20 interest)
        $inquiries = [
            [
                'inquiry_number' => 'INQ-2026-0001',
                'parent_name' => 'Vikram & Radhika Mehra',
                'email' => 'vikram.mehra@example.com',
                'phone' => '+91 98450 12345',
                'child_name' => 'Aarav Mehra',
                'child_age' => '3 years 2 months',
                'program_interested' => 'Nursery',
                'branch' => 'Kidzee Flagship Campus',
                'preferred_visit_date' => Carbon::now()->addDays(2),
                'message' => 'Interested in nursery admission for November 20 grand opening batch. Need transport inquiry for Indiranagar area.',
                'status' => 'campus_visit_scheduled',
                'counselor_notes' => 'Spoke with father. Very keen on the Pentamind curriculum. Scheduled campus walk-through on Saturday 11 AM.',
                'last_contacted_at' => Carbon::now()->subHours(4),
            ],
            [
                'inquiry_number' => 'INQ-2026-0002',
                'parent_name' => 'Sneha Kulkarni',
                'email' => 'sneha.k@example.com',
                'phone' => '+91 99801 87654',
                'child_name' => 'Ananya Kulkarni',
                'child_age' => '2 years 4 months',
                'program_interested' => 'Playgroup',
                'branch' => 'Kidzee Flagship Campus',
                'preferred_visit_date' => Carbon::now()->addDays(4),
                'message' => 'Looking for Playgroup plus extended Day Care till 5:30 PM. Can we visit on opening day?',
                'status' => 'contacted',
                'counselor_notes' => 'Sent Day Care schedule and fee brochure via WhatsApp. Awaiting confirmation.',
                'last_contacted_at' => Carbon::now()->subDay(),
            ],
            [
                'inquiry_number' => 'INQ-2026-0003',
                'parent_name' => 'Amitabh Sengupta',
                'email' => 'amitabh.s@example.com',
                'phone' => '+91 98712 34567',
                'child_name' => 'Reyansh Sengupta',
                'child_age' => '4 years 6 months',
                'program_interested' => 'Junior KG',
                'branch' => 'Kidzee Flagship Campus',
                'preferred_visit_date' => Carbon::now()->subDays(1),
                'message' => 'Relocating to the city next week. Want to secure seat before the Nov 20 inauguration batch fills up.',
                'status' => 'converted',
                'counselor_notes' => 'Parent visited campus and submitted online admission application form KDZ-APP-2026-101.',
                'last_contacted_at' => Carbon::now()->subHours(8),
            ],
            [
                'inquiry_number' => 'INQ-2026-0004',
                'parent_name' => 'Priyanka Reddy',
                'email' => 'priyanka.reddy@example.com',
                'phone' => '+91 97410 99887',
                'child_name' => 'Ishaan Reddy',
                'child_age' => '1.5 years',
                'program_interested' => 'Nuvo',
                'branch' => 'Kidzee Flagship Campus',
                'preferred_visit_date' => Carbon::now()->addDays(5),
                'message' => 'Need information on Nuvo toddler program and teacher-to-child ratio for early walkers.',
                'status' => 'new',
                'counselor_notes' => 'New lead from website query form. Assign to counselor Pooja.',
                'last_contacted_at' => null,
            ],
            [
                'inquiry_number' => 'INQ-2026-0005',
                'parent_name' => 'Dr. Harish & Shweta Nair',
                'email' => 'harish.nair@example.com',
                'phone' => '+91 98200 44332',
                'child_name' => 'Meera Nair',
                'child_age' => '5 years 1 month',
                'program_interested' => 'Senior KG',
                'branch' => 'Kidzee Flagship Campus',
                'preferred_visit_date' => Carbon::now()->addDays(1),
                'message' => 'Looking for phonics, early math and school-readiness prep in Senior KG. Please share syllabus details.',
                'status' => 'contacted',
                'counselor_notes' => 'Sent Senior KG syllabus. Call back planned for tomorrow 3 PM.',
                'last_contacted_at' => Carbon::now()->subHours(12),
            ],
        ];

        foreach ($inquiries as $inq) {
            Inquiry::updateOrCreate(['inquiry_number' => $inq['inquiry_number']], $inq);
        }

        // 4. Admission Applications
        $apps = [
            [
                'application_number' => 'KDZ-APP-2026-101',
                'child_first_name' => 'Reyansh',
                'child_last_name' => 'Sengupta',
                'gender' => 'Male',
                'dob' => Carbon::now()->subYears(4)->subMonths(6),
                'blood_group' => 'B+',
                'program_applied' => 'Junior KG',
                'academic_year' => '2026-2027',
                'father_name' => 'Amitabh Sengupta',
                'father_phone' => '+91 98712 34567',
                'father_email' => 'amitabh.s@example.com',
                'father_occupation' => 'Software Architect',
                'mother_name' => 'Rina Sengupta',
                'mother_phone' => '+91 98712 34568',
                'mother_email' => 'rina.s@example.com',
                'mother_occupation' => 'Product Designer',
                'address' => '#402, Sunshine Meadows, Green Glen Layout, Bellandur',
                'city' => 'Bengaluru',
                'pincode' => '560103',
                'emergency_contact_name' => 'Amitabh Sengupta',
                'emergency_contact_phone' => '+91 98712 34567',
                'medical_conditions' => 'Mild peanut allergy. Carries antihistamine.',
                'previous_school' => 'Kidzee Little Starters',
                'transport_required' => true,
                'daycare_required' => false,
                'status' => 'admitted',
                'admin_notes' => 'Documents verified. Application approved and enrolled into student database.',
                'approved_at' => Carbon::now()->subDays(3),
            ],
            [
                'application_number' => 'KDZ-APP-2026-102',
                'child_first_name' => 'Kiara',
                'child_last_name' => 'Kapoor',
                'gender' => 'Female',
                'dob' => Carbon::now()->subYears(3)->subMonths(4),
                'blood_group' => 'O+',
                'program_applied' => 'Nursery',
                'academic_year' => '2026-2027',
                'father_name' => 'Rohan Kapoor',
                'father_phone' => '+91 98330 11223',
                'father_email' => 'rohan.kapoor@example.com',
                'father_occupation' => 'Investment Banker',
                'mother_name' => 'Tara Kapoor',
                'mother_phone' => '+91 98330 11224',
                'mother_email' => 'tara.kapoor@example.com',
                'mother_occupation' => 'HR Director',
                'address' => 'Villa 18, Palm Meadows, Whitefield',
                'city' => 'Bengaluru',
                'pincode' => '560066',
                'emergency_contact_name' => 'Tara Kapoor',
                'emergency_contact_phone' => '+91 98330 11224',
                'medical_conditions' => 'None',
                'previous_school' => 'None (First schooling experience)',
                'transport_required' => true,
                'daycare_required' => true,
                'status' => 'admitted',
                'admin_notes' => 'Admission confirmed for opening batch. Fee paid in full.',
                'approved_at' => Carbon::now()->subDays(2),
            ],
            [
                'application_number' => 'KDZ-APP-2026-103',
                'child_first_name' => 'Advait',
                'child_last_name' => 'Verma',
                'gender' => 'Male',
                'dob' => Carbon::now()->subYears(2)->subMonths(3),
                'blood_group' => 'A+',
                'program_applied' => 'Playgroup',
                'academic_year' => '2026-2027',
                'father_name' => 'Nitin Verma',
                'father_phone' => '+91 99000 77889',
                'father_email' => 'nitin.v@example.com',
                'father_occupation' => 'Mechanical Consultant',
                'mother_name' => 'Divya Verma',
                'mother_phone' => '+91 99000 77880',
                'mother_email' => 'divya.v@example.com',
                'mother_occupation' => 'Architect',
                'address' => 'Flat 204, Prestige Ozone, Kadugodi',
                'city' => 'Bengaluru',
                'pincode' => '560067',
                'emergency_contact_name' => 'Nitin Verma',
                'emergency_contact_phone' => '+91 99000 77889',
                'medical_conditions' => 'None. Complete vaccinations up to date.',
                'previous_school' => 'None',
                'transport_required' => false,
                'daycare_required' => true,
                'status' => 'approved',
                'admin_notes' => 'Application approved. Invoice generated for initial registration fee.',
                'approved_at' => Carbon::now()->subDay(),
            ],
            [
                'application_number' => 'KDZ-APP-2026-104',
                'child_first_name' => 'Zoya',
                'child_last_name' => 'Khan',
                'gender' => 'Female',
                'dob' => Carbon::now()->subYears(1)->subMonths(8),
                'blood_group' => 'B+',
                'program_applied' => 'Nuvo',
                'academic_year' => '2026-2027',
                'father_name' => 'Tariq Khan',
                'father_phone' => '+91 98860 33445',
                'father_email' => 'tariq.khan@example.com',
                'father_occupation' => 'Entrepreneur',
                'mother_name' => 'Farah Khan',
                'mother_phone' => '+91 98860 33446',
                'mother_email' => 'farah.khan@example.com',
                'mother_occupation' => 'Fashion Designer',
                'address' => 'House 71, 4th Cross, Koramangala 4th Block',
                'city' => 'Bengaluru',
                'pincode' => '560034',
                'emergency_contact_name' => 'Farah Khan',
                'emergency_contact_phone' => '+91 98860 33446',
                'medical_conditions' => 'None',
                'previous_school' => 'None',
                'transport_required' => false,
                'daycare_required' => true,
                'status' => 'under_review',
                'admin_notes' => 'Awaiting birth certificate scan and immunization card copy.',
                'approved_at' => null,
            ],
        ];

        $createdApps = [];
        foreach ($apps as $appData) {
            $createdApps[$appData['application_number']] = AdmissionApplication::updateOrCreate(
                ['application_number' => $appData['application_number']],
                $appData
            );
        }

        // 5. Enrolled Students
        $students = [
            [
                'admission_application_id' => $createdApps['KDZ-APP-2026-101']->id,
                'admission_number' => 'KZ-2026-001',
                'roll_number' => 'JKG-01',
                'first_name' => 'Reyansh',
                'last_name' => 'Sengupta',
                'gender' => 'Male',
                'dob' => Carbon::now()->subYears(4)->subMonths(6),
                'blood_group' => 'B+',
                'program' => 'Junior KG',
                'section' => 'A',
                'admission_date' => Carbon::now()->subDays(3),
                'father_name' => 'Amitabh Sengupta',
                'father_phone' => '+91 98712 34567',
                'father_email' => 'amitabh.s@example.com',
                'mother_name' => 'Rina Sengupta',
                'mother_phone' => '+91 98712 34568',
                'mother_email' => 'rina.s@example.com',
                'address' => '#402, Sunshine Meadows, Green Glen Layout, Bellandur, Bengaluru',
                'emergency_contact_name' => 'Amitabh Sengupta',
                'emergency_contact_phone' => '+91 98712 34567',
                'photo_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Reyansh&backgroundColor=ffc72c',
                'medical_notes' => 'Mild peanut allergy. Carries antihistamine.',
                'status' => 'active',
            ],
            [
                'admission_application_id' => $createdApps['KDZ-APP-2026-102']->id,
                'admission_number' => 'KZ-2026-002',
                'roll_number' => 'NUR-01',
                'first_name' => 'Kiara',
                'last_name' => 'Kapoor',
                'gender' => 'Female',
                'dob' => Carbon::now()->subYears(3)->subMonths(4),
                'blood_group' => 'O+',
                'program' => 'Nursery',
                'section' => 'A',
                'admission_date' => Carbon::now()->subDays(2),
                'father_name' => 'Rohan Kapoor',
                'father_phone' => '+91 98330 11223',
                'father_email' => 'rohan.kapoor@example.com',
                'mother_name' => 'Tara Kapoor',
                'mother_phone' => '+91 98330 11224',
                'mother_email' => 'tara.kapoor@example.com',
                'address' => 'Villa 18, Palm Meadows, Whitefield, Bengaluru',
                'emergency_contact_name' => 'Tara Kapoor',
                'emergency_contact_phone' => '+91 98330 11224',
                'photo_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Kiara&backgroundColor=b39ddb',
                'medical_notes' => 'No known allergies. Good eater.',
                'status' => 'active',
            ],
            [
                'admission_application_id' => null,
                'admission_number' => 'KZ-2026-003',
                'roll_number' => 'SKG-01',
                'first_name' => 'Aahan',
                'last_name' => 'Patel',
                'gender' => 'Male',
                'dob' => Carbon::now()->subYears(5)->subMonths(2),
                'blood_group' => 'A+',
                'program' => 'Senior KG',
                'section' => 'A',
                'admission_date' => Carbon::now()->subDays(10),
                'father_name' => 'Kunal Patel',
                'father_phone' => '+91 98110 55667',
                'father_email' => 'kunal.patel@example.com',
                'mother_name' => 'Neha Patel',
                'mother_phone' => '+91 98110 55668',
                'mother_email' => 'neha.patel@example.com',
                'address' => 'Apt 501, Lakeview Enclave, HSR Layout, Bengaluru',
                'emergency_contact_name' => 'Kunal Patel',
                'emergency_contact_phone' => '+91 98110 55667',
                'photo_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Aahan&backgroundColor=80deea',
                'medical_notes' => 'None',
                'status' => 'active',
            ],
            [
                'admission_application_id' => null,
                'admission_number' => 'KZ-2026-004',
                'roll_number' => 'PG-01',
                'first_name' => 'Myra',
                'last_name' => 'Joshi',
                'gender' => 'Female',
                'dob' => Carbon::now()->subYears(2)->subMonths(5),
                'blood_group' => 'AB+',
                'program' => 'Playgroup',
                'section' => 'A',
                'admission_date' => Carbon::now()->subDays(8),
                'father_name' => 'Pranav Joshi',
                'father_phone' => '+91 98440 22331',
                'father_email' => 'pranav.joshi@example.com',
                'mother_name' => 'Kavita Joshi',
                'mother_phone' => '+91 98440 22332',
                'mother_email' => 'kavita.joshi@example.com',
                'address' => 'Flat 102, Greenwoods, Sarjapur Road, Bengaluru',
                'emergency_contact_name' => 'Kavita Joshi',
                'emergency_contact_phone' => '+91 98440 22332',
                'photo_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Myra&backgroundColor=c8e6c9',
                'medical_notes' => 'Asthma inhaler in bag as SOS precaution.',
                'status' => 'active',
            ],
        ];

        $createdStudents = [];
        foreach ($students as $stu) {
            $createdStudents[$stu['admission_number']] = Student::updateOrCreate(
                ['admission_number' => $stu['admission_number']],
                $stu
            );
        }

        // 6. Subscriptions for Students
        // Kiara has Day Care Full Day + Transport
        StudentSubscription::firstOrCreate(
            [
                'student_id' => $createdStudents['KZ-2026-002']->id,
                'subscription_plan_id' => $createdPlans['DAYCARE_FULL']->id,
            ],
            [
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'amount' => 12500.00,
                'status' => 'active',
                'auto_renew' => true,
                'notes' => 'Enrolled with Nov 20 early bird welcome discount.',
            ]
        );

        StudentSubscription::firstOrCreate(
            [
                'student_id' => $createdStudents['KZ-2026-002']->id,
                'subscription_plan_id' => $createdPlans['TRANSPORT_MONTHLY']->id,
            ],
            [
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'amount' => 3200.00,
                'status' => 'active',
                'auto_renew' => true,
                'notes' => 'Route: Whitefield Route 3, Morning pickup 8:10 AM.',
            ]
        );

        // Reyansh has Tuition Term + Transport
        StudentSubscription::firstOrCreate(
            [
                'student_id' => $createdStudents['KZ-2026-001']->id,
                'subscription_plan_id' => $createdPlans['TUITION_TERM']->id,
            ],
            [
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->addMonths(3),
                'amount' => 24000.00,
                'status' => 'active',
                'auto_renew' => true,
                'notes' => 'Term 1 tuition with Pentamind learning kit.',
            ]
        );

        StudentSubscription::firstOrCreate(
            [
                'student_id' => $createdStudents['KZ-2026-001']->id,
                'subscription_plan_id' => $createdPlans['TRANSPORT_MONTHLY']->id,
            ],
            [
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'amount' => 3200.00,
                'status' => 'active',
                'auto_renew' => true,
                'notes' => 'Bellandur Route 1.',
            ]
        );

        // 7. Invoices & Billing
        // Invoice 1: Paid Admission fee for Reyansh
        $inv1 = Invoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-1001'],
            [
                'student_id' => $createdStudents['KZ-2026-001']->id,
                'admission_application_id' => $createdApps['KDZ-APP-2026-101']->id,
                'parent_name' => 'Amitabh Sengupta',
                'parent_phone' => '+91 98712 34567',
                'parent_email' => 'amitabh.s@example.com',
                'title' => 'Admission Kit & Term 1 Tuition Fee',
                'invoice_type' => 'admission',
                'subtotal' => 29000.00,
                'discount' => 2000.00, // Early Bird Grand Opening Discount
                'tax' => 0.00,
                'total_amount' => 27000.00,
                'paid_amount' => 27000.00,
                'due_date' => Carbon::now()->subDays(2),
                'status' => 'paid',
                'payment_method' => 'upi',
                'paid_at' => Carbon::now()->subDays(2),
                'notes' => 'Grand Opening Nov 20 early bird concession applied.',
            ]
        );

        Payment::firstOrCreate(
            ['payment_reference' => 'PAY-2026-8001'],
            [
                'invoice_id' => $inv1->id,
                'amount' => 27000.00,
                'payment_method' => 'upi',
                'transaction_reference' => 'UPI/23948293849/HDFC',
                'payment_date' => Carbon::now()->subDays(2),
                'status' => 'completed',
                'notes' => 'Paid via GPay UPI.',
            ]
        );

        // Invoice 2: Paid Daycare + Transport for Kiara
        $inv2 = Invoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-1002'],
            [
                'student_id' => $createdStudents['KZ-2026-002']->id,
                'admission_application_id' => $createdApps['KDZ-APP-2026-102']->id,
                'parent_name' => 'Rohan Kapoor',
                'parent_phone' => '+91 98330 11223',
                'parent_email' => 'rohan.kapoor@example.com',
                'title' => 'Monthly Day Care & Bus Subscription (November 2026)',
                'invoice_type' => 'subscription',
                'subtotal' => 15700.00,
                'discount' => 700.00,
                'tax' => 0.00,
                'total_amount' => 15000.00,
                'paid_amount' => 15000.00,
                'due_date' => Carbon::now()->subDay(),
                'status' => 'paid',
                'payment_method' => 'online',
                'paid_at' => Carbon::now()->subDay(),
                'notes' => 'Online NetBanking payment confirmed.',
            ]
        );

        Payment::firstOrCreate(
            ['payment_reference' => 'PAY-2026-8002'],
            [
                'invoice_id' => $inv2->id,
                'amount' => 15000.00,
                'payment_method' => 'online',
                'transaction_reference' => 'TXN_NB_9923847291',
                'payment_date' => Carbon::now()->subDay(),
                'status' => 'completed',
                'notes' => 'NetBanking via ICICI Bank.',
            ]
        );

        // Invoice 3: Pending Registration Fee for Advait Verma (Approved Application)
        Invoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-1003'],
            [
                'student_id' => null,
                'admission_application_id' => $createdApps['KDZ-APP-2026-103']->id,
                'parent_name' => 'Nitin Verma',
                'parent_phone' => '+91 99000 77889',
                'parent_email' => 'nitin.v@example.com',
                'title' => 'Admission Registration & Welcome Kit Deposit',
                'invoice_type' => 'admission',
                'subtotal' => 10000.00,
                'discount' => 1000.00,
                'tax' => 0.00,
                'total_amount' => 9000.00,
                'paid_amount' => 0.00,
                'due_date' => Carbon::now()->addDays(5),
                'status' => 'pending',
                'payment_method' => null,
                'paid_at' => null,
                'notes' => 'Payment link sent to parent email & SMS.',
            ]
        );

        // 8. Sample Attendance Records for Active Students
        $today = Carbon::today();
        foreach ($createdStudents as $stu) {
            for ($i = 0; $i < 5; $i++) {
                $attDate = $today->copy()->subDays($i);
                if ($attDate->isWeekend()) {
                    continue;
                }
                Attendance::updateOrCreate(
                    [
                        'student_id' => $stu->id,
                        'date' => $attDate->toDateString(),
                    ],
                    [
                        'status' => ($i === 2 && $stu->first_name === 'Kiara') ? 'absent' : 'present',
                        'check_in_time' => '08:45:00',
                        'check_out_time' => ($stu->first_name === 'Kiara') ? '17:30:00' : '12:30:00',
                        'remarks' => ($i === 2 && $stu->first_name === 'Kiara') ? 'Mild fever - rested at home' : 'Active participation in circle time',
                    ]
                );
            }
        }
    }
}
