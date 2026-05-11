<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Booking - AJL Trans</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background antialiased font-sans">
    @php
        $status = $booking->status;
        $label = match ($status) {
            'menunggu_konfirmasi' => 'Menunggu Verifikasi',
            'dikonfirmasi' => 'Terverifikasi',
            'sedang_berjalan' => 'Sedang Berjalan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $status)),
        };

        $badge = match ($status) {
            'menunggu_konfirmasi' => 'bg-amber-50 text-amber-700 border-amber-200',
            'dikonfirmasi' => 'bg-blue-50 text-blue-700 border-blue-200',
            'sedang_berjalan' => 'bg-primary/10 text-primary border-primary/20',
            'selesai' => 'bg-green-50 text-green-700 border-green-200',
            'dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };

        $steps = [
            'menunggu_konfirmasi' => 'Booking diterima & menunggu verifikasi admin.',
            'dikonfirmasi' => 'Booking sudah diverifikasi oleh admin.',
            'sedang_berjalan' => 'Layanan sedang berjalan.',
            'selesai' => 'Layanan telah selesai. Terima kasih sudah menggunakan AJL Trans.',
            'dibatalkan' => 'Booking dibatalkan. DP tidak dapat dikembalikan.',
        ];

        $note = $booking->catatan_admin ? trim($booking->catatan_admin) : null;
        $testimonialUrl = route('booking.testimonial', $booking->booking_code);

        $flow = [
            'menunggu_konfirmasi' => ['label' => 'Verifikasi', 'desc' => 'Menunggu verifikasi admin'],
            'dikonfirmasi' => ['label' => 'Terverifikasi', 'desc' => 'Booking dikonfirmasi'],
            'sedang_berjalan' => ['label' => 'Berjalan', 'desc' => 'Layanan sedang berjalan'],
            'selesai' => ['label' => 'Selesai', 'desc' => 'Layanan selesai'],
        ];

        $progressIndex = match ($status) {
            'menunggu_konfirmasi' => 1,
            'dikonfirmasi' => 2,
            'sedang_berjalan' => 3,
            'selesai' => 4,
            default => 0,
        };
    @endphp

    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto space-y-6">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-primary text-white p-6">
                    <h1 class="text-2xl font-display font-bold">Tracking Status Booking</h1>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <span class="text-blue-100 text-sm">Kode: <span class="font-bold">{{ $booking->booking_code }}</span></span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider border {{ $badge }}">{{ $label }}</span>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="bg-white border border-slate-200 rounded-2xl p-4">
                        <p class="text-slate-700 font-semibold">Progress</p>
                        @if($status === 'dibatalkan')
                            <div class="mt-3 h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full w-full bg-red-500"></div>
                            </div>
                            <div class="mt-3 flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-red-700">Dibatalkan</p>
                                    <p class="text-xs text-slate-500 mt-1">Booking tidak dilanjutkan.</p>
                                </div>
                            </div>
                        @else
                            <div class="mt-3 h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary" style="width: {{ (int) round(($progressIndex / 4) * 100) }}%"></div>
                            </div>
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($flow as $key => $meta)
                                    @php
                                        $stepPos = match ($key) {
                                            'menunggu_konfirmasi' => 1,
                                            'dikonfirmasi' => 2,
                                            'sedang_berjalan' => 3,
                                            'selesai' => 4,
                                            default => 0,
                                        };
                                        $isActive = $progressIndex === $stepPos;
                                        $isDone = $progressIndex > $stepPos;
                                        $isPending = $progressIndex < $stepPos;
                                    @endphp
                                    <div class="rounded-2xl border p-3 {{ $isActive ? 'border-primary bg-primary/5' : ($isDone ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white') }}">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-xs font-extrabold uppercase tracking-wider {{ $isActive ? 'text-primary' : ($isDone ? 'text-green-700' : 'text-slate-400') }}">{{ $meta['label'] }}</p>
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $isDone ? 'bg-green-600 text-white' : ($isActive ? 'bg-primary text-white' : 'bg-slate-100 text-slate-400') }}">
                                                @if($isDone)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @elseif($isActive)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                                                    </svg>
                                                @else
                                                    <span class="text-[10px] font-bold">{{ $stepPos }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-2">{{ $meta['desc'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4">
                        <p class="text-slate-700 font-semibold">Update Status</p>
                        <p class="text-slate-600 text-sm mt-1">{{ $steps[$status] ?? 'Status sedang diproses.' }}</p>
                        @if($note)
                            <div class="mt-3 bg-white border border-slate-200 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Catatan Admin</p>
                                <p class="text-sm text-slate-700 mt-1 whitespace-pre-line">{{ $note }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white border border-slate-200 rounded-2xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pelanggan</p>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->customer->nama }}</p>
                            <p class="text-xs text-slate-500 mt-1">WhatsApp: {{ $booking->customer->no_wa }}</p>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-2xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Armada</p>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->vehicle->nama }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $booking->vehicle->tipe }} • {{ ucfirst($booking->kota) }}</p>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-2xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</p>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->tanggal_mulai->format('d/m/Y') }}</p>
                            <p class="text-xs text-slate-500 mt-1">Durasi: {{ $booking->kategori === 'sewa_mobil' ? ($booking->durasi . ' Jam') : ($booking->durasi_hari . ' Hari') }}</p>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-2xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pembayaran</p>
                            <p class="font-semibold text-slate-900 mt-1">Total: Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-500 mt-1">DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }} • Sisa: Rp {{ number_format(max($booking->total_harga - $booking->dp_amount, 0), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($status === 'selesai')
                        <div class="bg-green-50 border border-green-200 rounded-2xl p-4">
                            <p class="text-green-800 font-semibold">Terima kasih!</p>
                            <p class="text-green-700 text-sm mt-1">Bantu kami dengan mengisi testimoni.</p>
                            <a href="{{ $testimonialUrl }}" class="inline-flex mt-3 px-4 py-2 rounded-xl bg-green-600 text-white font-bold text-sm hover:bg-green-700 transition-colors">
                                Isi Testimoni
                            </a>
                        </div>
                    @elseif($status === 'dibatalkan')
                        <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
                            <p class="text-red-800 font-semibold">Booking Dibatalkan</p>
                            <p class="text-red-700 text-sm mt-1">Silakan hubungi admin jika ada pertanyaan. DP tidak dapat dikembalikan.</p>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        <a href="{{ route('home') }}" class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition-colors">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
