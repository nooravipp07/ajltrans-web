<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    booking: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const statusForm = useForm({
    status: props.booking.status,
    catatan_admin: props.booking.catatan_admin || '',
});

const verifyForm = useForm({});

const baseUrl = computed(() => String(route('home')).replace(/\/$/, ''));
const trackUrl = computed(() => route('booking.track', props.booking.booking_code));
const dpProofUrl = computed(() => {
    const p = props.booking.dp_qris_image;
    if (!p) return null;
    const path = String(p).replace(/^\/+/, '');
    if (path.startsWith('storage/')) return `${baseUrl.value}/${path}`;
    return `${baseUrl.value}/storage/${path}`;
});

const updateStatus = () => {
    statusForm.put(route('admin.bookings.update', props.booking.id), {
        preserveScroll: true,
        onSuccess: () => {
            const waUrl = page.props.flash.wa_notification_url;
            if (waUrl) {
                window.open(waUrl, '_blank');
            }
            alert('Status berhasil diperbarui');
        },
    });
};

const verifyBooking = () => {
    if (confirm('Verifikasi booking ini? Status akan berubah menjadi Dikonfirmasi.')) {
        verifyForm.post(route('admin.bookings.verify', props.booking.id), {
            preserveScroll: true,
            onSuccess: () => {
                const waUrl = page.props.flash.wa_notification_url;
                if (waUrl) {
                    window.open(waUrl, '_blank');
                }
                alert('Booking berhasil diverifikasi');
            },
        });
    }
};

const getStatusBadge = (status) => {
    const badges = {
        'menunggu_konfirmasi': 'bg-amber-50 text-amber-600 border-amber-100',
        'dikonfirmasi': 'bg-blue-50 text-blue-600 border-blue-100',
        'sedang_berjalan': 'bg-primary/5 text-primary border-primary/10',
        'selesai': 'bg-green-50 text-green-600 border-green-100',
        'dibatalkan': 'bg-red-50 text-red-600 border-red-100',
    };
    return badges[status] || 'bg-slate-50 text-slate-600 border-slate-100';
};
</script>

<template>
    <Head title="Detail Pemesanan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <Link :href="route('admin.bookings.index')" class="text-slate-400 hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        </Link>
                        <h2 class="text-2xl font-display font-extrabold text-slate-900">Detail #{{ booking.booking_code }}</h2>
                        <a :href="trackUrl" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-all">
                            Buka Tracking
                        </a>
                    </div>
                    <p class="text-slate-500 text-sm">Informasi lengkap pemesanan dan dokumen pelanggan.</p>
                </div>
                
                <div v-if="user.role === 'superadmin'" class="flex items-center gap-3">
                    <button @click="verifyBooking" :disabled="verifyForm.processing || booking.verified_at" class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-green-200 hover:bg-green-600 transition-all gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ booking.verified_at ? 'Sudah Terverifikasi' : 'Verifikasi Booking' }}
                    </button>
                </div>
                <div v-else-if="booking.verified_at" class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-600 rounded-xl border border-green-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-xs font-bold uppercase tracking-wider">Terverifikasi</span>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Info -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Customer Card -->
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                            <h3 class="text-lg font-display font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Informasi Pelanggan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Nama Lengkap</label>
                                    <p class="font-bold text-slate-900">{{ booking.customer.nama }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">NIK</label>
                                    <p class="font-bold text-slate-900">{{ booking.customer.nik }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Nomor WA</label>
                                    <p class="font-bold text-slate-900">{{ booking.customer.no_wa }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Alamat</label>
                                    <p class="font-bold text-slate-900">{{ booking.customer.alamat }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                            <h3 class="text-lg font-display font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Dokumen & Media
                            </h3>
                            <div class="space-y-6">
                                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bukti Pembayaran DP</p>
                                            <p class="text-sm font-bold text-slate-900 mt-1" v-if="dpProofUrl">Sudah diupload</p>
                                            <p class="text-sm font-bold text-slate-500 mt-1" v-else>Belum diupload</p>
                                        </div>
                                        <a v-if="dpProofUrl" :href="dpProofUrl" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-all">
                                            Buka Bukti
                                        </a>
                                    </div>
                                    <div v-if="dpProofUrl" class="mt-4">
                                        <img :src="dpProofUrl" class="w-full max-w-xl rounded-2xl border border-slate-200 object-contain">
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">Foto Identitas (KTP/SIM)</label>
                                    <div class="w-full max-w-md aspect-video rounded-3xl overflow-hidden border border-slate-100 bg-slate-50">
                                        <img v-if="booking.customer.foto_identitas" :src="'/storage/' + booking.customer.foto_identitas" class="w-full h-full object-cover">
                                        <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                                            <span>Tidak ada foto identitas</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="booking.media_docs.length > 0">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-4">Media Dokumentasi</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <div v-for="media in booking.media_docs" :key="media.id" class="aspect-square rounded-2xl overflow-hidden border border-slate-100">
                                            <img v-if="media.tipe === 'foto'" :src="'/storage/' + media.url" class="w-full h-full object-cover">
                                            <video v-else :src="'/storage/' + media.url" class="w-full h-full object-cover" controls></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="space-y-8">
                        <!-- Status Card -->
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                            <h3 class="text-lg font-display font-bold text-slate-900 mb-6">Status & Catatan</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Status Saat Ini</label>
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider border" :class="getStatusBadge(booking.status)">
                                        {{ booking.status.replace('_', ' ') }}
                                    </span>
                                </div>
                                
                                <form @submit.prevent="updateStatus" class="space-y-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block ml-1">Update Status</label>
                                        <select v-model="statusForm.status" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:ring-primary focus:border-primary font-bold text-sm">
                                            <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                                            <option value="dikonfirmasi">Dikonfirmasi</option>
                                            <option value="sedang_berjalan">Sedang Berjalan</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="dibatalkan">Dibatalkan</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block ml-1">Catatan Admin</label>
                                        <textarea v-model="statusForm.catatan_admin" rows="4" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:ring-primary focus:border-primary text-sm font-medium" placeholder="Contoh: Nama sopir, plat nomor, dll..."></textarea>
                                    </div>
                                    <button type="submit" :disabled="statusForm.processing" class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-sm hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Booking Detail Card -->
                        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white">
                            <h3 class="text-lg font-display font-bold mb-6">Detail Layanan</h3>
                            <div class="space-y-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Armada</p>
                                        <p class="font-bold">{{ booking.vehicle.nama }}</p>
                                    </div>
                                    <span class="px-2 py-1 bg-white/10 rounded text-[10px] font-bold uppercase">{{ booking.kota }}</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Layanan</p>
                                    <p class="font-bold capitalize">{{ booking.service_type.replace('_', ' ') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Waktu</p>
                                    <p class="font-bold">{{ new Date(booking.tanggal_mulai).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) }}</p>
                                    <p class="text-xs text-slate-400">{{ booking.kategori === 'sewa_mobil' ? booking.durasi + ' Jam' : booking.durasi_hari + ' Hari' }}</p>
                                </div>
                                <div v-if="booking.alamat_jemput">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jemput</p>
                                    <p class="text-sm font-medium text-slate-300">{{ booking.alamat_jemput }}</p>
                                </div>
                                <div class="pt-6 border-t border-white/10">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pembayaran</p>
                                    <p class="text-2xl font-display font-extrabold text-primary">Rp {{ new Intl.NumberFormat('id-ID').format(booking.total_harga) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
