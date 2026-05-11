<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    vehicles: Array,
});

const toggleStatus = (id) => {
    router.post(route('admin.vehicles.toggle-status', id));
};

const deleteVehicle = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus armada ini?')) {
        router.delete(route('admin.vehicles.destroy', id));
    }
};

const formatPrice = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};

const getMinPrice = (vehicle, city) => {
    const JAKARTA_MARKUP = 200000;

    const bdgPrices = vehicle.pricings.filter(p => p.kota === 'bandung' && p.harga_dasar > 0);
    const jktPrices = vehicle.pricings.filter(p => p.kota === 'jakarta' && p.harga_dasar > 0);

    let minBase = 0;
    if (bdgPrices.length > 0) {
        minBase = Math.min(...bdgPrices.map(p => p.harga_dasar));
    } else if (jktPrices.length > 0) {
        minBase = Math.max(Math.min(...jktPrices.map(p => p.harga_dasar)) - JAKARTA_MARKUP, 0);
    }

    if (!minBase) return 0;
    return city === 'jakarta' ? minBase + JAKARTA_MARKUP : minBase;
};
</script>

<template>
    <Head title="Manajemen Armada" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    Manajemen Armada
                </h2>
                <Link
                    :href="route('admin.vehicles.create')"
                    class="px-4 py-2 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary-dark transition-all"
                >
                    + Tambah Armada
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-slate-900">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4">Kendaraan</th>
                                        <th class="px-6 py-4">Tipe / Kategori</th>
                                        <th class="px-6 py-4">Harga (BDG/JKT)</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="vehicle in vehicles" :key="vehicle.id" class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img :src="vehicle.foto_urls ? vehicle.foto_urls[0] : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=100'" class="w-12 h-8 object-cover rounded shadow-sm">
                                                <span class="font-bold">{{ vehicle.nama }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium">{{ vehicle.tipe }}</p>
                                            <div class="mt-1">
                                                <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full" :class="vehicle.vehicle_size === 'besar' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700'">
                                                    {{ vehicle.vehicle_size === 'besar' ? 'Kendaraan Besar' : 'Kendaraan Kecil' }}
                                                </span>
                                            </div>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <span v-for="cat in vehicle.kategori" :key="cat" class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                                    {{ cat.replace('_', ' ') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div v-if="vehicle.pricings && vehicle.pricings.length > 0" class="space-y-1">
                                                <div class="flex justify-between gap-4 text-[10px]">
                                                    <span class="text-blue-500 font-bold uppercase">BDG:</span>
                                                    <span class="font-mono">{{ formatPrice(getMinPrice(vehicle, 'bandung')) }}</span>
                                                </div>
                                                <div class="flex justify-between gap-4 text-[10px]">
                                                    <span class="text-orange-500 font-bold uppercase">JKT:</span>
                                                    <span class="font-mono">{{ formatPrice(getMinPrice(vehicle, 'jakarta')) }}</span>
                                                </div>
                                            </div>
                                            <div v-else class="text-slate-400 text-xs italic">Harga belum diatur</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button @click="toggleStatus(vehicle.id)" class="px-3 py-1 rounded-full text-xs font-bold transition-all" :class="vehicle.is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200'">
                                                {{ vehicle.is_active ? 'Aktif' : 'Non-aktif' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <Link :href="route('admin.vehicles.edit', vehicle.id)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </Link>
                                                <button @click="deleteVehicle(vehicle.id)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
