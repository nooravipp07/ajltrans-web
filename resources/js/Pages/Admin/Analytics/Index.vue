<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
    Filler
} from 'chart.js';
import { Bar, Pie, Line, Doughnut } from 'vue-chartjs';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    ArcElement,
    Filler
);

const analyticsData = ref(null);
const loading = ref(true);
const selectedYear = ref(new Date().getFullYear());

const fetchAnalytics = async () => {
    loading.value = true;
    try {
        const response = await axios.get(route('admin.analytics.data'), {
            params: { year: selectedYear.value }
        });
        analyticsData.value = response.data;
    } catch (error) {
        console.error('Error fetching analytics:', error);
    } finally {
        loading.value = false;
    }
};

// Chart Data Configurations
const cityBookingChartData = computed(() => {
    if (!analyticsData.value) return null;
    const data = analyticsData.value.marketing.booking_per_kota;
    return {
        labels: data.map(i => i.kota.toUpperCase()),
        datasets: [{
            label: 'Total Pesanan',
            data: data.map(i => i.total),
            backgroundColor: ['#1B4FBF', '#6366F1', '#94A3B8'],
            borderRadius: 12,
            barThickness: 40,
        }]
    };
});

const revenueTrendChartData = computed(() => {
    if (!analyticsData.value) return null;
    const data = analyticsData.value.keuangan.tren_bulanan;
    return {
        labels: data.map(i => i.bulan),
        datasets: [{
            label: 'Pendapatan (IDR)',
            data: data.map(i => i.revenue),
            borderColor: '#1B4FBF',
            backgroundColor: (context) => {
                const chart = context.chart;
                const {ctx, chartArea} = chart;
                if (!chartArea) return null;
                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                gradient.addColorStop(0, 'rgba(27, 79, 191, 0)');
                gradient.addColorStop(1, 'rgba(27, 79, 191, 0.2)');
                return gradient;
            },
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#1B4FBF',
            pointBorderWidth: 2,
            pointHoverRadius: 8,
            pointHoverBackgroundColor: '#1B4FBF',
        }]
    };
});

const categoryChartData = computed(() => {
    if (!analyticsData.value) return null;
    const data = analyticsData.value.marketing.booking_per_kategori;
    return {
        labels: data.map(i => i.kategori.replace('_', ' ').toUpperCase()),
        datasets: [{
            data: data.map(i => i.total),
            backgroundColor: ['#1B4FBF', '#10B981', '#F59E0B'],
            hoverOffset: 15,
            borderWidth: 0,
        }]
    };
});

const statusChartData = computed(() => {
    if (!analyticsData.value) return null;
    const data = analyticsData.value.marketing.booking_status_dist;
    const statusLabels = {
        'menunggu_konfirmasi': 'Menunggu',
        'dikonfirmasi': 'Dikonfirmasi',
        'sedang_berjalan': 'Aktif',
        'selesai': 'Selesai',
        'dibatalkan': 'Batal'
    };
    const statusColors = {
        'menunggu_konfirmasi': '#F59E0B',
        'dikonfirmasi': '#3B82F6',
        'sedang_berjalan': '#1B4FBF',
        'selesai': '#10B981',
        'dibatalkan': '#EF4444'
    };
    
    return {
        labels: data.map(i => statusLabels[i.status] || i.status),
        datasets: [{
            data: data.map(i => i.total),
            backgroundColor: data.map(i => statusColors[i.status] || '#CBD5E1'),
            cutout: '70%',
            borderRadius: 5,
        }]
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#1e293b',
            padding: 12,
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 13 },
            cornerRadius: 12,
            displayColors: false
        }
    },
    scales: {
        y: { 
            beginAtZero: true, 
            grid: { color: '#f1f5f9', drawBorder: false },
            ticks: { font: { size: 11, weight: 'bold' }, color: '#94a3b8' }
        },
        x: { 
            grid: { display: false },
            ticks: { font: { size: 11, weight: 'bold' }, color: '#94a3b8' }
        }
    }
};

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { size: 11, weight: 'bold' } } }
    }
};

onMounted(() => {
    fetchAnalytics();
});
</script>

