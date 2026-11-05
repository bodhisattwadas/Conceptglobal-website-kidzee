<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Official Fee Receipt - {{ $invoice->invoice_number }} | Kidzee</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fredoka:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-brand { font-family: 'Fredoka', cursive, sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .receipt-box { border: 2px solid #000 !important; box-shadow: none !important; margin: 0 auto !important; }
        }
    </style>
</head>
<body class="bg-slate-100 py-8 px-4 min-h-screen text-slate-800">

    <!-- Action Bar -->
    <div class="max-w-2xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Invoice
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-extrabold text-xs shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Print Official Receipt
        </button>
    </div>

    <!-- Official Receipt Sheet -->
    <div class="receipt-box max-w-2xl mx-auto bg-white p-8 sm:p-10 rounded-3xl shadow-xl border-4 border-amber-400 relative">
        
        <!-- Watermark / Stamp simulation -->
        <div class="absolute right-8 top-32 pointer-events-none opacity-10">
            <i class="fa-solid fa-shapes text-9xl text-purple-900"></i>
        </div>

        <!-- School Header -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-amber-400 flex items-center justify-center text-purple-950 font-bold text-3xl shadow">
                    <i class="fa-solid fa-shapes"></i>
                </div>
                <div>
                    <h1 class="font-brand text-3xl font-extrabold text-purple-900 leading-none">
                        kidzee
                    </h1>
                    <p class="text-[11px] font-bold tracking-wider uppercase text-slate-600 font-sans mt-0.5">
                        Concept Global Education Preschool & Day Care
                    </p>
                    <p class="text-[10px] text-slate-500 font-medium">
                        Reg No: KDZ/BLR/2026 • Opening Nov 20, 2026 Batch
                    </p>
                </div>
            </div>

            <div class="text-right">
                <div class="inline-block px-3 py-1 rounded bg-purple-900 text-amber-300 font-bold text-xs uppercase tracking-wider">
                    Official Fee Receipt
                </div>
                <p class="text-xs font-bold text-slate-900 mt-1">Receipt #: {{ $invoice->invoice_number }}</p>
                <p class="text-[11px] text-slate-500">Date: {{ $invoice->paid_at ? $invoice->paid_at->format('d M Y') : date('d M Y') }}</p>
            </div>
        </div>

        <!-- Campus Address & Contacts -->
        <div class="py-2.5 border-b border-slate-200 text-[11px] text-slate-600 flex justify-between">
            <span>Campus: Near Central Park, Green Glen Layout, Bengaluru 560103</span>
            <span>Helpline: +91 98450 12345</span>
        </div>

        <!-- Student & Parent Information -->
        <div class="grid grid-cols-2 gap-4 py-4 border-b border-slate-200 text-xs">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Student Particulars</span>
                <p class="font-bold text-slate-900 text-sm mt-0.5">
                    {{ $invoice->student ? $invoice->student->full_name : ($invoice->application ? $invoice->application->child_full_name : 'N/A') }}
                </p>
                <p class="text-slate-600 mt-0.5">
                    Admission / Reg No: <strong>{{ $invoice->student ? $invoice->student->admission_number : ($invoice->application ? $invoice->application->application_number : 'Direct') }}</strong>
                </p>
                <p class="text-slate-600">
                    Program / Class: <strong>{{ $invoice->student ? $invoice->student->program : ($invoice->application ? $invoice->application->program_applied : 'Preschool') }}</strong>
                </p>
            </div>

            <div class="text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Parent / Guardian</span>
                <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $invoice->parent_name }}</p>
                <p class="text-slate-600 mt-0.5">Phone: {{ $invoice->parent_phone }}</p>
                <p class="text-slate-600">Academic Session: 2026-2027</p>
            </div>
        </div>

        <!-- Line Item Table -->
        <div class="py-4">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b-2 border-slate-900 text-slate-900 uppercase font-bold text-[11px]">
                        <th class="py-2 text-left">Sl.</th>
                        <th class="py-2 text-left">Description / Fee Head</th>
                        <th class="py-2 text-right">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="py-3 font-bold">1</td>
                        <td class="py-3">
                            <span class="font-bold text-slate-900 block">{{ $invoice->title }}</span>
                            <span class="text-[11px] text-slate-500">{{ $invoice->notes ?: 'Kidzee Pentamind Curriculum & Campus Services' }}</span>
                        </td>
                        <td class="py-3 text-right font-bold text-slate-900">
                            {{ number_format($invoice->subtotal, 2) }}
                        </td>
                    </tr>
                    @if($invoice->discount > 0)
                        <tr class="text-emerald-700">
                            <td class="py-2 font-bold">2</td>
                            <td class="py-2 font-medium">Grand Opening Special Concession (Nov 20 Privilege)</td>
                            <td class="py-2 text-right font-bold">- {{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-900 text-sm font-black text-slate-900">
                        <td colspan="2" class="py-2.5 text-right uppercase">Net Total Paid:</td>
                        <td class="py-2.5 text-right text-base text-purple-900">₹{{ number_format($invoice->paid_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Mode & Reference Details -->
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1 my-3">
            <div class="flex justify-between">
                <span>Payment Mode: <strong>{{ strtoupper($invoice->payment_method ?: 'Electronic') }}</strong></span>
                <span>Payment Status: <strong class="text-emerald-700">CLEARED / RECEIVED</strong></span>
            </div>
            @if($invoice->payments->count() > 0)
                <div class="text-[11px] text-slate-500">
                    Payment Ref: {{ $invoice->payments->first()->payment_reference }} • Txn: {{ $invoice->payments->first()->transaction_reference ?: 'N/A' }}
                </div>
            @endif
        </div>

        <!-- Signatures & Seal -->
        <div class="pt-8 mt-6 border-t border-slate-200 flex items-end justify-between text-xs">
            <div class="space-y-1 text-slate-500 text-[11px]">
                <p>• Fees once paid are non-transferable.</p>
                <p>• Computer-generated authentic preschool receipt.</p>
                <p class="font-bold text-purple-900">Welcome to the Kidzee Family!</p>
            </div>

            <div class="text-center space-y-1">
                <div class="w-32 h-10 border-b border-slate-900 mx-auto"></div>
                <span class="block font-bold text-slate-900 text-[11px]">Authorized Signatory</span>
                <span class="block text-[10px] text-slate-400">Concept Kidzee Accounts</span>
            </div>
        </div>

    </div>

</body>
</html>
