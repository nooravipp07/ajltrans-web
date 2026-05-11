<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    vehicle: {
        type: Object,
        default: () => ({
            id: null,
            nama: '',
            tipe: '',
            vehicle_size: 'kecil',
            kategori: [],
            tier: 'ekonomis',
            status: 'tersedia',
            badge: 'none',
            is_active: true,
            sort_order: 0,
        })
    }
});

const isEditing = !!props.vehicle.id;

const form = useForm({
    nama: props.vehicle.nama,
    tipe: props.vehicle.tipe,
    vehicle_size: props.vehicle.vehicle_size || 'kecil',
    kategori: props.vehicle.kategori || [],
    tier: props.vehicle.tier,
    status: props.vehicle.status,
    badge: props.vehicle.badge || 'none',
    sort_order: props.vehicle.sort_order || 0,
    photos: [],
    existing_photos: props.vehicle.foto_urls || [],
});

const submit = () => {
    // We use post for update too because we want to send files (multipart/form-data)
    // Laravel can handle PUT with _method field
    if (isEditing) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.vehicles.update', props.vehicle.id));
    } else {
        form.post(route('admin.vehicles.store'));
    }
};

const handlePhotoUpload = (e) => {
    form.photos = Array.from(e.target.files);
};

const removeExistingPhoto = (index) => {
    form.existing_photos.splice(index, 1);
};

const categories = [
    { value: 'sewa_mobil', label: 'Sewa Mobil' },
    { value: 'city_tour', label: 'City Tour' },
    { value: 'travel', label: 'Travel' }
];

const tiers = [
    { value: 'ekonomis', label: 'Ekonomis' },
    { value: 'mid_range', label: 'Mid-Range' },
    { value: 'premium', label: 'Premium' }
];

const statuses = [
    { value: 'tersedia', label: 'Tersedia' },
    { value: 'tidak_tersedia', label: 'Tidak Tersedia' },
    { value: 'perawatan', label: 'Perawatan' }
];

const badges = [
    { value: 'none', label: 'None' },
    { value: 'populer', label: 'Populer' },
    { value: 'baru', label: 'Baru' },
    { value: 'promo', label: 'Promo' }
];

const vehicleSizes = [
    { value: 'kecil', label: 'Kendaraan Kecil' },
    { value: 'besar', label: 'Kendaraan Besar' },
];

const toggleCategory = (value) => {
    const index = form.kategori.indexOf(value);
    if (index === -1) {
        form.kategori.push(value);
    } else {
        form.kategori.splice(index, 1);
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Armada' : 'Tambah Armada'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.vehicles.index')" class="text-slate-400 hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    {{ isEditing ? 'Edit Data Armada' : 'Tambah Armada Baru' }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Kendaraan -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Kendaraan</label>
                                <input v-model="form.nama" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" placeholder="Contoh: Toyota Alphard" required>
                                <div v-if="form.errors.nama" class="text-red-500 text-xs mt-1">{{ form.errors.nama }}</div>
                            </div>

                            <!-- Tipe Kendaraan -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Tipe Kendaraan</label>
                                <input v-model="form.tipe" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" placeholder="Contoh: Luxury MPV" required>
                                <div v-if="form.errors.tipe" class="text-red-500 text-xs mt-1">{{ form.errors.tipe }}</div>
                            </div>

                            <!-- Ukuran Kendaraan -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Ukuran Kendaraan</label>
                                <select v-model="form.vehicle_size" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold appearance-none">
                                    <option v-for="s in vehicleSizes" :key="s.value" :value="s.value">{{ s.label }}</option>
                                </select>
                                <div v-if="form.errors.vehicle_size" class="text-red-500 text-xs mt-1">{{ form.errors.vehicle_size }}</div>
                            </div>

                            <!-- Kelas Kendaraan (Tier) -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Kelas Kendaraan (Tier)</label>
                                <select v-model="form.tier" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold appearance-none">
                                    <option v-for="t in tiers" :key="t.value" :value="t.value">{{ t.label }}</option>
                                </select>
                                <div v-if="form.errors.tier" class="text-red-500 text-xs mt-1">{{ form.errors.tier }}</div>
                            </div>

                            <!-- Kategori Layanan -->
                            <div class="space-y-2 md:col-span-2 border-t border-slate-100 pt-8">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1 block mb-3">Kategori Layanan</label>
                                <div class="flex flex-wrap gap-3">
                                    <button v-for="c in categories" :key="c.value" 
                                            type="button"
                                            @click="toggleCategory(c.value)"
                                            :class="form.kategori.includes(c.value) ? 'bg-primary text-white border-primary' : 'bg-slate-50 text-slate-400 border-slate-100 hover:bg-slate-100'"
                                            class="px-6 py-3 rounded-xl border-2 font-bold text-sm transition-all">
                                        {{ c.label }}
                                    </button>
                                </div>
                                <div v-if="form.errors.kategori" class="text-red-500 text-xs mt-1">{{ form.errors.kategori }}</div>
                            </div>

                            <!-- Status & Badge -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Status</label>
                                <select v-model="form.status" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold appearance-none">
                                    <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                                </select>
                                <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Badge</label>
                                <select v-model="form.badge" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold appearance-none">
                                    <option v-for="b in badges" :key="b.value" :value="b.value">{{ b.label }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Urutan (Sort)</label>
                                <input v-model="form.sort_order" type="number" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold">
                            </div>

                            <!-- Photo Management -->
                            <div class="space-y-4 md:col-span-2 border-t border-slate-100 pt-8">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1 block mb-3">Foto Armada</label>
                                
                                <!-- Existing Photos -->
                                <div v-if="form.existing_photos.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                    <div v-for="(url, index) in form.existing_photos" :key="index" class="relative group aspect-video rounded-2xl overflow-hidden bg-slate-100">
                                        <img :src="url" class="w-full h-full object-cover">
                                        <button @click="removeExistingPhoto(index)" type="button" class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Upload New -->
                                <div class="border-2 border-dashed border-slate-200 rounded-[2rem] p-8 text-center hover:border-primary transition-colors relative cursor-pointer">
                                    <input @change="handlePhotoUpload" type="file" multiple accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                                    <div class="text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                        </svg>
                                        <p class="font-bold text-sm">Klik untuk tambah foto baru</p>
                                        <p class="text-xs mt-1" v-if="form.photos.length > 0">{{ form.photos.length }} file terpilih</p>
                                    </div>
                                </div>
                                <div v-if="form.errors.photos" class="text-red-500 text-xs mt-1">{{ form.errors.photos }}</div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                            <Link :href="route('admin.vehicles.index')" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all">Batal</Link>
                            <button type="submit" :disabled="form.processing" class="px-10 py-4 bg-primary text-white rounded-2xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/25 disabled:opacity-50">
                                {{ isEditing ? 'Simpan Perubahan' : 'Tambah Armada' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
