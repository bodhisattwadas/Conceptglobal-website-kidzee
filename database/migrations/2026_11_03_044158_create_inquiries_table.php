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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('inquiry_number')->unique();
            $table->string('parent_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('child_name');
            $table->string('child_age')->nullable();
            $table->string('program_interested'); // Nuvo, Playgroup, Nursery, Junior KG, Senior KG, Daycare
            $table->string('branch')->default('Concept Kidzee Campus');
            $table->date('preferred_visit_date')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new'); // new, contacted, campus_visit_scheduled, visited, converted, closed
            $table->text('counselor_notes')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
