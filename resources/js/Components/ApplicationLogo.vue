<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<template>
    <div class="flex items-center gap-2">
        <img :src="page.props.logo" class="h-9 w-auto" alt="Logo">
    </div>
</template>
