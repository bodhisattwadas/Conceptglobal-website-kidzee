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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_application_id')->nullable()->constrained('admission_applications')->nullOnDelete();
            $table->string('admission_number')->unique();
            $table->string('roll_number')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->date('dob');
            $table->string('blood_group')->nullable();
            $table->string('program'); // Nuvo, Playgroup, Nursery, Junior KG, Senior KG, Daycare
            $table->string('section')->default('A');
            $table->date('admission_date');

            // Parents details
            $table->string('father_name');
            $table->string('father_phone');
            $table->string('father_email')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_email')->nullable();

            $table->text('address');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('medical_notes')->nullable();
            $table->string('status')->default('active'); // active, inactive, graduated, transferred
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
