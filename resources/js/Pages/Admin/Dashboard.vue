<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recent_bookings: Array
});
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-display font-extrabold text-slate-900 leading-none">Admin Control Center</h2>
                    <p class="text-slate-500 text-sm mt-2 font-medium">Selamat datang kembali! Berikut ringkasan performa hari ini.</p>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <!-- Top Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Bookings</span>
                        </div>
                        <p class="text-3xl font-display font-extrabold text-slate-900">{{ stats.total_bookings }}</p>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Total reservasi masuk</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Pending</span>
                        </div>
                        <p class="text-3xl font-display font-extrabold text-slate-900">{{ stats.pending_bookings }}</p>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Menunggu konfirmasi</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-green-400 uppercase tracking-widest">Clients</span>
                        </div>
                        <p class="text-3xl font-display font-extrabold text-slate-900">{{ stats.total_customers }}</p>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Database pelanggan aktif</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Fleet</span>
                        </div>
                        <p class="text-3xl font-display font-extrabold text-slate-900">{{ stats.total_vehicles }}</p>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Kendaraan terdaftar</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Quick Actions -->
                    <div class="lg:col-span-1 space-y-6">
                        <h3 class="text-lg font-display font-extrabold text-slate-900 px-4 uppercase tracking-widest">Navigasi Cepat</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <Link :href="route('admin.analytics')" class="p-6 bg-white rounded-3xl border border-slate-100 hover:border-primary hover:shadow-xl transition-all group flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary/5 text-primary rounded-2xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Analitik</h4>
                                    <p class="text-xs text-slate-400 font-medium">Buka dashboard grafik</p>
                                </div>
                            </Link>

                            <Link :href="route('admin.vehicles.index')" class="p-6 bg-white rounded-3xl border border-slate-100 hover:border-primary hover:shadow-xl transition-all group flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary/5 text-primary rounded-2xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Manajemen Armada</h4>
                                    <p class="text-xs text-slate-400 font-medium">Update data kendaraan</p>
                                </div>
                            </Link>

                            <Link :href="route('admin.content')" class="p-6 bg-white rounded-3xl border border-slate-100 hover:border-primary hover:shadow-xl transition-all group flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary/5 text-primary rounded-2xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">CMS Konten</h4>
                                    <p class="text-xs text-slate-400 font-medium">Edit landing page</p>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Recent Bookings Table -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex justify-between items-center px-4">
                            <h3 class="text-lg font-display font-extrabold text-slate-900 uppercase tracking-widest">Pemesanan Terbaru</h3>
                            <Link :href="route('admin.bookings.index')" class="text-xs font-bold text-primary hover:underline">Lihat Semua</Link>
                        </div>
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                            <th class="px-8 py-4">Customer</th>
                                            <th class="px-8 py-4">Armada</th>
                                            <th class="px-8 py-4 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <tr v-for="booking in recent_bookings" :key="booking.id" class="hover:bg-slate-50/80 transition-all">
                                            <td class="px-8 py-6">
                                                <p class="font-bold text-slate-900">{{ booking.customer.nama }}</p>
                                                <p class="text-[10px] font-bold text-primary uppercase tracking-tighter">{{ booking.booking_code }}</p>
                                            </td>
                                            <td class="px-8 py-6">
                                                <p class="font-bold text-slate-600">{{ booking.vehicle.nama }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ booking.kota }}</p>
                                            </td>
                                            <td class="px-8 py-6 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider" 
                                                      :class="booking.status === 'selesai' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600'">
                                                    {{ booking.status.replace('_', ' ') }}
                                                </span>
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
