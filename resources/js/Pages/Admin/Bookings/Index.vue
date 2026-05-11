<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    bookings: Array,
});

const searchQuery = ref('');

const filteredBookings = computed(() => {
    if (!searchQuery.value) return props.bookings;
    const q = searchQuery.value.toLowerCase();
    return props.bookings.filter(b => 
        b.booking_code.toLowerCase().includes(q) || 
        b.customer.nama.toLowerCase().includes(q) || 
        b.vehicle.nama.toLowerCase().includes(q)
    );
});

const stats = computed(() => {
    return {
        total: props.bookings.length,
        pending: props.bookings.filter(b => b.status === 'menunggu_konfirmasi').length,
        active: props.bookings.filter(b => b.status === 'sedang_berjalan').length,
        completed: props.bookings.filter(b => b.status === 'selesai').length,
    };
});

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
    <Head title="Manajemen Pemesanan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-display font-extrabold text-slate-900">Manajemen Pemesanan</h2>
                    <p class="text-slate-500 text-sm">Pantau dan kelola seluruh reservasi kendaraan AJL Trans.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a :href="route('admin.bookings.export')" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all gap-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Laporan Booking
                    </a>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pesanan</p>
                        <p class="text-2xl font-display font-extrabold text-slate-900">{{ stats.total }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest mb-1">Menunggu</p>
                        <p class="text-2xl font-display font-extrabold text-amber-500">{{ stats.pending }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                        <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest mb-1">Berjalan</p>
                        <p class="text-2xl font-display font-extrabold text-primary">{{ stats.active }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                        <p class="text-[10px] font-bold text-green-400 uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-2xl font-display font-extrabold text-green-500">{{ stats.completed }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="relative w-full md:w-96">
                            <input v-model="searchQuery" type="text" placeholder="Cari Kode, Nama Customer, atau Mobil..." class="w-full pl-12 pr-4 py-3 rounded-2xl border-slate-100 focus:ring-primary focus:border-primary text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <th class="px-8 py-4">Pemesanan</th>
                                    <th class="px-8 py-4">Pelanggan</th>
                                    <th class="px-8 py-4">Layanan & Armada</th>
                                    <th class="px-8 py-4">Biaya</th>
                                    <th class="px-8 py-4 text-center">Status</th>
                                    <th class="px-8 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="booking in filteredBookings" :key="booking.id" class="hover:bg-slate-50/80 transition-all group">
                                    <td class="px-8 py-6">
                                        <a :href="route('booking.track', booking.booking_code)" target="_blank" class="font-display font-extrabold text-primary hover:underline">
                                            {{ booking.booking_code }}
                                        </a>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ new Date(booking.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-slate-900">{{ booking.customer.nama }}</p>
                                        <p class="text-xs text-slate-400">NIK: {{ booking.customer_nik }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-[9px] font-bold text-slate-500 uppercase">{{ booking.service_type.replace('_', ' ') }}</span>
                                            <span class="px-2 py-0.5 rounded bg-primary/5 text-[9px] font-bold text-primary uppercase">{{ booking.kota }}</span>
                                        </div>
                                        <p class="font-bold text-slate-700">{{ booking.vehicle.nama }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-display font-extrabold text-slate-900">Rp {{ new Intl.NumberFormat('id-ID').format(booking.total_harga) }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider border" :class="getStatusBadge(booking.status)">
                                            {{ booking.status.replace('_', ' ') }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <Link :href="route('admin.bookings.show', booking.id)" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all gap-2">
                                            Detail
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="filteredBookings.length === 0">
                                    <td colspan="6" class="px-8 py-12 text-center text-slate-400 font-medium italic">
                                        Tidak ada data pemesanan ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
