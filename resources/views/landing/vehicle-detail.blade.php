<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $vehicle->nama }} - AJL Trans</title>
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
<body x-data="{ scrolled: false, activeImage: 0 }" @scroll.window="scrolled = window.pageYOffset > 60">
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
    <section class="pt-48 pb-24">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Gallery -->
                <div class="space-y-6">
                    <div class="aspect-video rounded-[3rem] overflow-hidden bg-white shadow-2xl">
                        @if($vehicle->foto_urls && count($vehicle->foto_urls) > 0)
                            <img :src="{{ json_encode($vehicle->foto_urls) }}[activeImage]" class="w-full h-full object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1200" class="w-full h-full object-cover">
                        @endif
                    </div>
                    @if($vehicle->foto_urls && count($vehicle->foto_urls) > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($vehicle->foto_urls as $index => $url)
                        <button @click="activeImage = {{ $index }}" :class="activeImage === {{ $index }} ? 'ring-4 ring-primary' : 'opacity-60 hover:opacity-100'" class="aspect-video rounded-2xl overflow-hidden transition-all">
                            <img src="{{ $url }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="space-y-8">
                    <div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($vehicle->kategori as $cat)
                                <span class="bg-primary/10 text-primary px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest">{{ str_replace('_', ' ', $cat) }}</span>
                            @endforeach
                            <span class="bg-slate-100 text-slate-500 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest">{{ $vehicle->tier }}</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-display font-extrabold text-slate-900 mb-2">{{ $vehicle->nama }}</h1>
                        <p class="text-xl text-slate-400">{{ $vehicle->tipe }}</p>
                    </div>

                    <div class="p-8 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm space-y-6">
                        @php
                            $jakartaMarkup = 200000;
                            $bdgPricings = $vehicle->pricings->where('kota', 'bandung');
                            $jktPricings = $vehicle->pricings->where('kota', 'jakarta');
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Bandung Prices -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    <span class="text-xs font-bold text-slate-900 uppercase tracking-widest">Wilayah Bandung</span>
                                </div>
                                @if($bdgPricings->count() > 0)
                                    @foreach($bdgPricings as $price)
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-slate-500">{{ str_replace('_', ' ', ucfirst($price->paket_tipe)) }}</span>
                                            <span class="font-bold text-slate-900">Rp {{ number_format($price->harga_dasar, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach($jktPricings as $price)
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-slate-500">{{ str_replace('_', ' ', ucfirst($price->paket_tipe)) }}</span>
                                            <span class="font-bold text-slate-900">Rp {{ number_format(max(((int) $price->harga_dasar) - $jakartaMarkup, 0), 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Jakarta Prices -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                                    <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                    <span class="text-xs font-bold text-slate-900 uppercase tracking-widest">Wilayah Jakarta</span>
                                </div>
                                @if($bdgPricings->count() > 0)
                                    @foreach($bdgPricings as $price)
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-slate-500">{{ str_replace('_', ' ', ucfirst($price->paket_tipe)) }}</span>
                                            <span class="font-bold text-slate-900">Rp {{ number_format(((int) $price->harga_dasar) + $jakartaMarkup, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach($jktPricings as $price)
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-slate-500">{{ str_replace('_', ' ', ucfirst($price->paket_tipe)) }}</span>
                                            <span class="font-bold text-slate-900">Rp {{ number_format($price->harga_dasar, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-lg font-bold text-slate-900">Spesifikasi & Layanan:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-3 text-slate-600">
                                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span>Kondisi Prima & Bersih</span>
                            </div>
                            <div class="flex items-center gap-3 text-slate-600">
                                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span>Driver Profesional</span>
                            </div>
                            <div class="flex items-center gap-3 text-slate-600">
                                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span>Layanan 24 Jam</span>
                            </div>
                            <div class="flex items-center gap-3 text-slate-600">
                                <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span>Asuransi Perjalanan</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-200">
                        <a href="{{ url('/booking/' . str_replace('_', '-', $vehicle->kategori[0]) . '?vehicle_id=' . $vehicle->id) }}" class="inline-flex items-center justify-center gap-3 px-12 py-6 bg-primary text-white rounded-[2rem] font-bold text-lg hover:bg-primary-dark transition-all shadow-2xl shadow-primary/30 w-full md:w-auto">
                            Booking Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Simple Footer -->
    <footer class="bg-slate-900 py-12 text-center text-slate-500 text-sm">
        <div class="container mx-auto px-4">
            <p>&copy; 2026 AJL Trans. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
