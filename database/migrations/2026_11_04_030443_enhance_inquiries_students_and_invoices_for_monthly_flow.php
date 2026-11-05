<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('referral_source')->nullable()->after('branch'); // Where did they learn about us
            $table->string('referral_details')->nullable()->after('referral_source');
            $table->string('child_gender')->nullable()->after('child_age');
            $table->date('child_dob')->nullable()->after('child_gender');
            $table->text('address')->nullable()->after('message');
            $table->string('city')->nullable()->default('Bengaluru')->after('address');
            $table->string('pincode')->nullable()->after('city');
            $table->foreignId('converted_student_id')->nullable()->constrained('students')->nullOnDelete()->after('status');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('inquiry_id')->nullable()->constrained('inquiries')->nullOnDelete()->after('admission_application_id');
            $table->decimal('monthly_tuition_fee', 10, 2)->default(6500.00)->after('medical_notes');
            $table->boolean('has_daycare')->default(false)->after('monthly_tuition_fee');
            $table->decimal('monthly_daycare_fee', 10, 2)->default(0.00)->after('has_daycare');
            $table->boolean('has_transport')->default(false)->after('monthly_daycare_fee');
            $table->decimal('monthly_transport_fee', 10, 2)->default(0.00)->after('has_transport');
            $table->decimal('monthly_discount', 10, 2)->default(0.00)->after('monthly_transport_fee');
            $table->integer('billing_cycle_day')->default(5)->after('monthly_discount');
            $table->boolean('is_billing_active')->default(true)->after('billing_cycle_day');
            $table->string('referral_source')->nullable()->after('is_billing_active');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('billing_month')->nullable()->after('invoice_type');
            $table->integer('billing_year')->nullable()->after('billing_month');
            $table->decimal('tuition_fee_amount', 10, 2)->default(0.00)->after('subtotal');
            $table->decimal('daycare_fee_amount', 10, 2)->default(0.00)->after('tuition_fee_amount');
            $table->decimal('transport_fee_amount', 10, 2)->default(0.00)->after('daycare_fee_amount');
            $table->decimal('other_fee_amount', 10, 2)->default(0.00)->after('transport_fee_amount');
            $table->boolean('is_recurring_monthly')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'billing_month',
                'billing_year',
                'tuition_fee_amount',
                'daycare_fee_amount',
                'transport_fee_amount',
                'other_fee_amount',
                'is_recurring_monthly',
            ]);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['inquiry_id']);
            $table->dropColumn([
                'inquiry_id',
                'monthly_tuition_fee',
                'has_daycare',
                'monthly_daycare_fee',
                'has_transport',
                'monthly_transport_fee',
                'monthly_discount',
                'billing_cycle_day',
                'is_billing_active',
                'referral_source',
            ]);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropForeign(['converted_student_id']);
            $table->dropColumn([
                'referral_source',
                'referral_details',
                'child_gender',
                'child_dob',
                'address',
                'city',
                'pincode',
                'converted_student_id',
            ]);
        });
    }
};
