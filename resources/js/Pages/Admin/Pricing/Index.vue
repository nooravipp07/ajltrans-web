<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    vehicles: Array,
    service_types: Array,
});

const showModal = ref(false);
const editingVehicle = ref(null);

const JAKARTA_MARKUP = 200000;
const UNITS = [
    { value: 'per_12_jam', label: '12 Jam' },
    { value: 'per_18_jam', label: '18 Jam' },
    { value: 'per_hari', label: 'Per Hari' },
];

const categories = computed(() => {
    return (props.service_types || []).map(t => ({
        value: t.slug,
        label: t.label,
        categories: t.categories || [],
    }));
});

const serviceTypesForm = useForm({
    service_types: (props.service_types || []).map(t => ({
        slug: t.slug,
        label: t.label,
        categories: t.categories || [],
    })),
});

const importForm = useForm({
    file: null,
});

const form = useForm({
    vehicle_id: '',
    prices: []
});

const openEditModal = (vehicle) => {
    editingVehicle.value = vehicle;
    form.vehicle_id = vehicle.id;
    
    const initialPrices = [];
    categories.value.forEach(cat => {
        UNITS.forEach(u => {
            const bdg = vehicle.pricings ? vehicle.pricings.find(p => p.kota === 'bandung' && p.paket_tipe === cat.value && (p.unit || 'per_hari') === u.value) : null;
            const jkt = vehicle.pricings ? vehicle.pricings.find(p => p.kota === 'jakarta' && p.paket_tipe === cat.value && (p.unit || 'per_hari') === u.value) : null;

            const base = bdg?.harga_dasar ?? (jkt?.harga_dasar ? Math.max(parseInt(jkt.harga_dasar, 10) - JAKARTA_MARKUP, 0) : 0);
            const promo = bdg?.harga_promo ?? (jkt?.harga_promo ? Math.max(parseInt(jkt.harga_promo, 10) - JAKARTA_MARKUP, 0) : null);

            initialPrices.push({
                kota: 'bandung',
                paket_tipe: cat.value,
                unit: u.value,
                harga_dasar: base,
                harga_promo: promo,
            });
        });
    });
    
    form.prices = initialPrices;
    showModal.value = true;
};

const submit = () => {
    form.post(route('admin.pricing.store'), {
        onSuccess: () => {
            showModal.value = false;
        }
    });
};

const priceRef = (paket, unit) => {
    return form.prices.find(p => p.paket_tipe === paket && p.unit === unit) || { harga_dasar: 0, harga_promo: null };
};

const saveServiceTypes = () => {
    serviceTypesForm.put(route('admin.pricing.service-types'), {
        preserveScroll: true,
        onSuccess: () => alert('Jenis sewa berhasil diperbarui!'),
    });
};

const addServiceType = () => {
    serviceTypesForm.service_types.push({
        slug: '',
        label: '',
        categories: [],
    });
};

const removeServiceType = (index) => {
    serviceTypesForm.service_types.splice(index, 1);
};

const toggleServiceCategory = (typeIndex, categoryValue) => {
    const item = serviceTypesForm.service_types[typeIndex];
    const idx = item.categories.indexOf(categoryValue);
    if (idx === -1) item.categories.push(categoryValue);
    else item.categories.splice(idx, 1);
};

const handleImportFile = (e) => {
    importForm.file = e.target.files?.[0] || null;
};

const submitImport = () => {
    importForm.post(route('admin.pricing.import'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            importForm.reset('file');
        },
    });
};

