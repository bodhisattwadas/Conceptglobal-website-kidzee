<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Identity Badge - {{ $student->full_name }} | Kidzee</title>

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
        }
    </style>
</head>
<body class="bg-slate-100 py-8 px-4 min-h-screen text-slate-800 flex flex-col items-center">

    <!-- Action Bar -->
    <div class="w-full max-w-xl mb-6 flex items-center justify-between no-print">
        <a href="{{ route('admin.students.show', $student) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Student Profile
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-purple-900 hover:bg-purple-800 text-amber-300 font-extrabold text-xs shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Print ID Badge
        </button>
    </div>

    <!-- Dual ID Card (Front & Back) Layout -->
    <div class="flex flex-col sm:flex-row gap-6 items-center justify-center">

        <!-- FRONT SIDE -->
        <div class="w-72 h-[420px] bg-white rounded-3xl shadow-xl border-4 border-amber-400 overflow-hidden flex flex-col justify-between relative">
            <!-- Top Lanyard Hole Simulation -->
            <div class="absolute top-2 left-1/2 -translate-x-1/2 w-8 h-2 bg-slate-200 rounded-full border border-slate-300"></div>

            <!-- Top Header -->
            <div class="bg-gradient-to-r from-purple-900 to-indigo-950 text-white pt-6 pb-4 px-4 text-center border-b-4 border-amber-400">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-lg bg-amber-400 flex items-center justify-center text-purple-950 font-bold text-sm shadow">
                        <i class="fa-solid fa-shapes"></i>
                    </div>
                    <span class="font-brand text-2xl font-bold tracking-tight">kidzee</span>
                </div>
                <p class="text-[9px] uppercase tracking-wider text-amber-300 font-bold">Concept Global Preschool & Day Care</p>
            </div>

            <!-- Photo & Student Info -->
            <div class="p-4 text-center space-y-2 flex-1 flex flex-col justify-center items-center">
                <img src="{{ $student->photo_url ?: 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($student->first_name) }}" alt="{{ $student->full_name }}" class="w-24 h-24 rounded-2xl bg-amber-100 object-cover shadow-md border-2 border-amber-300">
                
                <h3 class="font-heading text-lg font-bold text-slate-900 leading-tight">{{ $student->full_name }}</h3>
                <span class="inline-block px-3 py-0.5 rounded-full text-xs font-black bg-purple-100 text-purple-900">
                    {{ $student->program }} (Sec {{ $student->section }})
                </span>

                <div class="text-[11px] text-slate-600 space-y-0.5 pt-1">
                    <p>Admission No: <strong class="text-purple-950 font-mono">{{ $student->admission_number }}</strong></p>
                    <p>Blood Group: <strong class="text-rose-600">{{ $student->blood_group ?: 'N/A' }}</strong></p>
                    <p>Emergency: <strong>{{ $student->emergency_contact_phone ?: $student->father_phone }}</strong></p>
                </div>
            </div>

            <!-- Bottom Stripe -->
            <div class="bg-amber-400 text-purple-950 py-1.5 px-3 text-center text-[10px] font-black uppercase tracking-wider">
                Academic Session 2026-2027
            </div>
        </div>

        <!-- BACK SIDE -->
        <div class="w-72 h-[420px] bg-slate-950 text-white rounded-3xl shadow-xl border-4 border-purple-900 overflow-hidden flex flex-col justify-between p-5 relative">
            <div class="text-center space-y-1">
                <span class="text-[10px] font-bold text-amber-300 uppercase tracking-wider">Emergency Protocol & Identification</span>
                <div class="h-0.5 w-12 bg-amber-400 mx-auto"></div>
            </div>

            <div class="space-y-3 text-[11px] text-slate-300">
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Parent Contacts:</span>
                    <p class="font-bold text-white">{{ $student->father_name }} ({{ $student->father_phone }})</p>
                    @if($student->mother_name)
                        <p class="text-slate-300">{{ $student->mother_name }} ({{ $student->mother_phone }})</p>
                    @endif
                </div>

                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Residential Address:</span>
                    <p class="text-slate-300 text-[10px] leading-tight">{{ $student->address }}</p>
                </div>

                @if($student->medical_notes)
                    <div class="p-2 rounded-xl bg-rose-500/20 border border-rose-500/30 text-[10px] text-rose-300">
                        <strong>SOS Note:</strong> {{ $student->medical_notes }}
                    </div>
                @endif
            </div>

            <div class="border-t border-slate-800 pt-3 text-center space-y-2">
                <!-- Barcode simulation -->
                <div class="h-6 flex items-center justify-center gap-1 text-slate-400">
                    <span class="w-1 h-5 bg-white"></span>
                    <span class="w-0.5 h-5 bg-white"></span>
                    <span class="w-1.5 h-5 bg-white"></span>
                    <span class="w-0.5 h-5 bg-white"></span>
                    <span class="w-1 h-5 bg-white"></span>
                    <span class="w-2 h-5 bg-white"></span>
                    <span class="w-0.5 h-5 bg-white"></span>
                    <span class="w-1 h-5 bg-white"></span>
                    <span class="w-0.5 h-5 bg-white"></span>
                    <span class="w-1.5 h-5 bg-white"></span>
                </div>
                <p class="text-[9px] text-slate-400">If found, please return to Concept Kidzee Campus or call +91 98450 12345.</p>
            </div>
        </div>

    </div>

</body>
</html>