<template>
    <Head title="Analitik Bisnis" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-display font-extrabold text-slate-900">Dashboard Analitik</h2>
                    <p class="text-slate-500 text-sm font-medium">Visualisasi performa AJL Trans Periode {{ selectedYear }}.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white p-1 rounded-2xl shadow-sm border border-slate-100 flex gap-1">
                        <button v-for="y in [2024, 2025, 2026]" :key="y" 
                                @click="selectedYear = y; fetchAnalytics()"
                                :class="selectedYear === y ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400 hover:bg-slate-50'"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                            {{ y }}
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="loading" class="flex flex-col justify-center items-center h-[60vh] gap-6">
                    <div class="relative w-20 h-20">
                        <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-primary border-t-transparent animate-spin"></div>
                    </div>
                    <p class="text-slate-400 font-display font-bold animate-pulse tracking-widest uppercase text-xs">Menganalisa Data Bisnis...</p>
                </div>

                <div v-else class="space-y-8 animate-in fade-in duration-700">
                    <!-- Stat Highlights -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">New Clients</span>
                            <div class="flex items-end gap-2">
                                <span class="text-4xl font-display font-extrabold text-slate-900 leading-none">{{ analyticsData.crm.customer_baru }}</span>
                                <span class="text-xs font-bold text-green-500 pb-1">+12%</span>
                            </div>
                            <p class="mt-4 text-xs text-slate-400 font-medium">Pelanggan baru bulan ini</p>
                        </div>

                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                             <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Retention Rate</span>
                            <div class="flex items-end gap-2">
                                <span class="text-4xl font-display font-extrabold text-slate-900 leading-none">
                                    {{ analyticsData.crm.customer_baru > 0 ? Math.round((analyticsData.crm.repeat_order / analyticsData.crm.customer_baru) * 100) : 0 }}%
                                </span>
                                <span class="text-xs font-bold text-slate-400 pb-1">Repeat</span>
                            </div>
                             <p class="mt-4 text-xs text-slate-400 font-medium">{{ analyticsData.crm.repeat_order }} Loyal customers</p>
                        </div>

                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 lg:col-span-2 relative overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full group-hover:scale-125 transition-transform duration-700"></div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Total Revenue {{ selectedYear }}</span>
                            <div class="flex flex-col">
                                <span class="text-4xl font-display font-extrabold text-primary leading-none">Rp {{ new Intl.NumberFormat('id-ID').format(analyticsData.keuangan.revenue_per_kota.reduce((a, b) => a + b.revenue, 0)) }}</span>
                                <div class="mt-6 flex flex-wrap gap-6">
                                    <div v-for="item in analyticsData.keuangan.revenue_per_kota" :key="item.kota" class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full" :class="item.kota === 'bandung' ? 'bg-primary' : 'bg-indigo-400'"></div>
                                        <span class="text-xs font-bold text-slate-600 capitalize">{{ item.kota }}: </span>
                                        <span class="text-xs font-extrabold text-slate-900">Rp {{ new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(item.revenue) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row 1 -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Revenue Trend -->
                        <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 group">
                            <div class="flex justify-between items-center mb-10">
                                <div>
                                    <h3 class="text-xl font-display font-extrabold text-slate-900">Tren Pendapatan</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Monthly Revenue Growth</p>
                                </div>
                                <div class="p-3 bg-slate-50 rounded-2xl group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
                                </div>
                            </div>
                            <div class="h-80">
                                <Line :data="revenueTrendChartData" :options="chartOptions" />
                            </div>
                        </div>

                        <!-- Booking Status Distribution -->
                        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                            <div class="text-center mb-8">
                                <h3 class="text-xl font-display font-extrabold text-slate-900">Status Pesanan</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Current Order Health</p>
                            </div>
                            <div class="h-64 relative">
                                <Doughnut :data="statusChartData" :options="doughnutOptions" />
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-12">
                                    <span class="text-3xl font-display font-extrabold text-slate-900">{{ analyticsData.marketing.booking_status_dist.reduce((a, b) => a + b.total, 0) }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row 2 -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                         <!-- City & Category -->
                         <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                            <div class="flex justify-between items-center mb-10">
                                <div>
                                    <h3 class="text-xl font-display font-extrabold text-slate-900">Sebaran Wilayah</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Bookings by Location</p>
                                </div>
                            </div>
                            <div class="h-64">
                                <Bar :data="cityBookingChartData" :options="chartOptions" />
                            </div>
                        </div>

                        <!-- Top Vehicles -->
                        <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                             <div class="flex justify-between items-center mb-8">
                                <div>
                                    <h3 class="text-xl font-display font-extrabold text-slate-900">Armada Terlaris</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Most Rented Vehicles</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div v-for="(item, index) in analyticsData.marketing.kendaraan_terpopuler" :key="item.vehicle_id" class="flex items-center justify-between p-5 bg-slate-50/50 border border-transparent hover:border-primary/20 hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 rounded-3xl group transition-all duration-300">
                                    <div class="flex items-center gap-5">
                                        <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center font-display font-extrabold text-primary group-hover:bg-primary group-hover:text-white transition-colors">{{ index + 1 }}</div>
                                        <div>
                                            <p class="font-bold text-slate-900 group-hover:text-primary transition-colors">{{ item.vehicle.nama }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Premium Selection</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-2xl font-display font-extrabold text-slate-900 group-hover:text-primary transition-colors">{{ item.total }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bookings</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display { font-family: 'Syne', sans-serif; }
</style>