const formatPrice = (value) => {
    if (value === null || value === undefined || value === 0) return '-';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const getBasePrice = (vehicle, category) => {
    if (!vehicle.pricings) return 0;
    const bdg = vehicle.pricings.find(p => p.paket_tipe === category && p.kota === 'bandung' && (p.unit || 'per_hari') === 'per_hari');
    if (bdg && bdg.harga_dasar) return parseInt(bdg.harga_dasar, 10);
    const jkt = vehicle.pricings.find(p => p.paket_tipe === category && p.kota === 'jakarta' && (p.unit || 'per_hari') === 'per_hari');
    if (jkt && jkt.harga_dasar) return Math.max(parseInt(jkt.harga_dasar, 10) - JAKARTA_MARKUP, 0);
    return 0;
};

const getBasePriceForUnit = (vehicle, category, unit) => {
    if (!vehicle.pricings) return 0;
    const bdg = vehicle.pricings.find(p => p.paket_tipe === category && p.kota === 'bandung' && (p.unit || 'per_hari') === unit);
    if (bdg && bdg.harga_dasar) return parseInt(bdg.harga_dasar, 10);
    const jkt = vehicle.pricings.find(p => p.paket_tipe === category && p.kota === 'jakarta' && (p.unit || 'per_hari') === unit);
    if (jkt && jkt.harga_dasar) return Math.max(parseInt(jkt.harga_dasar, 10) - JAKARTA_MARKUP, 0);
    return 0;
};

const getEffectivePrice = (vehicle, category, city, unit = 'per_hari') => {
    const base = getBasePriceForUnit(vehicle, category, unit);
    if (!base) return 0;
    return city === 'jakarta' ? base + JAKARTA_MARKUP : base;
};

const getPriceFor = (vehicle, category, city, unit = 'per_hari') => {
    const val = getEffectivePrice(vehicle, category, city, unit);
    return val ? formatPrice(val) : '-';
};
</script>

<template>
    <Head title="Manajemen Harga" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-slate-900">
                        Manajemen Harga & Promo
                    </h2>
                    <p class="text-slate-500 text-sm">Atur harga per kategori dan per wilayah untuk setiap armada.</p>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div>
                            <h3 class="text-xl font-display font-bold text-slate-900">Import Harga & Armada</h3>
                            <p class="text-slate-500 text-sm mt-1">Unduh template Excel, edit, lalu import untuk update massal. Template akan mengikuti jenis sewa yang aktif.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                            <a :href="route('admin.pricing.template')" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 text-white rounded-2xl text-sm font-bold hover:bg-slate-800 transition-all">
                                Download Template
                            </a>
                            <div class="flex items-center gap-3">
                                <input type="file" accept=".xlsx,.xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv" @change="handleImportFile" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-700 file:font-bold hover:file:bg-slate-200">
                                <button @click="submitImport" :disabled="importForm.processing || !importForm.file" class="px-6 py-3 bg-primary text-white rounded-2xl text-sm font-bold hover:bg-primary-dark transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                    {{ importForm.processing ? 'Import...' : 'Import' }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="importForm.errors.file" class="text-red-500 text-xs mt-3">{{ importForm.errors.file }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div>
                            <h3 class="text-xl font-display font-bold text-slate-900">Jenis Sewa (Dinamis)</h3>
                            <p class="text-slate-500 text-sm mt-1">Jenis sewa ini dipakai untuk input harga, form booking, dan template import.</p>
                        </div>
                        <div class="flex gap-3">
                            <button @click="addServiceType" type="button" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl text-sm font-bold hover:bg-slate-200 transition-all">
                                + Tambah Jenis
                            </button>
                            <button @click="saveServiceTypes" type="button" :disabled="serviceTypesForm.processing" class="px-6 py-3 bg-primary text-white rounded-2xl text-sm font-bold hover:bg-primary-dark transition-all disabled:opacity-50">
                                Simpan
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div v-for="(t, idx) in serviceTypesForm.service_types" :key="idx" class="p-5 rounded-2xl border border-slate-100 bg-slate-50">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-4">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Slug</label>
                                    <input v-model="t.slug" type="text" class="mt-2 w-full rounded-2xl border-slate-100 bg-white focus:border-primary focus:ring-primary py-3 px-4 font-bold text-sm" placeholder="contoh: lepas_kunci">
                                </div>
                                <div class="md:col-span-5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Label</label>
                                    <input v-model="t.label" type="text" class="mt-2 w-full rounded-2xl border-slate-100 bg-white focus:border-primary focus:ring-primary py-3 px-4 font-bold text-sm" placeholder="contoh: Lepas Kunci">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kategori Form</label>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <button type="button" @click="toggleServiceCategory(idx, 'sewa_mobil')" :class="t.categories.includes('sewa_mobil') ? 'bg-primary text-white' : 'bg-white text-slate-500'" class="px-3 py-2 rounded-xl text-[11px] font-bold border border-slate-100">
                                            Sewa
                                        </button>
                                        <button type="button" @click="toggleServiceCategory(idx, 'city_tour')" :class="t.categories.includes('city_tour') ? 'bg-primary text-white' : 'bg-white text-slate-500'" class="px-3 py-2 rounded-xl text-[11px] font-bold border border-slate-100">
                                            Tour
                                        </button>
                                        <button type="button" @click="toggleServiceCategory(idx, 'travel')" :class="t.categories.includes('travel') ? 'bg-primary text-white' : 'bg-white text-slate-500'" class="px-3 py-2 rounded-xl text-[11px] font-bold border border-slate-100">
                                            Travel
                                        </button>
                                    </div>
                                </div>
                                <div class="md:col-span-1 flex justify-end">
                                    <button type="button" @click="removeServiceType(idx)" class="px-3 py-2 bg-red-500 text-white rounded-xl text-xs font-bold hover:bg-red-600 transition-all">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="serviceTypesForm.hasErrors" class="text-red-500 text-xs mt-3">Periksa kembali slug/label yang diisi.</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 sticky left-0 bg-slate-50">Armada</th>
                                    <th v-for="cat in categories" :key="cat.value" class="px-6 py-4 text-center border-l border-slate-100">
                                        {{ cat.label }}
                                        <div class="flex justify-around mt-1 text-[8px]">
                                            <span>BDG</span>
                                            <span>JKT</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="v in vehicles" :key="v.id" class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900 sticky left-0 bg-white shadow-[10px_0_15px_-10px_rgba(0,0,0,0.05)]">
                                        {{ v.nama }}
                                        <div class="text-[10px] text-slate-400 font-normal">{{ v.tipe }}</div>
                                    </td>
                                    <td v-for="cat in categories" :key="cat.value" class="px-4 py-4 border-l border-slate-100">
                                        <div class="flex flex-col gap-1 text-[10px] items-center" v-if="v.pricings">
                                            <div class="w-full space-y-1">
                                                <div class="flex justify-between w-full gap-4" v-for="u in UNITS" :key="u.value">
                                                    <span class="text-slate-400 font-bold">{{ u.label }}</span>
                                                    <div class="flex justify-between gap-4 min-w-[180px]">
                                                        <span class="text-blue-500 font-mono">{{ getPriceFor(v, cat.value, 'bandung', u.value) }}</span>
                                                        <span class="text-orange-500 font-mono">{{ getPriceFor(v, cat.value, 'jakarta', u.value) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="text-center text-slate-300">-</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="openEditModal(v)" class="px-4 py-2 bg-primary/10 text-primary rounded-xl font-bold text-xs hover:bg-primary hover:text-white transition-all">
                                            Edit Harga
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="vehicles.length === 0">
                                    <td :colspan="categories.length + 2" class="px-6 py-10 text-center text-slate-400">Belum ada armada.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm">
            <div class="min-h-full flex items-start justify-center p-4 py-8">
                <div class="bg-white rounded-[2.5rem] w-full max-w-4xl p-8 shadow-2xl max-h-[calc(100vh-4rem)] overflow-y-auto">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-2xl font-display font-bold text-slate-900">Edit Harga: {{ editingVehicle?.nama }}</h3>
                        <p class="text-slate-500 text-sm">Input cukup harga Bandung, harga Jakarta otomatis + Rp 200.000.</p>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-sm font-bold text-slate-900 uppercase tracking-widest">Harga Bandung</span>
                            </div>
                            <div class="text-xs font-bold text-slate-500">Jakarta = Bandung + Rp 200.000</div>
                        </div>

                        <div v-for="cat in categories" :key="cat.value" class="rounded-2xl border border-slate-100 bg-white p-5 space-y-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-extrabold text-slate-900">{{ cat.label }}</p>
                            </div>

                            <div class="grid grid-cols-1 gap-3">
                                <div v-for="u in UNITS" :key="u.value" class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">{{ u.label }}</p>
                                        <p class="text-[11px] font-bold text-slate-400">Jakarta auto + Rp 200.000</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400">BASE</span>
                                            <input
                                                v-model="priceRef(cat.value, u.value).harga_dasar"
                                                type="number"
                                                class="w-full pl-12 pr-4 py-3 rounded-xl border-slate-100 bg-white focus:border-primary focus:ring-primary font-bold text-xs"
                                                placeholder="Harga Bandung"
                                            >
                                        </div>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-green-400">PROMO</span>
                                            <input
                                                v-model="priceRef(cat.value, u.value).harga_promo"
                                                type="number"
                                                class="w-full pl-14 pr-4 py-3 rounded-xl border-slate-100 bg-white focus:border-primary focus:ring-primary font-bold text-xs"
                                                placeholder="Promo Bandung (Opsional)"
                                            >
                                        </div>
                                        <div class="rounded-xl border border-slate-100 bg-white px-4 py-3">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jakarta (Auto)</p>
                                            <p class="text-sm font-extrabold text-slate-900 mt-1">
                                                {{
                                                    priceRef(cat.value, u.value).harga_dasar
                                                        ? formatPrice(parseInt(priceRef(cat.value, u.value).harga_dasar, 10) + JAKARTA_MARKUP)
                                                        : '-'
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                        <button @click="showModal = false" type="button" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                        <button type="submit" :disabled="form.processing" class="px-8 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 disabled:opacity-50">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan Harga' }}
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
