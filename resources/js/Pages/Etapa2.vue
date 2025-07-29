<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import IntegranteModal from '@/Components/IntegranteModal.vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    etapa2: Array,
    proyecto_id: Number,
    proyectoactual: Object
});
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const showModal = ref(false);
const editMode = ref(false);
const integranteEdit = ref(null);

const openNew = () => {
    integranteEdit.value = null;
    editMode.value = false;
    showModal.value = true;
};

const openEdit = (integrante) => {
    integranteEdit.value = { ...integrante };
    editMode.value = true;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    integranteEdit.value = null;
    editMode.value = false;
};

const reload = () => {
    window.location.reload();
};

const eliminarIntegrante = (id) => {
    if (confirm('¿Eliminar?')) {
        router.delete(`/integrantes/${id}`, {
            onSuccess: () => reload()
        });
    }
};
</script>

<template>
    <AppLayout title="Etapa 2 - Integrantes">
        <template #header>
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-green-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Etapa 2</span>
                    </nav>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                2
                            </div>
                            <div>
                                <h2 class="font-bold text-3xl text-white leading-tight">
                                    Integrantes del Equipo
                                </h2>
                                <p class="text-green-100 mt-1">Gestiona los miembros de tu equipo de trabajo</p>
                            </div>
                        </div>
                        <button @click="openNew" class="btn-primary bg-white text-green-700 hover:bg-green-50 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nuevo Integrante
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-green-50 py-8">
            <div class="max-w-6xl mx-auto px-4">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Header de la sección -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Lista de Integrantes
                                </h3>
                                <p class="text-gray-600 mt-1">
                                    {{ props.etapa2?.length || 0 }} integrante{{ (props.etapa2?.length !== 1) ? 's' : '' }} registrado{{ (props.etapa2?.length !== 1) ? 's' : '' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Link :href="route('projects.stages.show', proyectoactual.id)" class="btn-secondary">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Volver
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido de la tabla -->
                    <div class="p-8">
                        <!-- Estado vacío -->
                        <div v-if="!props.etapa2 || props.etapa2.length === 0" class="text-center py-12">
                            <div class="w-24 h-24 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">No hay integrantes registrados</h4>
                            <p class="text-gray-600 mb-6">Comienza agregando el primer miembro de tu equipo</p>
                            <button @click="openNew" class="btn-primary">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Agregar Primer Integrante
                            </button>
                        </div>

                        <!-- Tabla de integrantes -->
                        <div v-else class="overflow-hidden border border-gray-200 rounded-xl">
                            <div class="overflow-x-auto">
                                <table class="w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span>Nombre Completo</span>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0V4a2 2 0 014 0v2"></path>
                                                    </svg>
                                                    <span>Identificación</span>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                    </svg>
                                                    <span>Email</span>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(integrante, index) in props.etapa2" :key="integrante.id" 
                                            class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ integrante.nombre.charAt(0).toUpperCase() }}{{ integrante.apellido.charAt(0).toUpperCase() }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ integrante.nombre }} {{ integrante.apellido }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Integrante #{{ index + 1 }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                                        {{ integrante.tipo_identificacion }}
                                                    </span>
                                                    <div class="font-mono text-sm text-gray-700">
                                                        {{ integrante.numero_identificacion }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 flex items-center space-x-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                    </svg>
                                                    <span>{{ integrante.email }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <button @click="openEdit(integrante)" 
                                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Editar
                                                    </button>
                                                    <button @click="eliminarIntegrante(integrante.id)" 
                                                            class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Resumen y estadísticas -->
                        <div v-if="props.etapa2 && props.etapa2.length > 0" class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-600">Total Integrantes</p>
                                        <p class="text-2xl font-bold text-green-900">{{ props.etapa2.length }}</p>
                                    </div>
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600">Estado del Equipo</p>
                                        <p class="text-sm font-bold text-blue-900">
                                            {{ props.etapa2.length >= 2 ? 'Completo' : 'En formación' }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-xl border border-purple-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-purple-600">Próximo Paso</p>
                                        <p class="text-sm font-bold text-purple-900">Etapa 3</p>
                                    </div>
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <IntegranteModal
            :show="showModal"
            :integrante="integranteEdit"
            :proyecto-id="props.proyecto_id"
            :edit-mode="editMode"
            @close="closeModal"
            @saved="reload"
        />
    </AppLayout>
</template>

<style scoped>
/* Botones */
.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    background: linear-gradient(to right, #059669, #047857);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    text-decoration: none;
}

.btn-primary:hover {
    background: linear-gradient(to right, #047857, #065f46);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
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

.bg-white {
    animation: fadeIn 0.6s ease-out;
}

/* Hover effects para las filas de la tabla */
tr:hover {
    background-color: #f9fafb;
}

/* Estilos para los avatars */
.avatar-circle {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

/* Estados de focus para accesibilidad */
button:focus,
.btn-primary:focus,
.btn-secondary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .overflow-x-auto {
        margin: 0 -1rem;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
    
    .px-6 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .py-4 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
}

/* Loading states */
.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #9ca3af;
}

/* Custom scrollbar para la tabla en mobile */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Efectos de hover para los botones de acción */
button.text-red-700:hover {
    background-color: #fee2e2;
    border-color: #fca5a5;
}

button.text-gray-700:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

/* Badges de tipo de identificación */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Estados de transición */
.transition-colors {
    transition-property: color, background-color, border-color;
    transition-duration: 150ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Mejoras visuales para iconos */
svg {
    flex-shrink: 0;
}

/* Estilos para las tarjetas de estadísticas */
.stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.6));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.2s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-2px);
}
</style>