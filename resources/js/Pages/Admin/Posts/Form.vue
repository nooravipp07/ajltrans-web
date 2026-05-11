<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    post: {
        type: Object,
        default: () => ({
            id: null,
            title_id: '',
            title_en: '',
            content_id: '',
            content_en: '',
            is_published: false,
        })
    }
});

const isEditing = !!props.post.id;

const form = useForm({
    title_id: props.post.title_id,
    title_en: props.post.title_en,
    content_id: props.post.content_id,
    content_en: props.post.content_en,
    is_published: props.post.is_published,
});

const submit = () => {
    if (isEditing) {
        form.put(route('admin.posts.update', props.post.id));
    } else {
        form.post(route('admin.posts.store'));
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Berita' : 'Tulis Berita'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.posts.index')" class="text-slate-400 hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    {{ isEditing ? 'Edit Berita' : 'Tulis Berita Baru' }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="grid grid-cols-1 gap-8">
                            <!-- Judul -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Judul (Bahasa Indonesia)</label>
                                    <input v-model="form.title_id" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" placeholder="Masukkan judul..." required>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Judul (English)</label>
                                    <input v-model="form.title_en" type="text" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-bold" placeholder="Enter title...">
                                </div>
                            </div>

                            <!-- Konten -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Konten (Bahasa Indonesia)</label>
                                <textarea v-model="form.content_id" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm" rows="10" placeholder="Tulis isi berita di sini..." required></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Konten (English)</label>
                                <textarea v-model="form.content_en" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm" rows="10" placeholder="Write content here..."></textarea>
                            </div>

                            <div class="flex items-center gap-3 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                <input v-model="form.is_published" type="checkbox" id="is_published" class="w-6 h-6 rounded border-slate-200 text-primary focus:ring-primary">
                                <label for="is_published" class="font-bold text-slate-700">Terbitkan Berita Sekarang</label>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-end gap-4">
                            <Link :href="route('admin.posts.index')" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all">Batal</Link>
                            <button type="submit" :disabled="form.processing" class="px-10 py-4 bg-primary text-white rounded-2xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/25 disabled:opacity-50">
                                {{ isEditing ? 'Simpan Perubahan' : 'Terbitkan Berita' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
