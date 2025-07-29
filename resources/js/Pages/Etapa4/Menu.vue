<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { usePage, router, Link } from "@inertiajs/vue3";

const props = defineProps({
    proyecto_id: Number,
    proyectoactual: Object,
});

// Definir las secciones del modelo de negocio con sus iconos y descripciones
const secciones = [
    {
        titulo: "Propuesta de Valor",
        descripcion: "Define qué valor único ofreces a tus clientes",
        icono: "M13 10V3L4 14h7v7l9-11h-7z",
        ruta: "index.propuestavalor",
        color: "blue",
        orden: 1
    },
    {
        titulo: "Segmento de Clientes",
        descripcion: "Identifica a quién te diriges específicamente",
        icono: "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
        ruta: "index.segmentoclientes",
        color: "green",
        orden: 2
    },
    {
        titulo: "Relación con Clientes",
        descripcion: "Establece cómo interactúas con tus clientes",
        icono: "M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z",
        ruta: "index.relacionclientes",
        color: "pink",
        orden: 3
    },
    {
        titulo: "Canales",
        descripcion: "Define cómo llegas y entregas valor a tus clientes",
        icono: "M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z",
        ruta: "index.canales",
        color: "purple",
        orden: 4
    },
    {
        titulo: "Actividades Clave",
        descripcion: "Identifica las actividades más importantes para operar",
        icono: "M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3a2 2 0 112 0v2M9 5a2 2 0 112 0v2",
        ruta: "index.actividadesclave",
        color: "indigo",
        orden: 5
    },
    {
        titulo: "Recursos Clave",
        descripcion: "Define los recursos más importantes que necesitas",
        icono: "M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10",
        ruta: "index.recursosclave",
        color: "emerald",
        orden: 6
    },
    {
        titulo: "Alianzas Clave",
        descripcion: "Establece las alianzas estratégicas necesarias",
        icono: "M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z",
        ruta: "index.alianzasclave",
        color: "teal",
        orden: 7
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
        green: {
            border: 'border-green-500',
            bg: 'from-green-50 to-green-100',
            text: 'text-green-800',
            icon: 'text-green-600',
            button: 'bg-green-600 hover:bg-green-700'
        },
        pink: {
            border: 'border-pink-500',
            bg: 'from-pink-50 to-pink-100',
            text: 'text-pink-800',
            icon: 'text-pink-600',
            button: 'bg-pink-600 hover:bg-pink-700'
        },
        purple: {
            border: 'border-purple-500',
            bg: 'from-purple-50 to-purple-100',
            text: 'text-purple-800',
            icon: 'text-purple-600',
            button: 'bg-purple-600 hover:bg-purple-700'
        },
        indigo: {
            border: 'border-indigo-500',
            bg: 'from-indigo-50 to-indigo-100',
            text: 'text-indigo-800',
            icon: 'text-indigo-600',
            button: 'bg-indigo-600 hover:bg-indigo-700'
        },
        emerald: {
            border: 'border-emerald-500',
            bg: 'from-emerald-50 to-emerald-100',
            text: 'text-emerald-800',
            icon: 'text-emerald-600',
            button: 'bg-emerald-600 hover:bg-emerald-700'
        },
        teal: {
            border: 'border-teal-500',
            bg: 'from-teal-50 to-teal-100',
            text: 'text-teal-800',
            icon: 'text-teal-600',
            button: 'bg-teal-600 hover:bg-teal-700'
        }
    };
    return colors[color] || colors.blue;
};
</script>

