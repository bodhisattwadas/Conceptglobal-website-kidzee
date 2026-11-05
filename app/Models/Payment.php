<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_reference',
        'amount',
        'payment_method',
        'transaction_reference',
        'payment_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public static function generatePaymentReference(): string
    {
        $prefix = 'PAY-'.date('Y').'-';
        $last = self::where('payment_reference', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $seq = 8001;
        if ($last) {
            $parts = explode('-', $last->payment_reference);
            $seq = intval(end($parts)) + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
