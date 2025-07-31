<style scoped>
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.group:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.group:hover button {
    transform: scale(1.05);
}
button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}
.bg-white {
    animation: fadeIn 0.6s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}
@media (max-width: 768px) {
    .grid.grid-cols-1.md\:grid-cols-2 {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    .py-8 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
}
</style>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, Link } from '@inertiajs/vue3';

const props = defineProps({
    proyectoactual: Object
});

// Opciones del menú con iconos y colores
const menu = [
    {
        titulo: "Datos generales",
        descripcion: "Información básica del proyecto.",
        icono: "M13 10V3L4 14h7v7l9-11h-7z",
        ruta: "etapa1",
        color: "blue"
    },
    {
        titulo: "Definición Estratégica",
        descripcion: "Define la visión, misión y objetivos estratégicos.",
        icono: "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
        ruta: "definicionestrategica.index",
        color: "indigo"
    }
];

const getColorClasses = (color) => {
    const colors = {
        blue: {
            border: 'border-blue-500',
            bg: 'from-blue-50 to-blue-100',
            text: 'text-blue-800',
            icon: 'text-blue-600',
            button: 'bg-blue-600 hover:bg-blue-700'
        },
        indigo: {
            border: 'border-indigo-500',
            bg: 'from-indigo-50 to-indigo-100',
            text: 'text-indigo-800',
            icon: 'text-indigo-600',
            button: 'bg-indigo-600 hover:bg-indigo-700'
        }
    };
    return colors[color] || colors.blue;
};
</script>

<template>
    <AppLayout title="Etapa 2 - Definición Estratégica">
        <template #header>
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-blue-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', props.proyectoactual.id)">
                            {{ props.proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Etapa 2</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            2
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Definición Estratégica
                            </h2>
                            <p class="text-blue-100 mt-1">Completa los apartados para definir la estrategia de tu proyecto</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-5xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="(item, idx) in menu" :key="item.ruta"
                        class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden relative border-l-4"
                        :class="getColorClasses(item.color).border">
                        <div :class="['bg-gradient-to-r p-6', getColorClasses(item.color).bg]">
                            <div class="flex items-center mb-3">
                                <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3', getColorClasses(item.color).button]">
                                    {{ idx + 1 }}
                                </div>
                                <div :class="['w-10 h-10 rounded-lg flex items-center justify-center', getColorClasses(item.color).bg]">
                                    <svg :class="['w-6 h-6', getColorClasses(item.color).icon]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icono"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 :class="['font-bold text-lg leading-tight mb-2', getColorClasses(item.color).text]">
                                {{ item.titulo }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                                {{ item.descripcion }}
                            </p>
                            <Link :href="route(item.ruta, { id: props.proyectoactual.id })" class="block w-full">
                                <button :class="['w-full px-6 py-3 rounded-lg font-medium text-white transition-all duration-200 transform hover:scale-105 flex items-center justify-center space-x-2', getColorClasses(item.color).button]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>Ir a {{ item.titulo }}</span>
                                </button>
                            </Link>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-8 h-8 bg-white bg-opacity-90 rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 bg-white rounded-2xl shadow-lg p-8">
                    <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ¿Por qué es importante la Definición Estratégica?
                    </h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Permite alinear los esfuerzos del equipo hacia objetivos comunes.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Facilita la toma de decisiones estratégicas.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ayuda a comunicar la visión y misión del proyecto.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>