<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const logo = computed(() => page.props.logo);

const navigation = [
    { name: 'Dashboard', href: route('admin.dashboard'), icon: 'dashboard', roles: ['superadmin', 'admin', 'operator'] },
    { name: 'Manajemen Armada', href: route('admin.vehicles.index'), icon: 'fleet', roles: ['superadmin', 'admin'] },
    { name: 'Manajemen Booking', href: route('admin.bookings.index'), icon: 'booking', roles: ['superadmin', 'admin', 'operator'] },
    { name: 'Manajemen Harga', href: route('admin.pricing.index'), icon: 'pricing', roles: ['superadmin', 'admin'] },
    { name: 'Manajemen Customer', href: route('admin.customers.index'), icon: 'customer', roles: ['superadmin', 'admin'] },
    { name: 'CMS Content', href: route('admin.content'), icon: 'cms', roles: ['superadmin'] },
    { name: 'Berita / Artikel', href: route('admin.posts.index'), icon: 'cms', roles: ['superadmin', 'admin'] },
    { name: 'Galeri', href: route('admin.gallery.index'), icon: 'dashboard', roles: ['superadmin', 'admin'] },
    { name: 'Testimoni', href: route('admin.testimonials.index'), icon: 'testimonial', roles: ['superadmin', 'admin'] },
    { name: 'Template WhatsApp', href: route('admin.wa-templates.index'), icon: 'whatsapp', roles: ['superadmin', 'admin'] },
];

const filteredNav = computed(() => {
    if (!user.value) return [];
    return navigation.filter(item => item.roles.includes(user.value.role));
});

const isMobileMenuOpen = ref(false);

const getIcon = (name) => {
    const icons = {
        dashboard: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />',
        fleet: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />',
        booking: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />',
        pricing: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM12 2a10 10 0 100 20 10 10 0 000-20z" />',
        customer: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />',
        cms: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />',
        testimonial: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />',
        whatsapp: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21l1.8-5.4a9 9 0 113.6 3.6L3 21z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 10.5c.5 1.5 1.5 2.5 3 3l1-1c.2-.2.5-.3.8-.2l2.2.7c.4.1.6.6.4 1A3 3 0 0113 17c-3.9 0-7-3.1-7-7a3 3 0 011.5-2.6c.4-.2.9 0 1 .4l.7 2.2c.1.3 0 .6-.2.8l-1 1z" />',
    };
    return icons[name] || '';
};

const isUrlActive = (item) => {
    try {
        const currentPath = page.url.split('?')[0];
        const targetPath = new URL(item.href).pathname;
        
        if (item.name === 'Dashboard') {
            return currentPath === targetPath;
        }
        return currentPath.startsWith(targetPath);
    } catch (e) {
        return false;
    }
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-white border-r border-slate-100 flex-col hidden lg:flex">
            <div class="p-8">
                <Link :href="route('admin.dashboard')">
                    <img :src="logo" class="h-10 w-auto" alt="AJL Trans Logo">
                </Link>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4">
                <template v-for="item in filteredNav" :key="item.name">
                    <Link 
                        :href="item.href"
                        class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group"
                        :class="isUrlActive(item) ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-html="getIcon(item.icon)"></svg>
                        {{ item.name }}
                    </Link>
                </template>
            </nav>

            <div class="p-8 border-t border-slate-50" v-if="user">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary font-bold">
                        {{ user.nama?.charAt(0) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 leading-none">{{ user.nama }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ user.role }}</p>
                    </div>
                </div>
                <Link :href="route('admin.logout')" method="post" as="button" class="w-full py-3 bg-red-50 text-red-500 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition-all">
                    Keluar Sistem
                </Link>
            </div>
        </aside>

        <!-- Mobile Nav Toggle -->
        <div class="lg:hidden fixed top-4 right-4 z-50">
            <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="p-3 bg-white rounded-xl shadow-lg border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div v-if="isMobileMenuOpen" class="fixed inset-0 z-40 lg:hidden">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="isMobileMenuOpen = false"></div>
            <aside class="absolute inset-y-0 left-0 w-72 bg-white shadow-2xl flex flex-col p-8">
                <div class="mb-12">
                    <img :src="logo" class="h-10 w-auto" alt="Logo">
                </div>
                <nav class="flex-1 space-y-2">
                    <template v-for="item in filteredNav" :key="item.name">
                        <Link 
                            :href="item.href"
                            @click="isMobileMenuOpen = false"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all"
                            :class="isUrlActive(item) ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-html="getIcon(item.icon)"></svg>
                            {{ item.name }}
                        </Link>
                    </template>
                </nav>
            </aside>
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto h-screen">
            <header v-if="$slots.header" class="bg-white border-b border-slate-100 sticky top-0 z-30">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8">
                <slot />
            </div>
        </main>
    </div>
</template>
