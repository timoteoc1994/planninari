<template>
    <div
        class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6 cursor-pointer"
        @click="goToStages"
    >
        <div class="flex justify-between items-start mb-4">
            <h4 class="text-lg font-semibold text-gray-800 truncate">{{ project.name }}</h4>
            <div class="flex space-x-2">
                <button
                    @click.stop="editProject"
                    class="p-1 text-gray-400 hover:text-blue-600 transition-colors"
                    title="Editar proyecto"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button
                    @click.stop="deleteProject"
                    class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                    title="Eliminar proyecto"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <p v-if="project.description" class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ project.description }}
        </p>
        <p v-else class="text-gray-400 text-sm mb-4 italic">
            Sin descripción
        </p>
        
        <div class="flex justify-between items-center text-xs text-gray-500">
            <span>Creado: {{ formatDate(project.created_at) }}</span>
            <span v-if="project.updated_at !== project.created_at">
                Editado: {{ formatDate(project.updated_at) }}
            </span>
        </div>
        
        <!-- Modal de edición -->
        <ProjectModal
            :show="showEditModal"
            :project="project"
            :edit-mode="true"
            @close="showEditModal = false"
            @submit="handleEdit"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import ProjectModal from './ProjectModal.vue';

const props = defineProps({
    project: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['edit', 'delete']);

const showEditModal = ref(false);

const editProject = () => {
    showEditModal.value = true;
};

const deleteProject = () => {
    emit('delete', props.project.id);
};

const handleEdit = (projectData) => {
    emit('edit', props.project.id, projectData);
    showEditModal.value = false;
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const goToStages = () => {
    router.get(`/projects/${props.project.id}`);
};
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
