<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    testimonials: Array,
});

const showModal = ref(false);
const editingItem = ref(null);

const form = useForm({
    nama: '',
    kota: '',
    kendaraan_disewa: '',
    ulasan_id: '',
    ulasan_en: '',
    rating: 5,
    is_featured: true,
    is_active: true,
    status: 'approved',
    sort_order: 0,
});

const openCreateModal = () => {
    editingItem.value = null;
    form.reset();
    form.status = 'approved';
    form.is_active = true;
    showModal.value = true;
};

const openEditModal = (item) => {
    editingItem.value = item;
    form.nama = item.nama;
    form.kota = item.kota;
    form.kendaraan_disewa = item.kendaraan_disewa;
    form.ulasan_id = item.ulasan_id;
    form.ulasan_en = item.ulasan_en;
    form.rating = item.rating;
    form.is_featured = !!item.is_featured;
    form.is_active = !!item.is_active;
    form.status = item.status || 'approved';
    form.sort_order = item.sort_order;
    showModal.value = true;
};

const submit = () => {
    if (editingItem.value) {
        form.put(route('admin.testimonials.update', editingItem.value.id), {
            onSuccess: () => {
                showModal.value = false;
            }
        });
    } else {
        form.post(route('admin.testimonials.store'), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus testimoni ini?')) {
        router.delete(route('admin.testimonials.destroy', id));
    }
};
</script>

<template>
    <Head title="Manajemen Testimoni" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    Manajemen Testimoni Pelanggan
                </h2>
                <button @click="openCreateModal" class="px-4 py-2 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary-dark transition-all">
                    + Tambah Testimoni
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4">Pelanggan</th>
                                    <th class="px-6 py-4">Kota & Unit</th>
                                    <th class="px-6 py-4">Rating</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="item in testimonials" :key="item.id" class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900">{{ item.nama }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-slate-600">{{ item.kota }}</p>
                                        <p class="text-xs text-slate-400">{{ item.kendaraan_disewa }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex text-yellow-400">
                                            <template v-for="i in 5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="i <= item.rating ? 'fill-current' : 'text-slate-200'" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span v-if="item.status === 'pending'" class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase w-fit">Pending Moderasi</span>
                                            <span v-else-if="item.status === 'approved'" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase w-fit">Disetujui</span>
                                            <span v-else class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase w-fit">Ditolak</span>
                                            
                                            <span v-if="item.is_featured" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase w-fit">Featured di Beranda</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button @click="openEditModal(item)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button @click="deleteItem(item.id)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="testimonials.length === 0">
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada testimoni.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-[2.5rem] w-full max-w-2xl p-8 shadow-2xl">
                <h3 class="text-2xl font-display font-bold text-slate-900 mb-6">{{ editingItem ? 'Edit Testimoni' : 'Tambah Testimoni' }}</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Pelanggan</label>
                            <input v-model="form.nama" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Kota</label>
                            <input v-model="form.kota" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Kendaraan Disewa</label>
                            <input v-model="form.kendaraan_disewa" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Rating</label>
                            <select v-model="form.rating" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold">
                                <option v-for="i in 5" :key="i" :value="i">{{ i }} Bintang</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Ulasan (Bahasa Indonesia)</label>
                        <textarea v-model="form.ulasan_id" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-medium" rows="3" required></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Ulasan (English)</label>
                        <textarea v-model="form.ulasan_en" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-medium" rows="3"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Status Moderasi</label>
                            <select v-model="form.status" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold">
                                <option value="pending">Pending</option>
                                <option value="approved">Setujui (Approved)</option>
                                <option value="rejected">Tolak (Rejected)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Urutan</label>
                            <input v-model="form.sort_order" type="number" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <input v-model="form.is_active" type="checkbox" id="is_active" class="w-6 h-6 rounded border-slate-200 text-primary focus:ring-primary">
                            <label for="is_active" class="font-bold text-slate-700">Aktif (Dapat Muncul di Website)</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input v-model="form.is_featured" type="checkbox" id="is_featured" class="w-6 h-6 rounded border-slate-200 text-primary focus:ring-primary">
                            <label for="is_featured" class="font-bold text-slate-700">Tampilkan di Beranda (Featured)</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6">
                        <button @click="showModal = false" type="button" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