<template>
    <AppLayout title="Etapa 4 - Modelo de Negocio">
        <template #header>
            <div class="bg-gradient-to-r from-orange-600 to-amber-700 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-orange-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Etapa 4</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            4
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Modelo de Negocio
                            </h2>
                            <p class="text-orange-100 mt-1">Completa todas las secciones del Canvas de Modelo de Negocio</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-orange-50 py-8">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Panel de progreso -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Canvas del Modelo de Negocio</h3>
                            <p class="text-gray-600 mt-1">Completa las 7 secciones fundamentales para definir tu modelo de negocio</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-orange-600">0/7</div>
                            <div class="text-sm text-gray-500">Secciones completadas</div>
                        </div>
                    </div>
                    
                    <!-- Barra de progreso -->
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                        <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>Progreso del modelo de negocio</span>
                        <span>0% completado</span>
                    </div>
                </div>

                <!-- Grid de secciones -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div v-for="seccion in secciones" :key="seccion.orden" 
                         class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                        
                        <!-- Header de la tarjeta -->
                        <div :class="['bg-gradient-to-r p-6', getColorClasses(seccion.color).bg, getColorClasses(seccion.color).border]" 
                             class="border-l-4 relative">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-3">
                                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3', getColorClasses(seccion.color).button]">
                                            {{ seccion.orden }}
                                        </div>
                                        <div :class="['w-10 h-10 rounded-lg flex items-center justify-center', getColorClasses(seccion.color).bg]">
                                            <svg :class="['w-6 h-6', getColorClasses(seccion.color).icon]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="seccion.icono"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 :class="['font-bold text-lg leading-tight mb-2', getColorClasses(seccion.color).text]">
                                        {{ seccion.titulo }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido de la tarjeta -->
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                                {{ seccion.descripcion }}
                            </p>

                           

                            <!-- Botón de acción -->
                            <Link :href="route(seccion.ruta, { id: props.proyecto_id })" 
                                  class="block w-full">
                                <button :class="['w-full px-6 py-3 rounded-lg font-medium text-white transition-all duration-200 transform hover:scale-105 flex items-center justify-center space-x-2', getColorClasses(seccion.color).button]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>Completar Sección</span>
                                </button>
                            </Link>
                        </div>

                        <!-- Indicador de progreso en hover -->
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-8 h-8 bg-white bg-opacity-90 rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de información adicional -->
                <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Información sobre el Canvas -->
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                ¿Qué es el Canvas del Modelo de Negocio?
                            </h4>
                            <p class="text-gray-600 mb-4">
                                Es una herramienta visual que te ayuda a describir, diseñar y analizar tu modelo de negocio de manera estructurada y completa.
                            </p>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Define claramente tu propuesta de valor
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Identifica tus segmentos de clientes objetivo
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Establece canales de distribución efectivos
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Optimiza recursos y actividades clave
                                </li>
                            </ul>
                        </div>

                        <!-- Consejos -->
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Consejos para completar
                            </h4>
                            <div class="space-y-4">
                                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                                    <p class="text-blue-800 text-sm font-medium mb-1">Orden recomendado</p>
                                    <p class="text-blue-700 text-sm">Completa las secciones en el orden numerado para un mejor flujo de trabajo.</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                                    <p class="text-green-800 text-sm font-medium mb-1">Información detallada</p>
                                    <p class="text-green-700 text-sm">Cada sección incluye guías y ejemplos para ayudarte a completarla correctamente.</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                                    <p class="text-purple-800 text-sm font-medium mb-1">Revisa y ajusta</p>
                                    <p class="text-purple-700 text-sm">Puedes volver a editar cualquier sección en cualquier momento.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navegación -->
                    <div class="flex justify-between items-center pt-8 border-t border-gray-200 mt-8">
                        <Link :href="route('projects.stages.show', proyectoactual.id)" class="btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a Etapas
                        </Link>
                        
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-2">Completa todas las secciones para continuar</p>
                            <button class="btn-disabled" disabled>
                                Continuar a Etapa 5
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Botones */
.btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    background-color: white;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
}

.btn-secondary:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
    transform: translateY(-1px);
}

.btn-disabled {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #9ca3af;
    background-color: #f9fafb;
    cursor: not-allowed;
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-white {
    animation: fadeIn 0.6s ease-out;
}

/* Animación escalonada para las tarjetas */
.group:nth-child(1) { animation: slideInUp 0.6s ease-out 0.1s both; }
.group:nth-child(2) { animation: slideInUp 0.6s ease-out 0.2s both; }
.group:nth-child(3) { animation: slideInUp 0.6s ease-out 0.3s both; }
.group:nth-child(4) { animation: slideInUp 0.6s ease-out 0.4s both; }
.group:nth-child(5) { animation: slideInUp 0.6s ease-out 0.5s both; }
.group:nth-child(6) { animation: slideInUp 0.6s ease-out 0.6s both; }
.group:nth-child(7) { animation: slideInUp 0.6s ease-out 0.7s both; }

/* Efectos hover mejorados */
.group:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.group:hover button {
    transform: scale(1.05);
}

/* Estados de focus para accesibilidad */
button:focus,
.btn-secondary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-secondary, .btn-disabled {
        width: 100%;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
    
    .flex.justify-between {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .grid.grid-cols-1.lg\\:grid-cols-2 {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-8 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
}

@media (max-width: 640px) {
    .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4 {
        grid-template-columns: 1fr;
    }
}

/* Mejoras en la barra de progreso */
.bg-gradient-to-r.from-orange-500.to-amber-500 {
    background: linear-gradient(to right, #f97316, #f59e0b);
    box-shadow: 0 2px 4px rgba(249, 115, 22, 0.3);
}

/* Efectos de pulsación para indicadores */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.w-3.h-3.rounded-full.bg-gray-300 {
    animation: pulse 2s infinite;
}

/* Mejoras visuales para los iconos */
svg {
    flex-shrink: 0;
}

/* Efectos de transición para las tarjetas */
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.group:hover .absolute {
    transform: scale(1.1);
    transition: transform 0.2s ease-in-out;
}

/* Estilos para los números de orden */
.w-8.h-8.rounded-full {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Mejoras en las secciones informativas */
.border-l-4 {
    transition: all 0.2s ease-in-out;
}

.border-l-4:hover {
    transform: translateX(2px);
}

/* Estados de completado (para futuro uso) */
.completed {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.completed .w-3.h-3.rounded-full {
    background: #10b981;
    animation: none;
}

/* Efectos de glassmorphism */
.bg-white.bg-opacity-90 {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Mejoras en los gradientes */
.bg-gradient-to-r {
    background-attachment: fixed;
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Efectos de iluminación */
.group:hover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
    pointer-events: none;
    border-radius: 1rem;
}

/* Espaciado mejorado */
.space-y-2 > * + * {
    margin-top: 0.5rem;
}

.space-y-4 > * + * {
    margin-top: 1rem;
}
</style>
