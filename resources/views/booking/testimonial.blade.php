<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Testimoni - AJL Trans</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background antialiased font-sans">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-primary text-white p-6">
                    <h1 class="text-2xl font-display font-bold">Isi Testimoni</h1>
                    <p class="text-blue-100 text-sm mt-1">Kode Booking: {{ $booking->booking_code }}</p>
                </div>

                <div class="p-6 space-y-6">
                    <div class="bg-slate-50 rounded-2xl p-4">
                        <p class="text-slate-700 font-semibold">Detail Layanan</p>
                        <p class="text-slate-600 text-sm mt-1">{{ $booking->vehicle->nama }} • {{ ucfirst($booking->kota) }} • {{ $booking->tanggal_mulai->format('d/m/Y') }}</p>
                    </div>

                    <form id="testimonialForm" class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Nama</label>
                            <input name="nama" value="{{ $booking->customer->nama }}" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-3" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Kota</label>
                            <input name="kota" value="{{ ucfirst($booking->kota) }}" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-3" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Kendaraan Disewa</label>
                            <input name="kendaraan_disewa" value="{{ $booking->vehicle->nama }}" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-3" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Rating (1-5)</label>
                            <select name="rating" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-3" required>
                                <option value="5" selected>5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Ulasan</label>
                            <textarea name="ulasan_id" rows="5" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="Tulis pengalaman Anda..." required></textarea>
                        </div>

                        <button id="submitBtn" type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                            Kirim Testimoni
                        </button>
                        <p id="statusText" class="text-sm text-slate-500 text-center"></p>
                    </form>

                    <div class="flex gap-3">
                        <a href="{{ route('booking.track', $booking->booking_code) }}" class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors">
                            Kembali ke Status
                        </a>
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition-colors">
                            Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('testimonialForm');
        const submitBtn = document.getElementById('submitBtn');
        const statusText = document.getElementById('statusText');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            submitBtn.disabled = true;
            statusText.textContent = 'Mengirim...';

            const data = new FormData(form);
            const payload = Object.fromEntries(data.entries());

            try {
                const res = await fetch('/api/v1/testimonials', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();
                if (!res.ok) {
                    statusText.textContent = json.message || 'Gagal mengirim testimoni.';
                    submitBtn.disabled = false;
                    return;
                }

                statusText.textContent = 'Terima kasih! Testimoni Anda berhasil dikirim dan menunggu moderasi.';
                form.reset();
            } catch (err) {
                statusText.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            } finally {
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>

