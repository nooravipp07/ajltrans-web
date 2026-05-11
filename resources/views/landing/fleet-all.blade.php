<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Armada - AJL Trans</title>
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
        <div class="container mx-auto px-4 md:px-6 relative z-10">
            <h1 class="text-4xl md:text-6xl font-display font-extrabold text-white mb-6">Pilihan Armada</h1>
            
            <!-- Filters -->
            <form action="{{ route('fleet.all') }}" method="GET" class="bg-white/10 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px] space-y-2">
                    <label class="text-[10px] font-bold text-white/60 uppercase tracking-widest ml-1">Cari Nama / Tipe</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Contoh: Alphard..." class="w-full bg-white/5 border-white/10 rounded-xl py-3 px-5 text-white focus:border-primary focus:ring-primary placeholder-white/30">
                </div>
                <div class="flex-1 min-w-[200px] space-y-2">
                    <label class="text-[10px] font-bold text-white/60 uppercase tracking-widest ml-1">Kategori Sewa</label>
                    <select name="category" class="w-full bg-white/5 border-white/10 rounded-xl py-3 px-5 text-white focus:border-primary focus:ring-primary appearance-none">
                        <option value="all" {{ $category == 'all' ? 'selected' : '' }} class="bg-slate-900">Semua Kategori</option>
                        <option value="sewa_mobil" {{ $category == 'sewa_mobil' ? 'selected' : '' }} class="bg-slate-900">Sewa Mobil</option>
                        <option value="city_tour" {{ $category == 'city_tour' ? 'selected' : '' }} class="bg-slate-900">City Tour</option>
                        <option value="travel" {{ $category == 'travel' ? 'selected' : '' }} class="bg-slate-900">Travel</option>
                    </select>
                </div>
                <button type="submit" class="px-8 py-3.5 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">Filter</button>
            </form>
        </div>
    </section>

    <!-- Grid -->
    <section class="py-24">
        <div class="container mx-auto px-4 md:px-6">
            @if($vehicles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($vehicles as $car)
                <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 hover:shadow-2xl transition-all duration-500 group">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="{{ $car->foto_urls ? $car->foto_urls[0] : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $car->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-6 left-6 flex flex-wrap gap-2">
                            @foreach($car->kategori as $cat)
                                <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest text-primary shadow-sm">{{ str_replace('_', ' ', $cat) }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-2xl font-display font-bold text-slate-900">{{ $car->nama }}</h4>
                                <p class="text-slate-400 text-sm">{{ $car->tipe }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">Mulai Dari</span>
                                <span class="text-xl font-bold text-primary">Rp {{ number_format($car->starting_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
                            <a href="{{ route('vehicle.detail', $car->id) }}" class="text-sm font-bold text-slate-900 hover:text-primary transition-colors flex items-center gap-2">
                                Detail Armada
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="{{ url('/booking/' . str_replace('_', '-', $car->kategori[0]) . '?vehicle_id=' . $car->id) }}" class="px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">Booking</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-16">
                {{ $vehicles->links() }}
            </div>
            @else
            <div class="text-center py-24 bg-white rounded-[3rem] border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-slate-200 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-2xl font-display font-bold text-slate-900 mb-2">Armada Tidak Ditemukan</h3>
                <p class="text-slate-500">Coba ubah kata kunci atau kategori filter Anda.</p>
                <a href="{{ route('fleet.all') }}" class="mt-8 inline-block font-bold text-primary hover:underline">Reset Filter</a>
            </div>
            @endif
        </div>
    </section>

    <footer class="bg-slate-900 py-12 text-center text-slate-500 text-sm">
        <div class="container mx-auto px-4">
            <p>&copy; 2026 AJL Trans. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
