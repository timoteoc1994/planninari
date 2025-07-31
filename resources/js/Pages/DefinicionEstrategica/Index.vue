<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const props = defineProps({
    proyectoactual: Object
});
</script>

<template>
    <AppLayout title="Etapa 2">
        <template #header>
            <div class="flex justify-between items-center">
                <Link :href="route('projects.stages.show', { id: proyectoactual.id })">{{ proyectoactual.name }}</Link><Link :href="route('etapa1.menu', { id: proyectoactual.id })">/Menu</Link>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Definición Estratégica
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div>
                    <p>Proposito empresarial</p>
                    <textarea name="" id=""></textarea>
                </div>
                <div><p>Vision</p>
                <textarea name="vision" id=""></textarea></div>
                <div><p>Mision</p>
                <textarea name="mision" id=""></textarea></div>
                <div><p>Valores</p>
                <textarea name="valores" id=""></textarea></div>
            </div>
        </div>
    </AppLayout>
</template>