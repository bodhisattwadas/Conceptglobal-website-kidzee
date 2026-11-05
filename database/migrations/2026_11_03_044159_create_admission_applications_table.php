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
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->string('child_first_name');
            $table->string('child_last_name');
            $table->string('gender'); // Male, Female, Other
            $table->date('dob');
            $table->string('blood_group')->nullable();
            $table->string('program_applied'); // Nuvo, Playgroup, Nursery, Junior KG, Senior KG, Daycare
            $table->string('academic_year')->default('2026-2027');

            // Father / Guardian details
            $table->string('father_name');
            $table->string('father_phone');
            $table->string('father_email')->nullable();
            $table->string('father_occupation')->nullable();

            // Mother details
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('mother_occupation')->nullable();

            // Contact & address
            $table->text('address');
            $table->string('city')->default('Bangalore');
            $table->string('pincode')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            // Health & background
            $table->text('medical_conditions')->nullable();
            $table->string('previous_school')->nullable();

            // Additional services
            $table->boolean('transport_required')->default(false);
            $table->boolean('daycare_required')->default(false);

            // Status & Notes
            $table->string('status')->default('submitted'); // submitted, under_review, document_verified, approved, admitted, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
    }
};
