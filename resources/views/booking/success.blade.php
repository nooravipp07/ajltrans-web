<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Berhasil - AJL Trans</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background antialiased font-sans">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 text-center">
            <div class="mb-8">
                <img src="{{ $cms['branding']['logo_dark']->value_id ?? '/images/logo-dark.png' }}" class="h-10 mx-auto" alt="Logo">
            </div>
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h1 class="text-3xl font-display font-bold text-slate-900 mb-2">Pemesanan Terkirim!</h1>
            <p class="text-slate-500 mb-8">Terima kasih, {{ $booking->customer->nama }}. Pesanan Anda sedang kami proses.</p>
            
            <div class="bg-slate-50 rounded-2xl p-6 mb-8 text-left space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Kode Booking</span>
                    <span class="font-bold text-primary">{{ $booking->booking_code }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Kendaraan</span>
                    <span class="font-bold text-slate-900">{{ $booking->vehicle->nama }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Status DP</span>
                    <span class="font-bold 
                        @if($booking->dp_status === 'paid') text-green-600
                        @elseif($booking->dp_status === 'confirmed') text-blue-600
                        @else text-orange-600
                        @endif">
                        @if($booking->dp_status === 'paid') Menunggu Konfirmasi
                        @elseif($booking->dp_status === 'confirmed') DP Terkonfirmasi
                        @else Belum Bayar DP
                        @endif
                    </span>
                </div>
                <div class="flex justify-between text-sm border-t border-slate-200 pt-3">
                    <span class="text-slate-500 font-medium">Total Harga</span>
                    <span class="font-bold text-slate-900 text-lg">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-4">
                @if($booking->dp_status === 'paid' || $booking->dp_status === 'confirmed')
                    @php
                        $waService = app(\App\Services\WhatsAppService::class);
                        $dpCategory = match ($booking->kategori) {
                            'sewa_mobil' => 'dp_confirmation_sewa_mobil',
                            'city_tour' => 'dp_confirmation_city_tour',
                            'travel' => 'dp_confirmation_travel',
                            default => 'dp_confirmation',
                        };
                        $waLink = $waService->generateLink($booking, $dpCategory);
                    @endphp
                    <a href="{{ $waLink }}" target="_blank" class="block w-full py-4 bg-green-500 text-white rounded-2xl font-bold text-lg hover:bg-green-600 transition-all shadow-lg shadow-green-500/30">
                        Konfirmasi via WhatsApp
                    </a>
                @else
                    <a href="{{ route('booking.qris', $booking->booking_code) }}" class="block w-full py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                        Lanjutkan Pembayaran DP
                    </a>
                @endif
                
                <a href="/" class="block w-full py-4 text-slate-500 font-medium hover:text-primary transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
