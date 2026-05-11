<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    templates: Array,
    whatsapp_number: String,
});

const settingsForm = useForm({
    whatsapp_number: props.whatsapp_number || '',
});

const form = useForm({
    templates: props.templates.map(t => ({
        id: t.id,
        kategori: t.kategori,
        template_id: t.template_id,
        template_en: t.template_en
    }))
});

const submit = (id) => {
    const template = form.templates.find(t => t.id === id);
    form.put(route('admin.wa-templates.update', id), {
        data: template,
        preserveScroll: true,
        onSuccess: () => alert('Template WA berhasil diperbarui!')
    });
};

const saveWhatsappNumber = () => {
    settingsForm.put(route('admin.wa-templates.settings.whatsapp-number'), {
        preserveScroll: true,
        onSuccess: () => alert('Nomor WhatsApp berhasil diperbarui!'),
    });
};
</script>

<template>
    <Head title="Template WhatsApp" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-slate-900">
                Manajemen Template WhatsApp
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                        <div class="flex-1">
                            <h3 class="text-xl font-display font-bold text-slate-900">Nomor WhatsApp Tujuan</h3>
                            <p class="text-slate-500 text-sm mt-1">Nomor ini dipakai untuk link WhatsApp yang diterima AJL Trans (format 62xxxx).</p>
                            <div class="mt-4">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">WhatsApp Number</label>
                                <input v-model="settingsForm.whatsapp_number" type="text" class="mt-2 w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm" placeholder="Contoh: 6281234567890">
                            </div>
                        </div>
                        <button @click="saveWhatsappNumber" :disabled="settingsForm.processing" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg disabled:opacity-50">
                            Simpan Nomor WA
                        </button>
                    </div>
                </div>

                <div v-for="(template, index) in form.templates" :key="template.id" class="bg-white overflow-hidden shadow-sm sm:rounded-[2.5rem] p-8 border border-slate-100">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-display font-bold text-slate-900 uppercase tracking-tight">{{ template.kategori.replace('_', ' ') }}</h3>
                            <p class="text-slate-500 text-sm">
                                <template v-if="template.kategori.startsWith('dp_confirmation')">Pesan otomatis untuk konfirmasi pembayaran DP (WhatsApp).</template>
                                <template v-else>Pesan otomatis yang dikirim ke pelanggan setelah booking {{ template.kategori.replace('_', ' ') }}.</template>
                            </p>
                        </div>
                        <button @click="submit(template.id)" :disabled="form.processing" class="px-8 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 disabled:opacity-50">
                            Simpan Template
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Template Bahasa Indonesia</label>
                            <textarea v-model="template.template_id" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm" rows="6"></textarea>
                        </div>
                        <div class="space-y-4">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Template English</label>
                            <textarea v-model="template.template_en" class="w-full rounded-2xl border-slate-100 bg-slate-50 focus:border-primary focus:ring-primary py-4 px-6 font-medium text-sm" rows="6"></textarea>
                        </div>
                        
                        <div class="md:col-span-2 bg-blue-50 p-6 rounded-2xl border border-blue-100">
                            <h4 class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-3">Variabel yang Tersedia</h4>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="v in ['{{BOOKING_CODE}}', '{{NAMA}}', '{{KENDARAAN}}', '{{TIPE_KENDARAAN}}', '{{KOTA}}', '{{KATEGORI}}', '{{SERVICE_TYPE}}', '{{TGL_MULAI}}', '{{DURASI}}', '{{HARGA}}', '{{DP}}', '{{SISA}}', '{{TOTAL}}', '{{TOTAL_FULL}}', '{{BUKTI_DP_URL}}', '{{ALAMAT_JEMPUT}}', '{{ALAMAT_TUJUAN}}', '{{DRIVER_INFO}}']" :key="v" class="px-2 py-1 bg-white rounded text-[10px] font-mono font-bold text-blue-600 border border-blue-100">
                                    {{ v }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
