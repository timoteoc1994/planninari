<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    project: Object,
});

const stages = ref([]);
</script>

<template>
    <AppLayout :title="`Etapas de ${project.name}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Etapas de {{ project.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto bg-white p-8 rounded shadow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a :href="route('etapa1', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 1</h3>
                    </a>
                    <a :href="route('etapa2', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 2</h3>
                    </a>
                    <a :href="route('etapa3', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 3</h3>
                    </a>
                    <a :href="route('etapa4', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 4</h3>
                    </a>
                    <a :href="route('etapa5', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 5</h3>
                    </a>
                    <a :href="route('etapa6', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 6</h3>
                    </a>
                    <a :href="route('etapa7.index', project.id)" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 7</h3>
                    </a>
                    <a :href="route('etapa8', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 8</h3>
                    </a>
                    <a :href="route('etapa9', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 9</h3>
                    </a>
                    <a :href="route('etapa10', { id: project.id })" class="block border rounded p-4">
                        <h3 class="font-bold mb-2">Etapa 10</h3>
                    </a>

                </div>
            </div>
        </div>
    </AppLayout>
</template>