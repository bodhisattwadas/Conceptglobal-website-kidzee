<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdmissionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'child_first_name',
        'child_last_name',
        'gender',
        'dob',
        'blood_group',
        'program_applied',
        'academic_year',
        'father_name',
        'father_phone',
        'father_email',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_email',
        'mother_occupation',
        'address',
        'city',
        'pincode',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_conditions',
        'previous_school',
        'transport_required',
        'daycare_required',
        'status',
        'admin_notes',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'transport_required' => 'boolean',
            'daycare_required' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getChildFullNameAttribute(): string
    {
        return "{$this->child_first_name} {$this->child_last_name}";
    }

    public static function generateApplicationNumber(): string
    {
        $prefix = 'KDZ-APP-'.date('Y').'-';
        $last = self::where('application_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $seq = 101;
        if ($last) {
            $parts = explode('-', $last->application_number);
            $seq = intval(end($parts)) + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
