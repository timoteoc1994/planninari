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
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 py-8">
                <div class="max-w-4xl mx-auto px-4">
                    <h2 class="font-bold text-3xl text-white leading-tight">
                        Etapas de {{ project.name }}
                    </h2>
                    <p class="text-blue-100 mt-2">Completa cada etapa para desarrollar tu proyecto de negocio</p>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-12">
            <div class="max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Etapa 1 -->
                    <a :href="route('etapa1', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-blue-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    1
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 transition-colors">Datos Generales</h3>
                                    <span class="text-sm text-gray-500">Etapa 1</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Información básica del proyecto</p>
                        </div>
                    </a>

                    <!-- Etapa 2 -->
                    <a :href="route('etapa2', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-green-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    2
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-green-600 transition-colors">Integrantes</h3>
                                    <span class="text-sm text-gray-500">Etapa 2</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Equipo de trabajo del proyecto</p>
                        </div>
                    </a>

                    <!-- Etapa 3 -->
                    <a :href="route('etapa3', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-purple-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    3
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-purple-600 transition-colors">Oportunidad de Negocio</h3>
                                    <span class="text-sm text-gray-500">Etapa 3</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Análisis de mercado y oportunidades</p>
                        </div>
                    </a>

                    <!-- Etapa 4 -->
                    <a :href="route('etapa4', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-orange-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    4
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-orange-600 transition-colors">Modelo de Negocio</h3>
                                    <span class="text-sm text-gray-500">Etapa 4</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Estructura y estrategia del negocio</p>
                        </div>
                    </a>

                    <!-- Etapa 5 -->
                    <a :href="route('etapa5', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-pink-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    5
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-pink-600 transition-colors">Igualdad de Género</h3>
                                    <span class="text-sm text-gray-500">Etapa 5</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Inclusión y equidad en el proyecto</p>
                        </div>
                    </a>

                    <!-- Etapa 6 -->
                    <a :href="route('etapa6', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-emerald-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    6
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-emerald-600 transition-colors">Mitigación Ambiental</h3>
                                    <span class="text-sm text-gray-500">Etapa 6</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Impacto ambiental y sostenibilidad</p>
                        </div>
                    </a>

                    <!-- Etapa 7 -->
                    <a :href="route('etapa7.index', project.id)" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-indigo-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    7
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-indigo-600 transition-colors">Inversiones</h3>
                                    <span class="text-sm text-gray-500">Etapa 7</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Capital requerido y financiamiento</p>
                        </div>
                    </a>

                    <!-- Etapa 8 -->
                    <a :href="route('etapa8', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-teal-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    8
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-teal-600 transition-colors">Planificación Financiera</h3>
                                    <span class="text-sm text-gray-500">Etapa 8</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Presupuestos y proyecciones</p>
                        </div>
                    </a>

                    <!-- Etapa 9 -->
                    <a :href="route('etapa9', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-cyan-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    9
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-cyan-600 transition-colors">Presentación del Negocio</h3>
                                    <span class="text-sm text-gray-500">Etapa 9</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Pitch y propuesta de valor</p>
                        </div>
                    </a>

                    <!-- Etapa 10 -->
                    <a :href="route('etapa10', { id: project.id })" 
                       class="group block bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 border-violet-500 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-violet-500 to-violet-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    10
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-violet-600 transition-colors">Documentos Varios</h3>
                                    <span class="text-sm text-gray-500">Etapa 10</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">Documentación adicional</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>