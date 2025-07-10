<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import ProjectModal from '@/Components/ProjectModal.vue';
import ProjectCard from '@/Components/ProjectCard.vue';
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const showModal = ref(false);
const projects = ref([]);
const loading = ref(false);

const fetchProjects = async () => {
    try {
        loading.value = true;
        const response = await fetch('/projects', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        projects.value = data;
    } catch (error) {
        console.error('Error fetching projects:', error);
    } finally {
        loading.value = false;
    }
};

const createProject = async (projectData) => {
    try {
        const response = await fetch('/projects', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(projectData)
        });
        
        if (response.ok) {
            showModal.value = false;
            await fetchProjects();
        }
    } catch (error) {
        console.error('Error creating project:', error);
    }
};

const editProject = async (projectId, projectData) => {
    try {
        const response = await fetch(`/projects/${projectId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(projectData)
        });
        
        if (response.ok) {
            await fetchProjects();
        }
    } catch (error) {
        console.error('Error updating project:', error);
    }
};

const deleteProject = async (projectId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este proyecto?')) {
        try {
            const response = await fetch(`/projects/${projectId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                await fetchProjects();
            }
        } catch (error) {
            console.error('Error deleting project:', error);
        }
    }
};

onMounted(() => {
    fetchProjects();
});
</script>

<template>
    <AppLayout title="Planes">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Planes
                </h2>
                <button
                    @click="showModal = true"
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
                    
                    <div v-if="loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <p class="mt-2 text-gray-600">Cargando planes...</p>
                    </div>

                    <div v-else-if="projects.length === 0" class="text-center py-8">
                        <p class="text-gray-600">No tienes planes aún. ¡Crea tu primer plan!</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <ProjectCard
                            v-for="project in projects"
                            :key="project.id"
                            :project="project"
                            @edit="editProject"
                            @delete="deleteProject"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <ProjectModal
            :show="showModal"
            @close="showModal = false"
            @submit="createProject"
        />
    </AppLayout>
</template>
