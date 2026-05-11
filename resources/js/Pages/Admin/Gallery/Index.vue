<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    galleries: Array,
});

const showModal = ref(false);
const editingItem = ref(null);

const form = useForm({
    title_id: '',
    title_en: '',
    type: 'photo',
    file: null,
    url: '',
    sort_order: 0,
});

const openCreateModal = () => {
    editingItem.value = null;
    form.reset();
    showModal.value = true;
};

const openEditModal = (item) => {
    editingItem.value = item;
    form.title_id = item.title_id;
    form.title_en = item.title_en;
    form.type = item.type;
    form.url = item.url;
    form.sort_order = item.sort_order;
    showModal.value = true;
};

const submit = () => {
    if (editingItem.value) {
        form.put(route('admin.gallery.update', editingItem.value.id), {
            onSuccess: () => {
                showModal.value = false;
            }
        });
    } else {
        form.post(route('admin.gallery.store'), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Hapus item galeri ini?')) {
        router.delete(route('admin.gallery.destroy', id));
    }
};

const handleFileUpload = (e) => {
    form.file = e.target.files[0];
};
</script>

<template>
    <Head title="Manajemen Galeri" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    Manajemen Galeri (Foto & Video)
                </h2>
                <button @click="openCreateModal" class="px-4 py-2 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary-dark transition-all">
                    + Tambah Item
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div v-for="item in galleries" :key="item.id" class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                        <div class="aspect-square relative bg-slate-100">
                            <template v-if="item.type === 'photo'">
                                <img :src="item.url" class="w-full h-full object-cover">
                            </template>
                            <template v-else>
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </template>
                            
                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                <button @click="openEditModal(item)" class="p-3 bg-white text-primary rounded-xl hover:bg-primary hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="deleteItem(item.id)" class="p-3 bg-white text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-slate-900 truncate">{{ item.title_id }}</h4>
                            <span class="text-[10px] font-bold uppercase text-slate-400">{{ item.type }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl">
                <h3 class="text-2xl font-display font-bold text-slate-900 mb-6">{{ editingItem ? 'Edit Item' : 'Tambah Item Galeri' }}</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Judul (ID)</label>
                        <input v-model="form.title_id" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Tipe</label>
                            <select v-model="form.type" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold">
                                <option value="photo">Foto</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Urutan</label>
                            <input v-model="form.sort_order" type="number" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold">
                        </div>
                    </div>

                    <div v-if="!editingItem" class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Upload File</label>
                        <input @change="handleFileUpload" type="file" class="w-full p-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl">
                    </div>

                    <div v-if="form.type === 'video' && editingItem" class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">URL Video (YouTube/Vimeo)</label>
                        <input v-model="form.url" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold">
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
