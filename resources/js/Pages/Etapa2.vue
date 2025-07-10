<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import IntegranteModal from '@/Components/IntegranteModal.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    etapa2: Array,
    proyecto_id: Number,
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
    <AppLayout title="Etapa 2">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Etapa 2 - Integrantes
                </h2>
                <a :href="`/proyectos/${props.proyecto_id}/integrantes/create`" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo Integrante</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
                <table class="w-full table-auto mb-6">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-1">Nombre</th>
                            <th class="px-2 py-1">Apellido</th>
                            <th class="px-2 py-1">Tipo ID</th>
                            <th class="px-2 py-1">N° ID</th>
                            <th class="px-2 py-1">Email</th>
                            <th class="px-2 py-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="integrante in props.etapa2" :key="integrante.id">
                            <td class="px-2 py-1">{{ integrante.nombre }}</td>
                            <td class="px-2 py-1">{{ integrante.apellido }}</td>
                            <td class="px-2 py-1">{{ integrante.tipo_identificacion }}</td>
                            <td class="px-2 py-1">{{ integrante.numero_identificacion }}</td>
                            <td class="px-2 py-1">{{ integrante.email }}</td>
                            <td class="px-2 py-1">
                                <a :href="`/integrantes/${integrante.id}/edit`" class="text-blue-600 mr-2">Editar</a>
                                <button @click="eliminarIntegrante(integrante.id)" class="text-red-600">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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