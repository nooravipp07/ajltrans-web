<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran DP - AJL Trans</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background antialiased font-sans">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-primary text-white p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold font-display">Pembayaran DP Booking</h1>
                            <p class="text-blue-100 mt-1">Kode Booking: {{ $booking->booking_code }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-blue-100">Total Tagihan</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- DP Information -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-yellow-800 font-semibold mb-1">Informasi Pembayaran DP</h3>
                                <p class="text-yellow-700 text-sm">
                                    Uang DP Rp {{ number_format($booking->dp_amount, 0, ',', '.') }} untuk {{ str_replace('_', ' ', $booking->kategori) }}.
                                </p>
                                <p class="text-yellow-600 text-xs mt-1">Silakan lakukan pembayaran DP terlebih dahulu untuk melanjutkan proses booking. Jika pemesanan dibatalkan oleh client, DP dinyatakan hangus dan tidak dapat dikembalikan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- QRIS Section -->
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Scan QRIS untuk Pembayaran</h2>
                        
                        @php
                            $qrisSrc = null;
                            if ($qrisImage && $qrisImage->value_id) {
                                $val = $qrisImage->value_id;
                                if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                                    $qrisSrc = $val;
                                } else {
                                    $relative = ltrim((string) $val, '/');
                                    if (str_starts_with($relative, 'storage/')) {
                                        $qrisSrc = url($relative);
                                    } else {
                                        $qrisSrc = url('storage/' . ltrim($relative, '/'));
                                    }
                                }
                            }
                        @endphp

                        @if($qrisSrc)
                            <div class="bg-gray-100 rounded-lg p-4 sm:p-6 max-w-[460px] mx-auto">
                                <img src="{{ $qrisSrc }}" 
                                     alt="QRIS Payment" 
                                     class="w-full aspect-square object-contain">
                            </div>
                            <div class="mt-4 flex justify-center">
                                <a href="{{ $qrisSrc }}" download class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                                    Download QRIS
                                </a>
                            </div>
                        @else
                            <div class="bg-gray-100 rounded-lg p-12 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                <p class="text-gray-500">QRIS Image belum diupload</p>
                            </div>
                        @endif
                    </div>

                    <!-- DP Amount -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-blue-600 text-sm">Jumlah DP yang harus dibayar</p>
                                <p class="text-2xl font-bold text-blue-800">
                                    Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-blue-600 text-sm">Sisa pembayaran</p>
                                <p class="text-lg font-semibold text-blue-800">
                                    Rp {{ number_format($booking->total_harga - $booking->dp_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-2">Cara Pembayaran:</h3>
                        <ol class="text-sm text-gray-600 space-y-1">
                            <li>1. Buka aplikasi e-wallet atau mobile banking Anda</li>
                            <li>2. Scan kode QRIS di atas</li>
                            <li>3. Masukkan nominal DP sesuai yang tertera</li>
                            <li>4. Selesaikan pembayaran</li>
                            <li>5. Screenshot bukti pembayaran</li>
                        </ol>
                    </div>

                    @php
                        $dpProofSrc = null;
                        if ($booking->dp_qris_image) {
                            $val = $booking->dp_qris_image;
                            if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                                $dpProofSrc = $val;
                            } else {
                                $relative = ltrim((string) $val, '/');
                                if (str_starts_with($relative, 'storage/')) {
                                    $dpProofSrc = url($relative);
                                } else {
                                    $dpProofSrc = url('storage/' . ltrim($relative, '/'));
                                }
                            }
                        }
                    @endphp

                    <!-- Upload DP Proof -->
                    <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900">Upload Bukti Pembayaran (Wajib)</h3>
                                <p class="text-xs text-slate-500 mt-1">Upload screenshot/Foto bukti transfer agar tombol konfirmasi aktif.</p>

                                <div class="mt-4 space-y-3">
                                    <input id="dpProofInput" type="file" accept="image/*" class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">

                                    <div id="dpProofPreviewWrap" class="hidden">
                                        <div class="bg-slate-50 rounded-lg p-3">
                                            <img id="dpProofPreview" src="" alt="Bukti Pembayaran" class="w-full max-w-[460px] mx-auto rounded-lg object-contain">
                                        </div>
                                    </div>

                                    @if($dpProofSrc)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                            <p class="text-sm text-green-700 font-medium">Bukti pembayaran sudah diupload.</p>
                                            <a href="{{ $dpProofSrc }}" target="_blank" class="text-xs text-green-700 underline">Lihat bukti pembayaran</a>
                                        </div>
                                    @endif

                                    <div class="flex gap-3">
                                        <button id="uploadDpProofBtn" type="button" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-semibold disabled:opacity-50" disabled>
                                            Upload Bukti
                                        </button>
                                        <div id="dpProofStatus" class="text-sm text-slate-500 flex items-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <button id="confirmDpBtn" onclick="confirmPayment()" 
                                class="flex-1 bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Sudah Bayar - Konfirmasi
                        </button>
                        <a href="{{ route('home') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h3 class="font-semibold text-gray-800 mb-4">Detail Booking</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Nama</p>
                        <p class="font-medium">{{ $booking->customer->nama }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Kendaraan</p>
                        <p class="font-medium">{{ $booking->vehicle->nama }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Kategori</p>
                        <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $booking->kategori)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Mulai</p>
                        <p class="font-medium">{{ $booking->tanggal_mulai->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Durasi</p>
                        <p class="font-medium">
                            @if($booking->kategori === 'sewa_mobil' && $booking->durasi !== null)
                                {{ $booking->durasi }} Jam
                            @else
                                {{ $booking->durasi_hari }} Hari
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function confirmPayment() {
        if (typeof dpProofUploaded !== 'undefined' && !dpProofUploaded) {
            alert('Silakan upload bukti pembayaran terlebih dahulu.');
            return;
        }
        if (confirm('Apakah Anda sudah melakukan pembayaran DP?')) {
            const waWindow = window.open('', '_blank');

            fetch(`/api/v1/booking/{{ $booking->id }}/confirm-dp`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const waLink = data.wa_link;
                    if (waLink) {
                        if (waWindow && !waWindow.closed) {
                            waWindow.location.href = waLink;
                        } else {
                            window.location.href = waLink;
                        }
                        return;
                    }

                    window.location.href = data.redirect_url || '{{ route("booking.success", $booking->booking_code) }}';
                    return;
                }

                if (waWindow && !waWindow.closed) {
                    waWindow.close();
                }
                alert(data.message || 'Gagal mengkonfirmasi pembayaran. Silakan coba lagi.');
            })
            .catch(error => {
                console.error('Error:', error);
                if (waWindow && !waWindow.closed) {
                    waWindow.close();
                }
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    }
    </script>

    <script>
        const existingDpProof = @json($dpProofSrc);

        const dpProofInput = document.getElementById('dpProofInput');
        const dpProofPreviewWrap = document.getElementById('dpProofPreviewWrap');
        const dpProofPreview = document.getElementById('dpProofPreview');
        const uploadDpProofBtn = document.getElementById('uploadDpProofBtn');
        const dpProofStatus = document.getElementById('dpProofStatus');
        const confirmDpBtn = document.getElementById('confirmDpBtn');

        let dpProofUploaded = Boolean(existingDpProof);
        if (dpProofUploaded) {
            confirmDpBtn.disabled = false;
        }

        dpProofInput.addEventListener('change', () => {
            const file = dpProofInput.files && dpProofInput.files[0] ? dpProofInput.files[0] : null;
            if (!file) {
                uploadDpProofBtn.disabled = true;
                dpProofPreviewWrap.classList.add('hidden');
                dpProofStatus.textContent = '';
                return;
            }

            const url = URL.createObjectURL(file);
            dpProofPreview.src = url;
            dpProofPreviewWrap.classList.remove('hidden');
            uploadDpProofBtn.disabled = false;
            dpProofStatus.textContent = '';
        });

        uploadDpProofBtn.addEventListener('click', async () => {
            const file = dpProofInput.files && dpProofInput.files[0] ? dpProofInput.files[0] : null;
            if (!file) return;

            uploadDpProofBtn.disabled = true;
            dpProofInput.disabled = true;
            dpProofStatus.textContent = 'Mengupload...';

            try {
                const form = new FormData();
                form.append('dp_proof', file);

                const res = await fetch(`/api/v1/booking/{{ $booking->id }}/upload-dp-proof`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: form
                });

                const data = await res.json();
                if (!res.ok) {
                    dpProofStatus.textContent = data.message || 'Gagal upload bukti pembayaran.';
                    uploadDpProofBtn.disabled = false;
                    dpProofInput.disabled = false;
                    return;
                }

                dpProofUploaded = true;
                confirmDpBtn.disabled = false;
                dpProofStatus.innerHTML = data.dp_proof_url ? `Upload berhasil. <a href="${data.dp_proof_url}" target="_blank" class="underline">Lihat bukti</a>` : 'Upload berhasil.';
                dpProofInput.value = '';
                uploadDpProofBtn.disabled = true;
                dpProofInput.disabled = false;
            } catch (e) {
                dpProofStatus.textContent = 'Terjadi kesalahan saat upload.';
                uploadDpProofBtn.disabled = false;
                dpProofInput.disabled = false;
            }
        });
    </script>
</body>
</html>
