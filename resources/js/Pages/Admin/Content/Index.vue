<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    contents: Array,
});

// Group contents by section
const sections = ref(['branding', 'payment', 'hero', 'services', 'about', 'social']);
const groupedContents = ref({});

props.contents.forEach(item => {
    if (!groupedContents.value[item.section]) {
        groupedContents.value[item.section] = [];
    }
    groupedContents.value[item.section].push(item);
});

const form = useForm({
    items: props.contents.map(item => ({
        id: item.id,
        section: item.section,
        key: item.key,
        value_id: item.value_id,
        value_en: item.value_en,
        type: item.type,
        value_id_file: null // Add this to track file upload
    }))
});

// QRIS upload state
const qrisFile = ref(null);
const uploadingQris = ref(false);
const qrisPreview = ref(null);

const submit = (section) => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT'
    })).post(route('admin.content.update', section), {
        preserveScroll: true,
        onSuccess: () => alert('Konten ' + section + ' berhasil diperbarui!')
    });
};

const uploadQris = () => {
    if (!qrisFile.value) return;
    
    uploadingQris.value = true;
    const formData = new FormData();
    formData.append('qris_image', qrisFile.value);
    
    fetch(route('admin.content.upload-qris'), {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('QRIS berhasil diupload!');
            // Refresh page to show new QRIS
            window.location.reload();
        } else {
            alert('Gagal upload QRIS: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat upload QRIS');
    })
    .finally(() => {
        uploadingQris.value = false;
    });
};

// Preview QRIS when file is selected
watch(qrisFile, (file) => {
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            qrisPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        qrisPreview.value = null;
    }
});
</script>

<template>
    <Head title="Manajemen Konten" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-900">
                Manajemen Konten Landing Page
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <div v-for="section in sections" :key="section" class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                                <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-display font-bold text-slate-900 uppercase tracking-tight">
                                {{ section === 'branding' ? 'Logo & Branding' : (section === 'payment' ? 'Pembayaran & QRIS' : section + ' Section') }}
                            </h3>
                            <p class="text-slate-500 text-sm">Update {{ section === 'branding' ? 'logo perusahaan' : (section === 'payment' ? 'gambar QRIS pembayaran DP' : 'teks dan konten') }} untuk bagian {{ section }} di landing page.</p>
                        </div>
                        <button @click="submit(section)" :disabled="form.processing" class="px-8 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 disabled:opacity-50">
                            Simpan Perubahan
                        </button>
                    </div>

                    <!-- QRIS Upload Section -->
                    <div v-if="section === 'payment'" class="mb-8 p-6 bg-blue-50 rounded-3xl border border-blue-100">
                        <h4 class="text-lg font-bold text-blue-900 mb-4">Upload QRIS untuk Pembayaran DP</h4>
                        <form @submit.prevent="uploadQris" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-2">Pilih Gambar QRIS</label>
                                <input type="file" @input="qrisFile = $event.target.files[0]" accept="image/*" class="block w-full text-sm text-blue-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200">
                                <p class="text-xs text-blue-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                            </div>
                            <button type="submit" :disabled="!qrisFile || uploadingQris" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:opacity-50">
                                <span v-if="!uploadingQris">Upload QRIS</span>
                                <span v-else class="flex items-center gap-2">
                                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></div>
                                    Uploading...
                                </span>
                            </button>
                        </form>
                        <div v-if="qrisPreview" class="mt-4">
                            <p class="text-sm font-medium text-blue-700 mb-2">Preview:</p>
                            <img :src="qrisPreview" alt="QRIS Preview" class="max-w-xs rounded-lg border border-blue-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <template v-for="(item, index) in form.items" :key="item.id">
                            <div v-if="item.section === section" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                                <div class="md:col-span-2">
                                    <span class="text-[10px] font-bold text-primary uppercase tracking-widest bg-primary/10 px-3 py-1 rounded-full">{{ item.key.replace('_', ' ') }}</span>
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">
                                        {{ item.type === 'image' ? 'Upload Gambar/Logo' : 'Bahasa Indonesia' }}
                                    </label>
                                    <template v-if="item.type === 'image'">
                                        <div class="flex flex-col gap-4">
                                            <div class="h-20 w-auto bg-slate-800 p-4 rounded-xl flex items-center justify-center border border-slate-700">
                                                <img v-if="item.value_id" :src="item.value_id" class="h-full w-auto object-contain">
                                                <span v-else class="text-slate-500 text-xs italic">Belum ada gambar</span>
                                            </div>
                                            <input type="file" @input="item.value_id_file = $event.target.files[0]" class="text-xs block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all">
                                        </div>
                                    </template>
                                    <template v-else-if="item.type === 'textarea'">
                                        <textarea v-model="item.value_id" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm" rows="3"></textarea>
                                    </template>
                                    <template v-else>
                                        <input v-model="item.value_id" type="text" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm">
                                    </template>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">
                                        {{ item.type === 'image' ? 'Keterangan' : 'English' }}
                                    </label>
                                    <template v-if="item.type === 'image'">
                                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 h-full">
                                            <p class="text-xs text-blue-600 leading-relaxed font-medium">
                                                <span class="font-bold block mb-1">Panduan:</span>
                                                Gunakan file gambar (PNG/JPG/SVG). 
                                                <template v-if="item.key.includes('logo')">
                                                    Logo <strong>Light</strong> digunakan pada background gelap (Hero), logo <strong>Dark</strong> digunakan pada navbar putih.
                                                </template>
                                            </p>
                                        </div>
                                    </template>
                                    <template v-else-if="item.type === 'textarea'">
                                        <textarea v-model="item.value_en" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm" rows="3"></textarea>
                                    </template>
                                    <template v-else>
                                        <input v-model="item.value_en" type="text" class="w-full rounded-2xl border-slate-200 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm">
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
