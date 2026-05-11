<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking City Tour - AJL Trans</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-background antialiased font-sans" x-data="bookingForm('city_tour')" @vehicle-selected.window="handleVehicleSelected($event.detail)">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form Column -->
                <div class="lg:col-span-2">
                    <div class="mb-10 flex items-center justify-between">
                        <a href="/" class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Kembali</span>
                        </a>
                        <div class="h-10">
                            <img src="{{ $cms['branding']['logo_dark']->value_id ?? '/images/logo-dark.png' }}" class="h-full w-auto" alt="Logo">
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden">
                        <div class="bg-primary p-8 text-white">
                            <h1 class="text-3xl font-display font-bold mb-2">Form City Tour</h1>
                            <p class="text-white/70">Silakan lengkapi data di bawah ini untuk paket wisata kota.</p>
                        </div>

                        <form @submit.prevent="submitForm" class="p-8 space-y-8">
                            @csrf
                            <!-- NIK Check Section -->
                            <div class="space-y-4">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">NIK Pelanggan (16 Digit)</label>
                                <div class="flex gap-3">
                                    <input type="text" x-model="formData.nik" @blur="checkNik" maxlength="16" class="flex-1 rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="Masukkan 16 digit NIK..." required>
                                    <div x-show="checkingNik" class="flex items-center px-4">
                                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-primary border-t-transparent"></div>
                                    </div>
                                </div>
                                <template x-if="customerStatus === 'blacklisted'">
                                    <p class="text-red-500 text-sm font-medium" x-text="errorMessage"></p>
                                </template>
                            </div>

                            <!-- Personal Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="customerStatus !== 'blacklisted'">
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Nama Lengkap</label>
                                    <input type="text" x-model="formData.nama" :readonly="customerStatus === 'exists'" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="Nama sesuai KTP..." required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Nomor WhatsApp</label>
                                    <input type="text" x-model="formData.no_wa" :readonly="customerStatus === 'exists'" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" placeholder="Contoh: 0812..." required>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Alamat Lengkap</label>
                                    <textarea x-model="formData.alamat" :readonly="customerStatus === 'exists'" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" rows="3" placeholder="Alamat sesuai KTP..." required></textarea>
                                </div>
                            </div>

                            <!-- Identity Upload -->
                            <div class="space-y-4" x-show="customerStatus === 'new'">
                                <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Foto Identitas (KTP/SIM)</label>
                                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-primary transition-colors cursor-pointer relative">
                                    <input type="file" @change="handleFileChange($event, 'foto_identitas')" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                                    <div class="text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                        </svg>
                                        <p x-text="files.foto_identitas ? files.foto_identitas.name : 'Klik atau drag file untuk upload KTP'"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-8" x-show="customerStatus !== 'blacklisted'">
                                <!-- Tipe Paket -->
                                <div class="space-y-4 md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Tipe Paket</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach(($serviceTypes ?? []) as $t)
                                            <label class="relative flex flex-col p-4 bg-white border-2 rounded-2xl cursor-pointer transition-all hover:bg-slate-50"
                                                :class="formData.service_type === '{{ $t['slug'] }}' ? 'border-primary bg-primary/5' : 'border-slate-100'">
                                                <input type="radio" x-model="formData.service_type" value="{{ $t['slug'] }}" class="absolute top-4 right-4 text-primary focus:ring-primary">
                                                <span class="font-bold text-slate-900">{{ $t['label'] }}</span>
                                                <span class="text-xs text-slate-500 mt-1">{{ str_replace('_', ' ', $t['slug']) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="md:col-span-2 rounded-2xl border border-slate-100 bg-slate-50 p-6 space-y-3" x-show="formData.service_type">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Catatan Layanan</p>
                                            <p class="text-base font-extrabold text-slate-900 mt-1" x-text="getServiceTypeLabel()"></p>
                                        </div>
                                        <div class="text-right" x-show="selectedVehicleData">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Overtime</p>
                                            <p class="text-sm font-extrabold text-slate-900 mt-1" x-text="getOvertimeText()"></p>
                                        </div>
                                    </div>
                                    <ul class="text-sm text-slate-700 space-y-1">
                                        <template x-for="(line, idx) in getServiceTypeNotes()" :key="idx">
                                            <li class="flex gap-2">
                                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-primary"></span>
                                                <span x-text="line"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                <div class="space-y-2 md:col-span-2" x-data="{ 
                                    search: '', 
                                    open: false,
                                    vehicles: @js($vehicles),
                                    selectedVehicle: @js($selectedVehicle),
                                    get filteredVehicles() {
                                        return this.vehicles.filter(v => v.nama.toLowerCase().includes(this.search.toLowerCase()))
                                    },
                                    init() {
                                        if (this.selectedVehicle) {
                                            $dispatch('vehicle-selected', this.selectedVehicle);
                                        }
                                    }
                                }">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Pilih Kendaraan</label>
                                    <div class="relative">
                                        <div @click="open = !open" class="w-full rounded-xl border border-slate-200 py-3 px-4 flex items-center justify-between cursor-pointer bg-white">
                                            <div class="flex items-center gap-3">
                                                <template x-if="selectedVehicle">
                                                    <div class="flex items-center gap-3">
                                                        <img :src="selectedVehicle.foto_urls ? selectedVehicle.foto_urls[0] : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=100'" class="w-10 h-6 object-cover rounded">
                                                        <span x-text="selectedVehicle.nama" class="font-bold"></span>
                                                    </div>
                                                </template>
                                                <template x-if="!selectedVehicle">
                                                    <span class="text-slate-400">Cari atau Pilih Armada...</span>
                                                </template>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>

                                        <div x-show="open" @click.away="open = false" class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl max-h-80 overflow-y-auto">
                                            <div class="p-3 border-b border-slate-50 sticky top-0 bg-white">
                                                <input type="text" x-model="search" class="w-full rounded-lg border-slate-100 focus:border-primary focus:ring-primary text-sm" placeholder="Ketik nama mobil...">
                                            </div>
                                            <div class="p-2">
                                                <template x-for="v in filteredVehicles" :key="v.id">
                                                    <div @click="formData.vehicle_id = v.id; selectedVehicle = v; open = false; search = ''; $dispatch('vehicle-selected', v)" class="flex items-center gap-4 p-3 hover:bg-primary/5 rounded-xl cursor-pointer transition-colors group">
                                                        <img :src="v.foto_urls ? v.foto_urls[0] : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=200'" class="w-20 h-12 object-cover rounded-lg group-hover:scale-105 transition-transform">
                                                        <div>
                                                            <p class="font-bold text-slate-900" x-text="v.nama"></p>
                                                            <p class="text-xs text-slate-400 uppercase font-bold tracking-tighter" x-text="v.tipe"></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" x-model="formData.vehicle_id" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Kota</label>
                                    <select x-model="formData.kota" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" required>
                                        <option value="bandung">Bandung</option>
                                        <option value="jakarta">Jakarta</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Tanggal Mulai</label>
                                    <input type="date" x-model="formData.tanggal_mulai" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Durasi (Hari)</label>
                                    <input type="number" x-model="formData.durasi_hari" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary py-3" min="1" required>
                                </div>

                                <!-- Price Summary -->
                                <div class="md:col-span-2 p-6 bg-primary/5 rounded-2xl border border-primary/10" x-show="selectedVehicleData">
                                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700" x-show="!isSelectedServiceAvailable()">
                                        Keterangan layanan pilihan yang anda pilih tidak tersedia.
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Estimasi Total Wisata</p>
                                            <p class="text-xs text-slate-400" x-text="'Harga All-In: Rp ' + formatNumber(getCurrentPrice())"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-3xl font-display font-extrabold text-primary" x-text="'Rp ' + formatNumber(calculateTotal())"></p>
                                            <p class="text-[10px] text-slate-400 italic">Sudah termasuk Mobil, Sopir & BBM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8">
                                <button type="submit" :disabled="customerStatus === 'blacklisted' || submitting || !isSelectedServiceAvailable()" class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-dark transition-all shadow-lg shadow-primary/25 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!submitting">Pesan Paket Wisata</span>
                                    <span x-show="submitting" class="flex items-center justify-center gap-2">
                                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
                                        <span>Memproses...</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Terms Sidebar Column -->
                <div class="lg:col-span-1">
                    @include('components.booking-terms')
                </div>
            </div>
        </div>
    </div>

    <script>
        const JAKARTA_MARKUP = 200000;
        const OT_RATES = {
            small: 100000,
            hiace_elf: 150000,
            medium_bus: 250000,
        };

        function matchPrice(vehicle, type, city) {
            if (!vehicle.pricings) return 0;
            const target = (city === 'bdg' || city === 'bandung') ? 'bandung' : 'jakarta';

            const bdg = vehicle.pricings.find(p => p.paket_tipe === type && p.kota === 'bandung' && (p.unit || 'per_hari') === 'per_hari');
            const jkt = vehicle.pricings.find(p => p.paket_tipe === type && p.kota === 'jakarta' && (p.unit || 'per_hari') === 'per_hari');

            let base = 0;
            if (bdg && bdg.harga_dasar) {
                base = parseInt(bdg.harga_dasar, 10) || 0;
            } else if (jkt && jkt.harga_dasar) {
                base = Math.max((parseInt(jkt.harga_dasar, 10) || 0) - JAKARTA_MARKUP, 0);
            }

            return target === 'jakarta' ? base + JAKARTA_MARKUP : base;
        }

        function bookingForm(category) {
            return {
                formData: {
                    nik: '',
                    nama: '',
                    alamat: '',
                    no_wa: '',
                    vehicle_id: '{{ $selectedVehicleId ?? "" }}',
                    kota: '{{ request()->get("city", "bandung") }}',
                    kategori: 'city_tour',
                    service_type: '{{ $defaultServiceType ?? 'city_tour_allin' }}',
                    tanggal_mulai: '',
                    jam_mulai: '',
                    durasi: 12,
                    durasi_hari: 1,
                    dengan_supir: false,
                    alamat_jemput: '',
                    alamat_tujuan: '',
                },
                selectedVehicleData: null,
                getServiceTypeLabel() {
                    const types = @js($serviceTypes ?? []);
                    const found = types.find(t => t.slug === this.formData.service_type);
                    return found ? found.label : this.formData.service_type.replaceAll('_', ' ');
                },
                getServiceTypeNotes() {
                    const slug = (this.formData.service_type || '').toLowerCase();
                    const notes = [];

                    if (slug.includes('allin') && (slug.includes('dalam_kota') || slug.includes('city_tour'))) {
                        notes.push('All-in (Mobil, Driver, BBM).');
                        notes.push('Harga belum termasuk: Makan Driver, Tips Driver, Tiket Wisata & Parkir.');
                        notes.push('Berlaku jemputan area Bandung.');
                    } else if (slug.includes('allin') && slug.includes('luar_kota')) {
                        notes.push('All-in (Mobil, Driver, BBM).');
                        notes.push('Harga belum termasuk: Makan Driver, Tips Driver, Tiket Wisata & Parkir, E-Toll.');
                        notes.push('Berlaku jemputan area Bandung.');
                    } else {
                        notes.push('Detail layanan mengikuti paket yang dipilih.');
                    }

                    return notes;
                },
                getVehicleClass() {
                    const name = (this.selectedVehicleData?.nama || '').toLowerCase();
                    const tipe = (this.selectedVehicleData?.tipe || '').toLowerCase();

                    if (name.includes('big bus') || name.includes('hdd') || name.includes('60 seat') || name.includes('50 seat')) {
                        return 'big_bus';
                    }
                    if (name.includes('bus')) {
                        return 'medium_bus';
                    }
                    if (name.includes('hiace') || name.includes('haice') || name.includes('elf') || name.includes('coaster') || tipe.includes('hiace') || tipe.includes('elf') || tipe.includes('coaster')) {
                        return 'hiace_elf';
                    }
                    return 'small';
                },
                getOvertimeText() {
                    if (!this.selectedVehicleData) return '-';
                    const cls = this.getVehicleClass();
                    if (cls === 'big_bus') {
                        return '10% dari harga sewa';
                    }
                    const rate = cls === 'medium_bus' ? OT_RATES.medium_bus : (cls === 'hiace_elf' ? OT_RATES.hiace_elf : OT_RATES.small);
                    return 'Rp ' + this.formatNumber(rate) + ' / jam';
                },
                calculateTotal() {
                    if (!this.selectedVehicleData) return 0;
                    
                    let price = 0;
                    const city = this.formData.kota;
                    const type = this.formData.service_type;

                    if (city === 'bandung') {
                        price = matchPrice(this.selectedVehicleData, type, 'bdg');
                    } else {
                        price = matchPrice(this.selectedVehicleData, type, 'jkt');
                    }
                    if (!price || price <= 0) return 0;

                    return price * this.formData.durasi_hari;
                },
                getCurrentPrice() {
                    if (!this.selectedVehicleData) return 0;
                    const city = this.formData.kota;
                    const type = this.formData.service_type;
                    return (city === 'bandung') ? matchPrice(this.selectedVehicleData, type, 'bdg') : matchPrice(this.selectedVehicleData, type, 'jkt');
                },
                isSelectedServiceAvailable() {
                    if (!this.selectedVehicleData || !this.formData.service_type) return true;
                    return this.getCurrentPrice() > 0;
                },
                formatNumber(val) {
                    return new Intl.NumberFormat('id-ID').format(val);
                },
                handleVehicleSelected(vehicle) {
                    this.selectedVehicleData = JSON.parse(JSON.stringify(vehicle));
                    this.formData.vehicle_id = vehicle.id;
                },
                files: {
                    foto_identitas: null,
                    media_docs: []
                },
                customerStatus: 'new',
                checkingNik: false,
                submitting: false,
                errorMessage: '',

                async checkNik() {
                    if (this.formData.nik.length !== 16) return;
                    
                    this.checkingNik = true;
                    try {
                        const response = await fetch('/api/v1/customer/check-nik', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ nik: this.formData.nik })
                        });
                        const res = await response.json();
                        
                        this.customerStatus = res.status;
                        if (res.status === 'exists') {
                            this.formData.nama = res.data.nama;
                            this.formData.alamat = res.data.alamat;
                            this.formData.no_wa = res.data.no_wa;
                        } else if (res.status === 'blacklisted') {
                            this.errorMessage = res.message;
                        }
                    } catch (error) {
                        console.error('Error checking NIK:', error);
                    } finally {
                        this.checkingNik = false;
                    }
                },

                handleFileChange(event, field) {
                    this.files[field] = event.target.files[0];
                },

                handleMultipleFilesChange(event) {
                    const selectedFiles = Array.from(event.target.files);
                    if (selectedFiles.length > 5) {
                        alert('Maksimal 5 file dokumentasi.');
                        event.target.value = '';
                        this.files.media_docs = [];
                        return;
                    }
                    this.files.media_docs = selectedFiles;
                },

                async submitForm() {
                    if (!this.isSelectedServiceAvailable()) {
                        alert('Keterangan layanan pilihan yang anda pilih tidak tersedia.');
                        return;
                    }
                    this.submitting = true;
                    const data = new FormData();
                    
                    // Main Form Data
                    Object.keys(this.formData).forEach(key => {
                        if (this.formData[key] !== null) {
                            data.append(key, this.formData[key]);
                        }
                    });

                    // Single Files
                    if (this.files.foto_identitas) {
                        data.append('foto_identitas', this.files.foto_identitas);
                    }

                    // Multiple Files
                    this.files.media_docs.forEach((file, index) => {
                        data.append(`media_docs[${index}]`, file);
                    });

                    try {
                        const response = await fetch('/api/v1/booking', {
                            method: 'POST',
                            body: data
                        });
                        const res = await response.json();
                        if (response.ok) {
                            // Redirect ke halaman QRIS payment
                            window.location.href = res.redirect_url || `/booking/qris/${res.booking_code}`;
                        } else {
                            const msg = (res && res.message) ? String(res.message) : '';
                            if (msg.toLowerCase().includes('harga') && msg.toLowerCase().includes('belum tersedia')) {
                                alert('Keterangan layanan pilihan yang anda pilih tidak tersedia.');
                            } else {
                                alert(msg || 'Terjadi kesalahan saat menyimpan data.');
                            }
                        }
                    } catch (error) {
                        console.error('Error submitting form:', error);
                    } finally {
                        this.submitting = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
