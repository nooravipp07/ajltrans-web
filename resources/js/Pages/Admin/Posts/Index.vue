<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    posts: Array,
});

const deletePost = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
        router.delete(route('admin.posts.destroy', id));
    }
};
</script>

<template>
    <Head title="Manajemen Berita" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    Manajemen Berita & Artikel
                </h2>
                <Link
                    :href="route('admin.posts.create')"
                    class="px-4 py-2 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary-dark transition-all"
                >
                    + Tulis Berita
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4">Judul</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="post in posts" :key="post.id" class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900">{{ post.title_id }}</p>
                                        <p class="text-xs text-slate-400 italic">{{ post.slug }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="post.is_published ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                            {{ post.is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ post.published_at ? new Date(post.published_at).toLocaleDateString('id-ID') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <Link :href="route('admin.posts.edit', post.id)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </Link>
                                            <button @click="deletePost(post.id)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="posts.length === 0">
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 font-medium">Belum ada berita yang ditulis.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
