<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'parent_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'child_name' => 'required|string|max:255',
            'child_age' => 'nullable|string|max:100',
            'child_gender' => 'nullable|string|in:Male,Female,Other',
            'child_dob' => 'nullable|date',
            'program_interested' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'referral_source' => 'required|string|max:150',
            'referral_details' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'preferred_visit_date' => 'nullable|date',
            'message' => 'nullable|string|max:1000',
        ]);

        $validated['inquiry_number'] = Inquiry::generateInquiryNumber();
        $validated['status'] = 'new';
        $validated['branch'] = $validated['branch'] ?? 'Concept Kidzee Main Campus';
        $validated['city'] = $validated['city'] ?? 'Bengaluru';

        $inquiry = Inquiry::create($validated);

        return redirect()->back()->with([
            'success' => "Thank you, {$inquiry->parent_name}! Your query #{$inquiry->inquiry_number} has been submitted successfully. Our admissions counselor will contact you shortly regarding the November 20 inauguration batch!",
            'inquiry_number' => $inquiry->inquiry_number,
        ]);
    }
}
