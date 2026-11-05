<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_application_id',
        'inquiry_id',
        'admission_number',
        'roll_number',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'blood_group',
        'program',
        'section',
        'admission_date',
        'father_name',
        'father_phone',
        'father_email',
        'mother_name',
        'mother_phone',
        'mother_email',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'photo_url',
        'medical_notes',
        'monthly_tuition_fee',
        'has_daycare',
        'monthly_daycare_fee',
        'has_transport',
        'monthly_transport_fee',
        'monthly_discount',
        'billing_cycle_day',
        'is_billing_active',
        'referral_source',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'admission_date' => 'date',
            'monthly_tuition_fee' => 'decimal:2',
            'has_daycare' => 'boolean',
            'monthly_daycare_fee' => 'decimal:2',
            'has_transport' => 'boolean',
            'monthly_transport_fee' => 'decimal:2',
            'monthly_discount' => 'decimal:2',
            'is_billing_active' => 'boolean',
        ];
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(AdmissionApplication::class, 'admission_application_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(StudentSubscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(StudentSubscription::class)->where('status', 'active');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Compute total net monthly billable amount for this student
     */
    public function getTotalMonthlyFeeAttribute(): float
    {
        $tuition = (float) $this->monthly_tuition_fee;
        $daycare = $this->has_daycare ? (float) $this->monthly_daycare_fee : 0;
        $transport = $this->has_transport ? (float) $this->monthly_transport_fee : 0;
        $discount = (float) $this->monthly_discount;

        return max(0, ($tuition + $daycare + $transport) - $discount);
    }

    /**
     * Get invoice for a specific billing month & year
     */
    public function getMonthlyInvoice(int $month, int $year): ?Invoice
    {
        return $this->invoices()
            ->where('billing_month', $month)
            ->where('billing_year', $year)
            ->first();
    }

    /**
     * Get fee payment status flag for a specific month
     * Returns: 'paid', 'pending', 'overdue', 'unbilled'
     */
    public function getMonthlyFeeStatus(int $month, int $year): array
    {
        $invoice = $this->getMonthlyInvoice($month, $year);

        if (! $invoice) {
            return [
                'status' => 'unbilled',
                'label' => 'Unbilled',
                'badge' => 'bg-slate-100 text-slate-600 border-slate-200',
                'invoice' => null,
                'amount' => $this->total_monthly_fee,
            ];
        }

        if ($invoice->status === 'paid') {
            return [
                'status' => 'paid',
                'label' => 'Paid',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'invoice' => $invoice,
                'amount' => (float) $invoice->total_amount,
                'paid_at' => $invoice->paid_at,
            ];
        }

        if (Carbon::now()->isAfter($invoice->due_date)) {
            return [
                'status' => 'overdue',
                'label' => 'Overdue',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
                'invoice' => $invoice,
                'amount' => (float) $invoice->balance_due,
            ];
        }

        return [
            'status' => 'pending',
            'label' => 'Due / Pending',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'invoice' => $invoice,
            'amount' => (float) $invoice->balance_due,
        ];
    }

    public static function generateAdmissionNumber(): string
    {
        $prefix = 'KZ-'.date('Y').'-';
        $last = self::where('admission_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $seq = 1;
        if ($last) {
            $parts = explode('-', $last->admission_number);
            $seq = intval(end($parts)) + 1;
        }

        return $prefix.str_pad((string) $seq, 3, '0', STR_PAD_LEFT);
    }
}
