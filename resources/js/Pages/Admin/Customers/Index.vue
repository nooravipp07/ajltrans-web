<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    customers: Array,
});

const searchQuery = ref('');

const filteredCustomers = computed(() => {
    if (!searchQuery.value) return props.customers;
    const q = searchQuery.value.toLowerCase();
    return props.customers.filter(c => 
        c.nama.toLowerCase().includes(q) || 
        c.nik.includes(q) || 
        c.no_wa.includes(q)
    );
});

const stats = computed(() => {
    return {
        total: props.customers.length,
        active: props.customers.filter(c => c.status === 'aktif').length,
        blacklist: props.customers.filter(c => c.status === 'blacklist').length,
    };
});
</script>

<template>
    <Head title="Database Pelanggan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-display font-extrabold text-slate-900">Database Pelanggan</h2>
                    <p class="text-slate-500 text-sm">Kelola informasi dan riwayat perjalanan client AJL Trans.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a :href="route('admin.customers.export')" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all gap-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Pelanggan</p>
                            <p class="text-2xl font-display font-extrabold text-slate-900">{{ stats.total }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Status Aktif</p>
                            <p class="text-2xl font-display font-extrabold text-slate-900">{{ stats.active }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Blacklist</p>
                            <p class="text-2xl font-display font-extrabold text-slate-900">{{ stats.blacklist }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="relative w-full md:w-96">
                            <input v-model="searchQuery" type="text" placeholder="Cari NIK, Nama, atau WhatsApp..." class="w-full pl-12 pr-4 py-3 rounded-2xl border-slate-100 focus:ring-primary focus:border-primary text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                    <th class="px-8 py-4">Informasi Pelanggan</th>
                                    <th class="px-8 py-4">Kontak & Alamat</th>
                                    <th class="px-8 py-4">Total Order</th>
                                    <th class="px-8 py-4 text-center">Status</th>
                                    <th class="px-8 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr v-for="customer in filteredCustomers" :key="customer.id" class="hover:bg-slate-50/80 transition-all group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold uppercase">
                                                {{ customer.nama.charAt(0) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 group-hover:text-primary transition-colors">{{ customer.nama }}</p>
                                                <p class="text-xs text-slate-400">NIK: {{ customer.nik }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <a :href="'https://wa.me/' + customer.no_wa" target="_blank" class="flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-green-600 transition-colors mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.652zm6.599-3.835c1.52.909 3.019 1.388 4.647 1.389 5.405 0 9.811-4.406 9.813-9.811.001-2.618-1.02-5.08-2.875-6.934-1.856-1.854-4.319-2.875-6.934-2.876-5.405 0-9.812 4.406-9.814 9.811-.001 1.703.443 3.363 1.284 4.811l-1.011 3.691 3.89-.98z"/></svg>
                                            {{ customer.no_wa }}
                                        </a>
                                        <p class="text-xs text-slate-400 truncate max-w-[200px]">{{ customer.alamat }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-display font-extrabold text-slate-900">{{ customer.bookings_count }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">Perjalanan</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider" :class="customer.status === 'aktif' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 animate-pulse" :class="customer.status === 'aktif' ? 'bg-green-500' : 'bg-red-500'"></span>
                                            {{ customer.status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <Link :href="route('admin.customers.show', customer.id)" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all gap-2">
                                            Detail
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="filteredCustomers.length === 0">
                                    <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium italic">
                                        Tidak ada data pelanggan ditemukan.
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
