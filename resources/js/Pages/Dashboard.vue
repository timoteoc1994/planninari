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
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div 
                            v-for="project in props.projects"
                            :key="project.id"
                            class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6"
                        >
                            <div class=" justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 truncate">
                                    {{ project.name }}
                                </h3>
                                <div class="flex">
                                    
                                    <button
                                        @click="openEditModal(project)"
                                        class="text-blue-600 hover:text-blue-800 text-sm bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded"
                                        title="Editar"
                                    >
                                        ✏️ Editar
                                    </button>
                                    <button
                                        @click="deleteProject(project.id)"
                                        class="text-red-600 hover:text-red-800 text-sm bg-red-50 hover:bg-red-100 px-2 py-1 rounded"
                                        title="Eliminar"
                                    >
                                        🗑️ Eliminar
                                    </button>

                                    <Link :href="route('projects.stages.show', project.id)" class="text-blue-600 hover:text-blue-800 text-sm bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded">
                                        Detalles
                                    </Link>

                                </div>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ project.description || 'Sin descripción' }}
                            </p>
                            
                            <div class="text-xs text-gray-500">
                                Creado: {{ formatDate(project.created_at) }}
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Crear Proyecto -->
        <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click="closeModals">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Crear Nuevo Plan</h3>
                        <button @click="closeModals" class="text-gray-400 hover:text-gray-600 text-xl">
                            ✕
                        </button>
                    </div>
                    
                    <form @submit.prevent="createProject">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Nombre del Plan
                            </label>
                            <input
                                v-model="projectForm.name"
                                type="text"
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Ingresa el nombre del plan"
                            >
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Descripción
                            </label>
                            <textarea
                                v-model="projectForm.description"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                rows="3"
                                placeholder="Describe tu plan"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <button
                                type="button"
                                @click="closeModals"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Crear Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Proyecto -->
        <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click="closeModals">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Editar Plan</h3>
                        <button @click="closeModals" class="text-gray-400 hover:text-gray-600 text-xl">
                            ✕
                        </button>
                    </div>
                    
                    <form @submit.prevent="updateProject">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Nombre del Plan
                            </label>
                            <input
                                v-model="projectForm.name"
                                type="text"
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Ingresa el nombre del plan"
                            >
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Descripción
                            </label>
                            <textarea
                                v-model="projectForm.description"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                rows="3"
                                placeholder="Describe tu plan"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-between">
                            <button
                                type="button"
                                @click="closeModals"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Actualizar Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>