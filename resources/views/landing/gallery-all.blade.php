<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Foto & Video - AJL Trans</title>
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
            <h1 class="text-4xl md:text-6xl font-display font-extrabold text-white mb-6">Galeri Dokumentasi</h1>
            <p class="text-slate-300 max-w-2xl mx-auto">Kumpulan foto dan video armada serta momen perjalanan berharga bersama AJL Trans.</p>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="py-24">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($galleries as $item)
                <div class="relative group aspect-square rounded-[2rem] overflow-hidden bg-white border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                    @if($item->type === 'photo')
                        <img src="{{ $item->url }}" alt="{{ $item->title_id }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-900 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-40 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center p-6 text-center">
                        <h4 class="text-white font-bold mb-2">{{ $item->{'title_' . app()->getLocale()} ?? $item->title_id }}</h4>
                        <span class="text-white/70 text-[10px] font-bold uppercase tracking-widest bg-white/20 px-3 py-1 rounded-full">{{ $item->type }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-16">
                {{ $galleries->links() }}
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
