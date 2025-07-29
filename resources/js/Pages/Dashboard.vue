<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    projects: Array,
});

const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingProject = ref(null);

const projectForm = ref({
    name: '',
    description: ''
});

const resetForm = () => {
    projectForm.value = {
        name: '',
        description: ''
    };
};
    
const createProject = () => {
    router.post(route('projects.store'), projectForm.value, {
        onSuccess: () => {
            showCreateModal.value = false;
            resetForm();
        },
        onError: (error) => {
            console.error('Error creating project:', error);
        }
    });
};

const openEditModal = (project) => {
    editingProject.value = project;
    projectForm.value = {
        name: project.name,
        description: project.description
    };
    showEditModal.value = true;
};

const updateProject = () => {
    router.post(route('projects.update', editingProject.value.id), projectForm.value, {
        onSuccess: () => {
            showEditModal.value = false;
            editingProject.value = null;
            resetForm();
        },
        onError: (error) => {
            console.error('Error updating project:', error);
        }
    });
};

const deleteProject = (projectId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este proyecto?')) {
        router.delete(route('projects.destroy', projectId), {
            onError: (error) => {
                console.error('Error deleting project:', error);
            }
        });
    }
};

const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    editingProject.value = null;
    resetForm();
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <AppLayout title="Planes">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Planes
                </h2>
                <button
                    @click="showCreateModal = true"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Nuevo Plan
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Projects Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Mis Planes</h3>
                    
                    <div v-if="props.projects.length === 0" class="text-center py-8">
                        <p class="text-gray-600">No tienes planes aún. ¡Crea tu primer plan!</p>
                    </div>

                    <!-- Project Cards integradas directamente -->
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        <div 
                            v-for="project in props.projects"
                            :key="project.id"
                            class="bg-gradient-to-br from-blue-50 via-white to-blue-100 border-2 border-blue-200 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-6 relative group flex flex-col justify-between"
                        >
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" /></svg>
                                    <h3 class="text-xl font-bold text-blue-800 truncate">{{ project.name }}</h3>
                                </div>
                                <p class="bg-blue-50/60 text-gray-700 text-sm mb-4 line-clamp-3 rounded-lg px-3 py-2 shadow-inner">
                                    {{ project.description || 'Sin descripción' }}
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 mt-2 w-full">
                                <div class="flex flex-col gap-2 items-stretch w-full">
                                    <button
                                        @click="openEditModal(project)"
                                        class="flex items-center gap-1 text-blue-700 hover:text-white bg-blue-100 hover:bg-blue-600 px-2 py-1 rounded font-semibold shadow text-xs transition-all duration-200 w-full"
                                        title="Editar"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h2v2h-2v-2z" /></svg>
                                        Editar
                                    </button>
                                    <button
                                        @click="deleteProject(project.id)"
                                        class="flex items-center gap-1 text-red-700 hover:text-white bg-red-100 hover:bg-red-600 px-2 py-1 rounded font-semibold shadow text-xs transition-all duration-200 w-full"
                                        title="Eliminar"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Eliminar
                                    </button>
                                    <Link :href="route('projects.stages.show', project.id)" class="flex items-center gap-1 text-indigo-700 hover:text-white bg-indigo-100 hover:bg-indigo-600 px-2 py-1 rounded font-semibold shadow text-xs transition-all duration-200 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        Detalles
                                    </Link>
                                </div>
                                <div class="flex justify-end mt-2">
                                    <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded-full text-xs font-bold shadow">Creado: {{ formatDate(project.created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Crear Proyecto -->
        <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-blue-200/60 via-white/80 to-blue-400/60 backdrop-blur-sm" @click="closeModals">
            <div class="relative mx-auto p-6 w-full max-w-md bg-white rounded-2xl shadow-2xl border border-blue-200" @click.stop>
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <h3 class="text-xl font-bold text-blue-700">Crear Nuevo Plan</h3>
                    </div>
                    <button @click="closeModals" class="text-gray-400 hover:text-blue-600 text-2xl font-bold transition-colors">
                        ✕
                    </button>
                </div>
                <form @submit.prevent="createProject" class="space-y-4">
                    <div>
                        <label class="block text-blue-700 text-sm font-bold mb-2">Nombre del Plan</label>
                        <input
                            v-model="projectForm.name"
                            type="text"
                            required
                            class="w-full px-4 py-2 rounded-lg border-2 border-blue-200 bg-blue-50 text-blue-900 focus:outline-none focus:border-blue-400 transition"
                            placeholder="Ingresa el nombre del plan"
                        >
                    </div>
                    <div>
                        <label class="block text-blue-700 text-sm font-bold mb-2">Descripción</label>
                        <textarea
                            v-model="projectForm.description"
                            class="w-full px-4 py-2 rounded-lg border-2 border-blue-200 bg-blue-50 text-blue-900 focus:outline-none focus:border-blue-400 transition"
                            rows="3"
                            placeholder="Describe tu plan"
                        ></textarea>
                    </div>
                    <div class="flex gap-2 justify-end mt-6">
                        <button
                            type="button"
                            @click="closeModals"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold px-4 py-2 rounded-lg transition-all shadow"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg transition-all shadow flex items-center gap-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Crear Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para Editar Proyecto -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-blue-200/60 via-white/80 to-blue-400/60 backdrop-blur-sm" @click="closeModals">
            <div class="relative mx-auto p-6 w-full max-w-md bg-white rounded-2xl shadow-2xl border border-blue-200" @click.stop>
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V4m8 8H4" /></svg>
                        <h3 class="text-xl font-bold text-blue-700">Editar Plan</h3>
                    </div>
                    <button @click="closeModals" class="text-gray-400 hover:text-blue-600 text-2xl font-bold transition-colors">
                        ✕
                    </button>
                </div>
                <form @submit.prevent="updateProject" class="space-y-4">
                    <div>
                        <label class="block text-blue-700 text-sm font-bold mb-2">Nombre del Plan</label>
                        <input
                            v-model="projectForm.name"
                            type="text"
                            required
                            class="w-full px-4 py-2 rounded-lg border-2 border-blue-200 bg-blue-50 text-blue-900 focus:outline-none focus:border-blue-400 transition"
                            placeholder="Ingresa el nombre del plan"
                        >
                    </div>
                    <div>
                        <label class="block text-blue-700 text-sm font-bold mb-2">Descripción</label>
                        <textarea
                            v-model="projectForm.description"
                            class="w-full px-4 py-2 rounded-lg border-2 border-blue-200 bg-blue-50 text-blue-900 focus:outline-none focus:border-blue-400 transition"
                            rows="3"
                            placeholder="Describe tu plan"
                        ></textarea>
                    </div>
                    <div class="flex gap-2 justify-end mt-6">
                        <button
                            type="button"
                            @click="closeModals"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold px-4 py-2 rounded-lg transition-all shadow"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg transition-all shadow flex items-center gap-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V4m8 8H4" /></svg>
                            Actualizar Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>