<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
const props = defineProps({
    project: Array,
});
const generarPdf = () => {
    window.open(route('generar.pdf', props.project.id), '_blank');
};
</script>

<template>
    <AppLayout title="Etapa 2">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Etapa 2
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <p>{{ project.name }}</p>
                <p>{{ project.description }}</p>
                <button @click="generarPdf">Generar PDF</button>
               
            </div>
        </div>
    </AppLayout>
</template>