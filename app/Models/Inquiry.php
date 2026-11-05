<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_number',
        'parent_name',
        'email',
        'phone',
        'child_name',
        'child_age',
        'child_gender',
        'child_dob',
        'program_interested',
        'branch',
        'referral_source',
        'referral_details',
        'address',
        'city',
        'pincode',
        'preferred_visit_date',
        'message',
        'status',
        'converted_student_id',
        'counselor_notes',
        'last_contacted_at',
    ];

    protected function casts(): array
    {
        return [
            'child_dob' => 'date',
            'preferred_visit_date' => 'date',
            'last_contacted_at' => 'datetime',
        ];
    }

    public function convertedStudent(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'converted_student_id');
    }

    public static function generateInquiryNumber(): string
    {
        $prefix = 'INQ-'.date('Y').'-';
        $last = self::where('inquiry_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $seq = 1;
        if ($last) {
            $parts = explode('-', $last->inquiry_number);
            $seq = intval(end($parts)) + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
