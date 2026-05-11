<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Berita & Artikel - AJL Trans</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Syne:wght@400..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #F5F4F0; }
        .font-display { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 60">
    <!-- Navbar -->
    <nav :class="scrolled ? 'bg-white/80 backdrop-blur-lg border-b border-slate-100 py-4' : 'bg-transparent py-6'" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
        <div class="container mx-auto px-4 md:px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <img :src="scrolled ? '{{ $cms['branding']['logo_dark']->value_id ?? '/images/logo-dark.png' }}' : '{{ $cms['branding']['logo_light']->value_id ?? '/images/logo-light.png' }}'" 
                     class="h-10 w-auto transition-all duration-300" alt="AJL Trans Logo">
            </a>
            <a href="/" class="px-6 py-2 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all">Kembali</a>
        </div>
    </nav>

    <!-- Header -->
    <section class="relative pt-48 pb-24 bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=2070" class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-4 md:px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-display font-extrabold text-white mb-6">Berita & Artikel</h1>
            <p class="text-slate-300 max-w-2xl mx-auto">Dapatkan informasi terbaru seputar layanan, tips perjalanan, dan berita terkini dari AJL Trans.</p>
        </div>
    </section>

    <!-- News Grid -->
    <section class="py-24">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($posts as $post)
                <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 hover:shadow-2xl transition-all duration-500 group">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $post->title_id }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-6 left-6">
                            <span class="bg-white/90 backdrop-blur px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest text-primary shadow-sm">
                                {{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h4 class="text-xl font-display font-bold text-slate-900 mb-4 line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $post->{'title_' . app()->getLocale()} ?? $post->title_id }}
                        </h4>
                        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-3">
                            {{ strip_tags($post->{'content_' . app()->getLocale()} ?? $post->content_id) }}
                        </p>
                        <a href="{{ route('news.detail', $post->slug) }}" class="inline-flex items-center gap-2 font-bold text-sm text-slate-900 hover:text-primary transition-colors">
                            Baca Selengkapnya
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 py-12 text-center text-slate-500 text-sm">
        <div class="container mx-auto px-4">
            <p>&copy; 2026 AJL Trans. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
