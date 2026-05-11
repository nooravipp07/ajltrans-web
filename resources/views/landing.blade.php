<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AJL Trans - Sewa Mobil Premium Bandung & Jakarta</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Syne:wght@400..800&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Syne', sans-serif; }
    </style>
</head>
<body class="antialiased bg-background selection:bg-primary selection:text-white" x-data="landingPage()">
    
    <!-- Navbar -->
    <nav 
        :class="scrolled ? 'bg-white/80 backdrop-blur-md border-b border-slate-200 py-4' : 'bg-transparent py-6'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 ease-in-out"
    >
        <div class="container mx-auto px-4 md:px-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <img :src="scrolled ? '{{ $cms['branding']['logo_dark']->value_id ?? '/images/logo-ajl.png' }}' : '{{ $cms['branding']['logo_light']->value_id ?? '/images/logo-ajl.png' }}'" 
                     class="h-10 w-auto transition-all duration-300" alt="AJL Trans Logo">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-10">
                <a href="/#home" :class="scrolled ? 'text-slate-900 hover:text-primary' : 'text-white/80 hover:text-white'" class="text-sm font-bold tracking-widest uppercase hover:text-primary transition-colors">Home</a>
                <a href="/#fleet" :class="scrolled ? 'text-slate-900 hover:text-primary' : 'text-white/80 hover:text-white'" class="text-sm font-bold tracking-widest uppercase hover:text-primary transition-colors">Armada</a>
                <a href="/#news" :class="scrolled ? 'text-slate-900 hover:text-primary' : 'text-white/80 hover:text-white'" class="text-sm font-bold tracking-widest uppercase hover:text-primary transition-colors">Berita</a>
                <a href="/#gallery" :class="scrolled ? 'text-slate-900 hover:text-primary' : 'text-white/80 hover:text-white'" class="text-sm font-bold tracking-widest uppercase hover:text-primary transition-colors">Galeri</a>
                <a href="/#contact" :class="scrolled ? 'text-slate-900 hover:text-primary' : 'text-white/80 hover:text-white'" class="text-sm font-bold tracking-widest uppercase hover:text-primary transition-colors">Kontak</a>
                
                <!-- Language Switcher -->
                <div class="flex items-center gap-2 border-l border-slate-300 pl-8 ml-2">
                    <a href="#" @click.prevent="setLang('id')" :class="currentLocale == 'id' ? 'text-primary font-bold' : (scrolled ? 'text-slate-400' : 'text-white/50')" class="text-sm uppercase transition-colors hover:text-primary">ID</a>
                    <a href="#" @click.prevent="setLang('en')" :class="currentLocale == 'en' ? 'text-primary font-bold' : (scrolled ? 'text-slate-400' : 'text-white/50')" class="text-sm uppercase transition-colors hover:text-primary">EN</a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="#fleet" :class="scrolled ? 'bg-primary text-white hover:bg-primary-dark' : 'bg-white text-primary hover:bg-slate-100'" class="px-6 py-2.5 rounded-full font-bold transition-all duration-300">
                    {{ __('messages.booking') ?? 'Booking Now' }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=2070" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 md:px-6 relative z-20">
            <div class="max-w-2xl fade-up">
                <span class="inline-block px-4 py-1.5 bg-primary/20 backdrop-blur-sm border border-primary/30 rounded-full text-primary font-bold text-sm mb-6 uppercase tracking-wider">
                    Premium Rental Mobil
                </span>
                <h1 class="text-5xl md:text-7xl font-display font-extrabold text-white leading-tight mb-6">
                    {{ $cms['hero']['title']->{'value_' . app()->getLocale()} ?? 'Sewa Mobil Premium di Bandung & Jakarta' }}
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-10 leading-relaxed">
                    {{ $cms['hero']['subtitle']->{'value_' . app()->getLocale()} ?? 'Nikmati perjalanan mewah dengan armada terbaru dan layanan profesional terbaik.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#fleet" class="px-8 py-4 bg-primary text-white rounded-xl font-bold text-lg hover:bg-primary-dark transition-all duration-300 text-center shadow-lg shadow-primary/25">
                        {{ __('messages.view_fleet') ?? 'Lihat Armada' }}
                    </a>
                    <a href="#services" class="px-8 py-4 bg-white/10 backdrop-blur-md text-white border border-white/20 rounded-xl font-bold text-lg hover:bg-white/20 transition-all duration-300 text-center">
                        {{ __('messages.our_services') ?? 'Layanan Kami' }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Booking Bar -->
    <div class="container mx-auto px-4 md:px-6 -mt-16 relative z-30">
        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 p-6 md:p-8">
            <div class="text-center mb-8">
                <h3 class="text-xl font-display font-bold text-slate-900">Mulai Perjalanan Anda</h3>
                <p class="text-slate-500 text-sm">Pilih kategori layanan terlebih dahulu</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <button @click="selectCategory('sewa_mobil')" :class="quickBooking.category === 'sewa_mobil' ? 'bg-primary text-white border-primary' : 'bg-slate-50 text-slate-600 border-slate-100 hover:bg-slate-100'" class="p-6 rounded-2xl border-2 transition-all flex flex-col items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold">Sewa Mobil</span>
                </button>
                <button @click="selectCategory('city_tour')" :class="quickBooking.category === 'city_tour' ? 'bg-primary text-white border-primary' : 'bg-slate-50 text-slate-600 border-slate-100 hover:bg-slate-100'" class="p-6 rounded-2xl border-2 transition-all flex flex-col items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5V17m-4.5-6h1.5a2 2 0 012 2v1a2 2 0 002 2h.1" />
                    </svg>
                    <span class="font-bold">City Tour</span>
                </button>
                <button @click="selectCategory('travel')" :class="quickBooking.category === 'travel' ? 'bg-primary text-white border-primary' : 'bg-slate-50 text-slate-600 border-slate-100 hover:bg-slate-100'" class="p-6 rounded-2xl border-2 transition-all flex flex-col items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="font-bold">Travel</span>
                </button>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-6 border-t border-slate-100 pt-8" x-show="quickBooking.category">
                <div class="flex-1 w-full space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Kota Penjemputan</label>
                    <select x-model="quickBooking.city" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-slate-900 font-bold focus:ring-2 focus:ring-primary appearance-none">
                        <option value="bandung">Bandung</option>
                        <option value="jakarta">Jakarta</option>
                    </select>
                </div>
                <div class="flex-1 w-full space-y-2" x-show="availableServiceTypes().length > 0">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Tipe Layanan</label>
                    <select x-model="quickBooking.service_type" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-slate-900 font-bold focus:ring-2 focus:ring-primary appearance-none">
                        <template x-for="t in availableServiceTypes()" :key="t.slug">
                            <option :value="t.slug" x-text="t.label"></option>
                        </template>
                    </select>
                </div>
                <button @click="startBooking()" class="w-full md:w-auto px-12 py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-dark transition-all shadow-lg shadow-primary/25 self-end">
                    Lanjutkan ke Form <span class="ml-2">→</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Fleet Section -->
    <section id="fleet" class="py-24 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6 fade-up">
                <div>
                    <span class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">Pilihan Armada</span>
                    <h2 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900">Kendaraan Terbaik Untuk Anda</h2>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button @click="fleetFilter = 'all'" :class="fleetFilter === 'all' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-6 py-2.5 rounded-full font-bold transition-all">Semua</button>
                    <button @click="fleetFilter = 'sewa_mobil'" :class="fleetFilter === 'sewa_mobil' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-6 py-2.5 rounded-full font-bold transition-all">Sewa Mobil</button>
                    <button @click="fleetFilter = 'city_tour'" :class="fleetFilter === 'city_tour' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-6 py-2.5 rounded-full font-bold transition-all">City Tour</button>
                    <button @click="fleetFilter = 'travel'" :class="fleetFilter === 'travel' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-6 py-2.5 rounded-full font-bold transition-all">Travel</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <template x-for="car in filteredFleet().slice(0, 6)" :key="car.id">
                    <div class="bg-slate-50 rounded-[2.5rem] overflow-hidden border border-slate-100 hover:shadow-2xl transition-all duration-500 group fade-up">
                        <div class="aspect-[16/10] overflow-hidden relative">
                            <img :src="car.foto_urls && car.foto_urls.length > 0 ? car.foto_urls[0] : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800'" :alt="car.nama" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute top-6 left-6 flex flex-wrap gap-2">
                                <template x-for="cat in car.kategori" :key="cat">
                                    <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest text-primary shadow-sm" x-text="cat.replace('_', ' ')"></span>
                                </template>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-2xl font-display font-bold text-slate-900" x-text="car.nama"></h4>
                                    <p class="text-slate-400 text-sm" x-text="car.tipe"></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">Mulai Dari</span>
                                    <span class="text-xl font-bold text-primary">Rp <span x-text="getSelectedPrice(car)"></span></span>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
                                <a :href="'/vehicle/' + car.id" class="text-sm font-bold text-slate-900 hover:text-primary transition-colors flex items-center gap-2">
                                    Detail Armada
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                <button @click="startBooking(car.id)" class="px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">Booking</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="text-center fade-up">
                <a :href="'/fleet?category=' + fleetFilter" class="inline-flex items-center gap-3 px-10 py-5 bg-slate-900 text-white rounded-[2rem] font-bold hover:bg-primary transition-all shadow-xl shadow-slate-900/20 group">
                    Lihat Semua Armada
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-slate-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="fade-up">
                    <span class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">Layanan Kami</span>
                    <h2 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-8 leading-tight">Solusi Transportasi Lengkap Untuk Kebutuhan Anda</h2>
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-3xl flex gap-6 items-start hover:shadow-xl transition-all">
                            <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex-shrink-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-display font-bold text-slate-900 mb-2">Sewa Mobil (12/24 Jam)</h4>
                                <p class="text-slate-500 leading-relaxed">Pilihan lepas kunci atau dengan pengemudi profesional untuk kenyamanan maksimal perjalanan Anda.</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-3xl flex gap-6 items-start hover:shadow-xl transition-all">
                            <div class="w-14 h-14 bg-primary/10 text-primary rounded-2xl flex-shrink-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5V17m-4.5-6h1.5a2 2 0 012 2v1a2 2 0 002 2h.1" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-display font-bold text-slate-900 mb-2">City Tour & Wisata</h4>
                                <p class="text-slate-500 leading-relaxed">Paket wisata keliling Bandung atau Jakarta dengan rute fleksibel dan armada yang selalu bersih.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative fade-up">
                    <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=2070" class="rounded-[3rem] shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="py-24 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6 fade-up">
                <div>
                    <span class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">Berita & Artikel</span>
                    <h2 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900">Informasi Terbaru AJL Trans</h2>
                </div>
                <a href="{{ route('news.all') }}" class="group flex items-center gap-3 font-bold text-primary hover:text-primary-dark transition-all">
                    Lihat Semua Berita
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($posts as $post)
                <div class="bg-slate-50 rounded-[2.5rem] overflow-hidden border border-slate-100 hover:shadow-2xl transition-all duration-500 group fade-up">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $post->title_id }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="p-8">
                        <h4 class="text-xl font-display font-bold text-slate-900 mb-4 line-clamp-2 group-hover:text-primary transition-colors">
                            {{ $post->{'title_' . app()->getLocale()} ?? $post->title_id }}
                        </h4>
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
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-24 bg-slate-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-16 fade-up">
                <span class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">Galeri Kami</span>
                <h2 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-8">Momen & Armada AJL Trans</h2>
                <a href="{{ route('gallery.all') }}" class="inline-flex items-center gap-2 font-bold text-primary hover:underline">
                    Lihat Semua Galeri
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($galleries as $item)
                <div class="relative group aspect-square rounded-[2rem] overflow-hidden bg-white shadow-sm hover:shadow-xl transition-all fade-up">
                    @if($item->type === 'photo')
                        <img src="{{ $item->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center p-6 text-center">
                        <h4 class="text-white font-bold mb-2">{{ $item->{'title_' . app()->getLocale()} ?? $item->title_id }}</h4>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-16 fade-up">
                <span class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">Testimoni</span>
                <h2 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900">Apa Kata Pelanggan Kami</h2>
            </div>

            <div class="flex flex-wrap justify-center gap-8">
                @foreach($testimonials as $item)
                <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.33%-1.5rem)] hover:shadow-xl transition-all duration-500 fade-up">
                    <div class="flex gap-1 mb-6">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $i <= $item->rating ? 'text-yellow-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-slate-600 italic mb-8 leading-relaxed">
                        {{ app()->getLocale() === 'en' ? ($item->ulasan_en ?? $item->ulasan_id) : $item->ulasan_id }}
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-lg">
                            {{ substr($item->nama, 0, 1) }}
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-900">{{ $item->nama }}</h5>
                            <span class="text-slate-400 text-sm">{{ $item->kota }} • {{ $item->kendaraan_disewa }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Testimonial Submission Form -->
            <div class="mt-24 max-w-4xl mx-auto bg-slate-50 rounded-[3rem] p-8 md:p-12 border border-slate-100 fade-up">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-display font-bold text-slate-900 mb-4">Bagikan Pengalaman Anda</h3>
                    <p class="text-slate-500">Kepuasan Anda adalah prioritas kami. Berikan ulasan untuk layanan AJL Trans.</p>
                </div>

                <form @submit.prevent="submitTestimonial" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" x-model="testimonialForm.nama" required class="w-full bg-white border-none rounded-2xl py-4 px-6 text-slate-900 font-bold focus:ring-2 focus:ring-primary shadow-sm" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Asal Kota</label>
                            <input type="text" x-model="testimonialForm.kota" required class="w-full bg-white border-none rounded-2xl py-4 px-6 text-slate-900 font-bold focus:ring-2 focus:ring-primary shadow-sm" placeholder="Contoh: Jakarta">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Kendaraan yang Disewa</label>
                            <input type="text" x-model="testimonialForm.kendaraan_disewa" required class="w-full bg-white border-none rounded-2xl py-4 px-6 text-slate-900 font-bold focus:ring-2 focus:ring-primary shadow-sm" placeholder="Contoh: Toyota Hiace">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Rating</label>
                            <select x-model="testimonialForm.rating" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-slate-900 font-bold focus:ring-2 focus:ring-primary shadow-sm appearance-none">
                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                <option value="2">⭐⭐ (Kurang)</option>
                                <option value="1">⭐ (Buruk)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Ulasan Anda</label>
                        <textarea x-model="testimonialForm.ulasan_id" required rows="4" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-slate-900 font-bold focus:ring-2 focus:ring-primary shadow-sm" placeholder="Tuliskan pengalaman perjalanan Anda bersama AJL Trans..."></textarea>
                    </div>

                    <div class="flex flex-col items-center gap-4">
                        <button type="submit" :disabled="testimonialForm.loading" class="w-full md:w-auto px-12 py-4 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-dark transition-all shadow-lg shadow-primary/25 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!testimonialForm.loading">Kirim Testimoni</span>
                            <span x-show="testimonialForm.loading">Mengirim...</span>
                        </button>

                        <div x-show="testimonialForm.successMessage" x-transition class="w-full p-4 bg-green-50 text-green-600 rounded-2xl text-center font-bold text-sm border border-green-100">
                            <span x-text="testimonialForm.successMessage"></span>
                        </div>
                        <div x-show="testimonialForm.errorMessage" x-transition class="w-full p-4 bg-red-50 text-red-600 rounded-2xl text-center font-bold text-sm border border-red-100">
                            <span x-text="testimonialForm.errorMessage"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-slate-900 pt-24 pb-12 text-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
                <div class="space-y-8">
                    <div class="flex items-center gap-2">
                        <img src="{{ $cms['branding']['logo_light']->value_id ?? '/images/logo-light.png' }}" class="h-12 w-auto" alt="AJL Trans Logo">
                    </div>
                    <p class="text-slate-400 leading-relaxed">Penyedia layanan transportasi premium terbaik di Bandung dan Jakarta. Mengutamakan kenyamanan dan keamanan perjalanan Anda.</p>
                    <div class="flex gap-4">
                        @if(isset($cms['social']['instagram']))
                        <a href="{{ $cms['social']['instagram']->value_id }}" target="_blank" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all group">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        @endif

                        @if(isset($cms['social']['facebook']))
                        <a href="{{ $cms['social']['facebook']->value_id }}" target="_blank" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all group">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-8.783h-2.954v-3.429h2.954v-2.527c0-2.925 1.787-4.516 4.396-4.516 1.25 0 2.324.093 2.637.135v3.057h-1.808c-1.419 0-1.694.675-1.694 1.664v2.187h3.384l-.441 3.429h-2.943v8.783h6.135c.731 0 1.325-.593 1.325-1.324v-21.351c0-.732-.593-1.325-1.325-1.325z"/></svg>
                        </a>
                        @endif

                        @if(isset($cms['social']['tiktok']))
                        <a href="{{ $cms['social']['tiktok']->value_id }}" target="_blank" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all group">
                            <span class="sr-only">TikTok</span>
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.03 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.9-.32-1.98-.23-2.81.33-.85.51-1.44 1.43-1.58 2.41-.14.99.13 2.02.74 2.81.59.7 1.48 1.1 2.39 1.1 1.2.02 2.33-.46 3.07-1.41.64-.73.94-1.66.95-2.61.02-3.45.01-6.9.01-10.35z"/></svg>
                        </a>
                        @endif

                        @if(isset($cms['social']['youtube']))
                        <a href="{{ $cms['social']['youtube']->value_id }}" target="_blank" class="w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center hover:bg-primary transition-all group">
                            <span class="sr-only">YouTube</span>
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-xl font-display font-bold mb-8">Layanan</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="{{ route('booking.sewa') }}" class="hover:text-primary transition-colors">Sewa Mobil</a></li>
                        <li><a href="{{ route('booking.tour') }}" class="hover:text-primary transition-colors">City Tour</a></li>
                        <li><a href="{{ route('booking.travel') }}" class="hover:text-primary transition-colors">Travel</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-display font-bold mb-8">Informasi</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#about" class="hover:text-primary transition-colors">Tentang Kami</a></li>
                        <li><a href="#fleet" class="hover:text-primary transition-colors">Pilihan Armada</a></li>
                        <li><a href="#news" class="hover:text-primary transition-colors">Berita Terbaru</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-display font-bold mb-8">Kontak</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li class="flex items-center gap-3">
                            <span>Bandung & Jakarta, Indonesia</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span>+62 812 3456 7890</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-12 text-center text-slate-500 text-sm">
                <p>&copy; 2026 AJL Trans. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ config('ajl.whatsapp_number') }}" target="_blank" class="fixed bottom-8 right-8 z-[60] group">
        <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-25 group-hover:opacity-0 transition-opacity"></div>
        <div class="relative w-16 h-16 bg-green-500 rounded-full flex items-center justify-center text-white shadow-2xl hover:scale-110 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.224-3.82l.454.27c1.464.87 3.162 1.33 4.904 1.33 5.305 0 9.623-4.317 9.626-9.624.002-2.571-1.002-4.986-2.826-6.81s-4.239-2.827-6.81-2.828c-5.306 0-9.623 4.317-9.626 9.625-.001 1.848.528 3.655 1.529 5.21l.294.463-1.007 3.675 3.766-.988zm11.233-7.252c-.31-.154-1.833-.904-2.116-1.007-.283-.103-.49-.154-.696.154-.206.31-.799 1.007-.98 1.213-.181.206-.361.232-.67.077-.31-.154-1.307-.482-2.49-1.537-.92-.821-1.542-1.835-1.722-2.144-.181-.309-.019-.477.135-.63.14-.139.31-.361.465-.541.154-.181.206-.309.31-.515.103-.206.052-.386-.026-.541-.077-.154-.696-1.678-.954-2.3-.251-.605-.506-.523-.696-.533-.181-.008-.387-.01-.593-.01-.206 0-.541.077-.825.386-.284.309-1.083 1.057-1.083 2.578 0 1.52 1.108 2.99 1.263 3.2 1.185 1.605 2.565 2.872 4.414 3.672 1.849.8 1.849.533 2.185.503.336-.03 1.083-.443 1.237-.876.154-.433.154-.8.103-.876-.051-.077-.206-.129-.516-.283z"/>
            </svg>
        </div>
    </a>

    <script>
        function landingPage() {
            return {
                scrolled: false,
                currentLocale: '{{ app()->getLocale() }}',
                fleet: [],
                fleetFilter: 'all',
                serviceTypes: @js($serviceTypes ?? []),
                quickBooking: {
                    category: '',
                    city: 'bandung',
                    service_type: 'lepas_kunci'
                },

                async init() {
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.pageYOffset > 60;
                    });
                    
                    await this.fetchFleet();
                    this.initFadeUp();
                },

                availableServiceTypes() {
                    const category = this.quickBooking.category;
                    if (!category) return [];
                    return (this.serviceTypes || []).filter(t => {
                        const cats = Array.isArray(t.categories) ? t.categories : [];
                        if (cats.length === 0) return true;
                        return cats.includes(category);
                    });
                },

                ensureServiceType() {
                    const list = this.availableServiceTypes();
                    if (list.length === 0) {
                        this.quickBooking.service_type = '';
                        return;
                    }
                    const slugs = list.map(t => t.slug);
                    if (!slugs.includes(this.quickBooking.service_type)) {
                        this.quickBooking.service_type = slugs[0];
                    }
                },

                selectCategory(cat) {
                    this.quickBooking.category = cat;
                    this.ensureServiceType();
                },

                async fetchFleet() {
                    try {
                        const response = await fetch('/api/v1/vehicles');
                        this.fleet = await response.json();
                    } catch (error) {
                        console.error('Error fetching fleet:', error);
                    }
                },

                filteredFleet() {
                    if (this.fleetFilter === 'all') return this.fleet;
                    return this.fleet.filter(car => {
                        return Array.isArray(car.kategori) && car.kategori.includes(this.fleetFilter);
                    });
                },

                getSelectedPrice(car) {
                    if (!car.pricings || car.pricings.length === 0) return '-';
                    
                    const JAKARTA_MARKUP = 200000;
                    let city = this.quickBooking.city || 'bandung';
                    let type = this.quickBooking.service_type || (this.availableServiceTypes()[0]?.slug || 'lepas_kunci');
                    let category = this.quickBooking.category || 'sewa_mobil';
                    let unitWanted = category === 'sewa_mobil' ? 'per_12_jam' : 'per_hari';
                    if (String(type).toLowerCase().includes('luar_kota')) {
                        unitWanted = 'per_hari';
                    }

                    const bdg = car.pricings.find(p => p.kota === 'bandung' && p.paket_tipe === type && (p.unit || 'per_hari') === unitWanted);
                    const jkt = car.pricings.find(p => p.kota === 'jakarta' && p.paket_tipe === type && (p.unit || 'per_hari') === unitWanted);

                    let base = 0;
                    if (bdg && bdg.harga_dasar > 0) {
                        base = bdg.harga_dasar;
                    } else if (jkt && jkt.harga_dasar > 0) {
                        base = Math.max(jkt.harga_dasar - JAKARTA_MARKUP, 0);
                    }

                    if (base > 0) {
                        const effective = city === 'jakarta' ? base + JAKARTA_MARKUP : base;
                        return new Intl.NumberFormat('id-ID').format(effective);
                    }

                    // Fallback to min price if specific type not found
                    const bdgPrices = car.pricings.filter(p => p.kota === 'bandung' && p.harga_dasar > 0 && (p.unit || 'per_hari') === unitWanted);
                    const jktPrices = car.pricings.filter(p => p.kota === 'jakarta' && p.harga_dasar > 0 && (p.unit || 'per_hari') === unitWanted);
                    let minBase = 0;

                    if (bdgPrices.length > 0) {
                        minBase = Math.min(...bdgPrices.map(p => p.harga_dasar));
                    } else if (jktPrices.length > 0) {
                        minBase = Math.max(Math.min(...jktPrices.map(p => p.harga_dasar)) - JAKARTA_MARKUP, 0);
                    } else {
                        const allBdg = car.pricings.filter(p => p.kota === 'bandung' && p.harga_dasar > 0);
                        const allJkt = car.pricings.filter(p => p.kota === 'jakarta' && p.harga_dasar > 0);
                        if (allBdg.length > 0) {
                            minBase = Math.min(...allBdg.map(p => p.harga_dasar));
                        } else if (allJkt.length > 0) {
                            minBase = Math.max(Math.min(...allJkt.map(p => p.harga_dasar)) - JAKARTA_MARKUP, 0);
                        }
                    }

                    if (!minBase) return '-';
                    const minEffective = city === 'jakarta' ? minBase + JAKARTA_MARKUP : minBase;
                    return new Intl.NumberFormat('id-ID').format(minEffective);
                },

                getStartingPrice(car) {
                    if (!car.pricings || car.pricings.length === 0) return '-';
                    const unitWanted = (this.quickBooking.category || 'sewa_mobil') === 'sewa_mobil' ? 'per_12_jam' : 'per_hari';
                    let bdgPrices = car.pricings.filter(p => p.kota === 'bandung' && p.harga_dasar > 0 && (p.unit || 'per_hari') === unitWanted);
                    if (bdgPrices.length === 0) {
                        bdgPrices = car.pricings.filter(p => p.kota === 'bandung' && p.harga_dasar > 0);
                    }
                    if (bdgPrices.length === 0) return '-';
                    const min = Math.min(...bdgPrices.map(p => p.harga_dasar));
                    return new Intl.NumberFormat('id-ID').format(min);
                },

                startBooking(vehicleId = null) {
                    let url = '';
                    let category = this.quickBooking.category || 'sewa_mobil'; // Default to sewa_mobil
                    let city = this.quickBooking.city || 'bandung';
                    this.ensureServiceType();
                    let type = this.quickBooking.service_type || 'lepas_kunci';

                    if (category === 'sewa_mobil') {
                        url = `/booking/sewa-mobil?city=${city}&type=${type}`;
                    } else if (category === 'city_tour') {
                        url = `/booking/city-tour?city=${city}&type=${type}`;
                    } else if (category === 'travel') {
                        url = `/booking/travel?city=${city}&type=${type}`;
                    }
                    
                    if (url && vehicleId) {
                        url += (url.includes('?') ? '&' : '?') + `vehicle_id=${vehicleId}`;
                    }
                    
                    if (url) window.location.href = url;
                },

                testimonialForm: {
                    nama: '',
                    kota: '',
                    kendaraan_disewa: '',
                    ulasan_id: '',
                    rating: 5,
                    loading: false,
                    successMessage: '',
                    errorMessage: ''
                },

                async submitTestimonial() {
                    this.testimonialForm.loading = true;
                    this.testimonialForm.successMessage = '';
                    this.testimonialForm.errorMessage = '';
                    
                    try {
                        const response = await fetch('/api/v1/testimonials', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.testimonialForm)
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok) {
                            this.testimonialForm.successMessage = data.message;
                            this.testimonialForm.nama = '';
                            this.testimonialForm.kota = '';
                            this.testimonialForm.kendaraan_disewa = '';
                            this.testimonialForm.ulasan_id = '';
                            this.testimonialForm.rating = 5;
                        } else {
                            this.testimonialForm.errorMessage = data.message || 'Gagal mengirim testimoni.';
                        }
                    } catch (error) {
                        this.testimonialForm.errorMessage = 'Terjadi kesalahan sistem.';
                    } finally {
                        this.testimonialForm.loading = false;
                    }
                },

                setLang(lang) {
                    fetch('{{ route("set.lang") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ lang: lang })
                    }).then(() => window.location.reload());
                },

                initFadeUp() {
                    const observerOptions = { threshold: 0.1 };
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('opacity-100', 'translate-y-0');
                                entry.target.classList.remove('opacity-0', 'translate-y-10');
                            }
                        });
                    }, observerOptions);

                    document.querySelectorAll('.fade-up').forEach(el => {
                        el.classList.add('transition-all', 'duration-1000', 'opacity-0', 'translate-y-10');
                        observer.observe(el);
                    });
                }
            }
        }
    </script>
</body>
</html>
