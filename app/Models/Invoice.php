<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'student_id',
        'admission_application_id',
        'parent_name',
        'parent_phone',
        'parent_email',
        'title',
        'invoice_type',
        'billing_month',
        'billing_year',
        'subtotal',
        'tuition_fee_amount',
        'daycare_fee_amount',
        'transport_fee_amount',
        'other_fee_amount',
        'discount',
        'tax',
        'total_amount',
        'paid_amount',
        'due_date',
        'status',
        'is_recurring_monthly',
        'payment_method',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'billing_month' => 'integer',
            'billing_year' => 'integer',
            'subtotal' => 'decimal:2',
            'tuition_fee_amount' => 'decimal:2',
            'daycare_fee_amount' => 'decimal:2',
            'transport_fee_amount' => 'decimal:2',
            'other_fee_amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'is_recurring_monthly' => 'boolean',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(AdmissionApplication::class, 'admission_application_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getBalanceDueAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->paid_amount);
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-'.date('Y').'-';
        $last = self::where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $seq = 1001;
        if ($last) {
            $parts = explode('-', $last->invoice_number);
            $seq = intval(end($parts)) + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
