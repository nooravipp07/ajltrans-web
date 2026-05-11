<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title_id }} - AJL Trans</title>
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

    <!-- Content -->
    <article class="pt-48 pb-24">
        <div class="container mx-auto px-4 md:px-6 max-w-4xl">
            <div class="mb-12 text-center">
                <span class="bg-primary/10 text-primary px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest mb-6 inline-block">
                    {{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}
                </span>
                <h1 class="text-4xl md:text-6xl font-display font-extrabold text-slate-900 mb-8 leading-tight">
                    {{ $post->{'title_' . app()->getLocale()} ?? $post->title_id }}
                </h1>
            </div>

            <div class="aspect-[21/9] rounded-[3rem] overflow-hidden mb-16 shadow-2xl">
                <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=2000' }}" class="w-full h-full object-cover">
            </div>

            <div class="prose prose-lg max-w-none text-slate-600 leading-relaxed">
                {!! nl2br(e($post->{'content_' . app()->getLocale()} ?? $post->content_id)) !!}
            </div>

            <div class="mt-24 pt-12 border-t border-slate-200 text-center">
                <h4 class="font-bold text-slate-900 mb-6 text-xl">Bagikan Berita Ini</h4>
                <div class="flex justify-center gap-4">
                    <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-400 hover:text-primary hover:shadow-xl transition-all border border-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </button>
                    <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-400 hover:text-primary hover:shadow-xl transition-all border border-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </article>

    <!-- Simple Footer -->
    <footer class="bg-slate-900 py-12 text-center text-slate-500 text-sm">
        <div class="container mx-auto px-4">
            <p>&copy; 2026 AJL Trans. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
