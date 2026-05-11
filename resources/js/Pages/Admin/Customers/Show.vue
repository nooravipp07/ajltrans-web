<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    customer: Object,
});

const form = useForm({
    reason: '',
});

const toggleBlacklist = () => {
    if (props.customer.status === 'aktif') {
        const reason = prompt('Alasan blacklist:');
        if (reason) {
            form.reason = reason;
            form.post(route('admin.customers.blacklist', props.customer.nik));
        }
    } else {
        if (confirm('Cabut status blacklist?')) {
            form.post(route('admin.customers.unblacklist', props.customer.nik));
        }
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
    <Head :title="'Detail Pelanggan - ' + customer.nama" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.customers.index')" class="p-3 bg-white rounded-2xl border border-slate-100 text-slate-400 hover:text-primary transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </Link>
                    <div>
                        <h2 class="text-2xl font-display font-extrabold text-slate-900 leading-none">Profil Pelanggan</h2>
                        <p class="text-slate-500 text-sm mt-2 font-medium">Informasi lengkap dan riwayat perjalanan {{ customer.nama }}.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="toggleBlacklist" 
                            :class="customer.status === 'aktif' ? 'bg-red-50 text-red-600 hover:bg-red-600 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white'"
                            class="px-6 py-3 rounded-2xl text-sm font-bold shadow-sm transition-all flex items-center gap-2 border border-transparent">
                        <svg v-if="customer.status === 'aktif'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ customer.status === 'aktif' ? 'Blacklist Client' : 'Aktifkan Kembali' }}
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Identity Card -->
                    <div class="lg:col-span-1 space-y-8">
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 text-center relative overflow-hidden">
                            <div v-if="customer.status === 'blacklist'" class="absolute top-4 right-4 rotate-12 opacity-20 pointer-events-none">
                                <div class="border-4 border-red-600 text-red-600 px-4 py-2 font-display font-black text-2xl uppercase">Blacklisted</div>
                            </div>

                            <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center text-3xl font-display font-black text-slate-300 mx-auto mb-6 border-4 border-white shadow-lg">
                                {{ customer.nama.charAt(0) }}
                            </div>
                            <h3 class="text-xl font-display font-extrabold text-slate-900">{{ customer.nama }}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">NIK: {{ customer.nik }}</p>
                            
                            <div class="mt-8 flex flex-col gap-3">
                                <a :href="'https://wa.me/' + customer.no_wa" target="_blank" class="w-full py-4 bg-green-50 text-green-600 rounded-2xl text-sm font-bold hover:bg-green-600 hover:text-white transition-all flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.652zm6.599-3.835c1.52.909 3.019 1.388 4.647 1.389 5.405 0 9.811-4.406 9.813-9.811.001-2.618-1.02-5.08-2.875-6.934-1.856-1.854-4.319-2.875-6.934-2.876-5.405 0-9.812 4.406-9.814 9.811-.001 1.703.443 3.363 1.284 4.811l-1.011 3.691 3.89-.98z"/></svg>
                                    WhatsApp Client
                                </a>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-6">Informasi Kontak</h4>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Alamat Domisili</p>
                                    <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ customer.alamat }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Status Keanggotaan</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider" :class="customer.status === 'aktif' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'">
                                        {{ customer.status }}
                                    </span>
                                </div>
                                <div v-if="customer.blacklist_reason">
                                    <p class="text-[10px] font-bold text-red-400 uppercase mb-1">Alasan Blacklist</p>
                                    <p class="text-sm font-bold text-red-700 bg-red-50 p-3 rounded-xl">{{ customer.blacklist_reason }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking History -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                            <div class="p-8 border-b border-slate-50">
                                <h3 class="text-xl font-display font-extrabold text-slate-900">Riwayat Perjalanan</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Seluruh transaksi yang dilakukan</p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                            <th class="px-8 py-4">Booking</th>
                                            <th class="px-8 py-4">Kendaraan</th>
                                            <th class="px-8 py-4">Total Biaya</th>
                                            <th class="px-8 py-4 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <tr v-for="booking in customer.bookings" :key="booking.id" class="hover:bg-slate-50/80 transition-all">
                                            <td class="px-8 py-6">
                                                <p class="font-display font-extrabold text-primary">{{ booking.booking_code }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase">{{ new Date(booking.created_at).toLocaleDateString('id-ID') }}</p>
                                            </td>
                                            <td class="px-8 py-6">
                                                <p class="font-bold text-slate-700">{{ booking.vehicle.nama }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ booking.kota }}</p>
                                            </td>
                                            <td class="px-8 py-6">
                                                <p class="text-sm font-display font-extrabold text-slate-900">Rp {{ new Intl.NumberFormat('id-ID').format(booking.total_harga) }}</p>
                                            </td>
                                            <td class="px-8 py-6 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider border" :class="getStatusBadge(booking.status)">
                                                    {{ booking.status.replace('_', ' ') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="customer.bookings.length === 0">
                                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">
                                                Belum ada riwayat perjalanan.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Syne', sans-serif; }
</style>
