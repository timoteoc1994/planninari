<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router, Link } from "@inertiajs/vue3";


const props = defineProps({
    planes: Object,
});


const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingPlan = ref(null);

const planForm = ref({
    nombre: "",
    descripcion: "",
});

const resetForm = () => {
    planForm.value = {
        nombre: "",
        descripcion: "",
    };
};

const openCreateModal = () => {
    resetForm();
    showCreateModal.value = true;
};

const openEditModal = (plan) => {
    editingPlan.value = plan;
    planForm.value = {
        nombre: plan.nombre,
        descripcion: plan.descripcion,
    };
    showEditModal.value = true;
};

const closeModals = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    editingPlan.value = null;
    resetForm();
};

const createPlan = () => {
      router.post(route("planes.store"), planForm.value, {
        onSuccess: () => {
            showCreateModal.value = false;
            resetForm();
        },
        onError: (error) => {
            console.error("Error creating project:", error);
        },
    });
};

const updatePlan = () => {
    router.put(
        route("planes.update", editingPlan.value.id),
        planForm.value,
        {
            onSuccess: () => {
                showEditModal.value = false;
                editingPlan.value = null;
                resetForm();
            },
            onError: (error) => {
                console.error("Error updating plan:", error);
            },
        }
    );
};

const deletePlan = (planId) => {
    if (confirm("¿Estás seguro de que quieres eliminar este plan?")) {
        router.delete(route("planes.destroy", planId), {
            onError: (error) => {
                console.error("Error deleting plan:", error);
            },
        });
    }
};

</script>

<template>
    <AppLayout title="Etapa 2">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Proyectos
                </h2>
                <button
                    @click="openCreateModal"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Nuevo Plan
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg p-6">
                    <table class="min-w-full divide-y divide-blue-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase">ID</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase">Nombre</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase">Descripción</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase">Creado</th>
                                 <th class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="plan in planes.data" :key="plan.id" class="hover:bg-blue-50">
                                <td class="px-4 py-2">{{ plan.id }}</td>
                                <td class="px-4 py-2 font-semibold text-blue-800">{{ plan.nombre }}</td>
                                <td class="px-4 py-2">{{ plan.descripcion }}</td>
                                <td class="px-4 py-2">{{ plan.created_at ? new Date(plan.created_at).toLocaleDateString('es-ES') : 'Sin fecha' }}</td>
                                <td class="px-4 py-2 flex gap-2">
                                    <button
                                        @click="openEditModal(plan)"
                                        class="flex items-center gap-1 text-blue-700 hover:text-white bg-blue-100 hover:bg-blue-600 px-2 py-1 rounded font-semibold shadow text-xs transition-all duration-200"
                                        title="Editar"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h2v2h-2v-2z"/></svg>
                                        Editar
                                    </button>
                                    <button
                                        @click="deletePlan(plan.id)"
                                        class="flex items-center gap-1 text-red-700 hover:text-white bg-red-100 hover:bg-red-600 px-2 py-1 rounded font-semibold shadow text-xs transition-all duration-200"
                                        title="Eliminar"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Eliminar
                                    </button>
                                    <Link
                                        :href="
                                            route(
                                                'planes.show',
                                                plan.id
                                            )
                                        "
                                        class="flex items-center gap-1 text-indigo-700 hover:text-white bg-indigo-100 hover:bg-indigo-600 px-2 py-1 rounded font-semibold shadow text-xs transition-all duration-200 w-full"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5l7 7-7 7"
                                            />
                                        </svg>
                                        Detalles
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex gap-2 justify-center mt-6">
                        <button
                            v-for="link in planes.links"
                            :key="link.label"
                            :disabled="!link.url || link.active"
                            @click="$inertia.visit(link.url)"
                            v-html="link.label"
                            class="px-3 py-1 rounded border text-blue-700 bg-blue-50 hover:bg-blue-200 font-semibold"
                            :class="{ 'bg-blue-700 text-white': link.active, 'cursor-not-allowed opacity-50': !link.url }"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <!-- Modal para Crear Plan -->
    <div
        v-if="showCreateModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-blue-200/60 via-white/80 to-blue-400/60 backdrop-blur-sm"
        @click="closeModals"
    >
        <div
            class="relative mx-auto p-6 w-full max-w-md bg-white rounded-2xl shadow-2xl border border-blue-200"
            @click.stop
        >
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7 text-blue-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    <h3 class="text-xl font-bold text-blue-700">
                        Crear Nuevo Plan
                    </h3>
                </div>
                <button
                    @click="closeModals"
                    class="text-gray-400 hover:text-blue-600 text-2xl font-bold transition-colors"
                >
                    ✕
                </button>
            </div>
            <form @submit.prevent="createPlan" class="space-y-4">
                <div>
                    <label class="block text-blue-700 text-sm font-bold mb-2">Nombre del Plan</label>
                    <input
                        v-model="planForm.nombre"
                        type="text"
                        required
                        class="w-full px-4 py-2 rounded-lg border-2 border-blue-200 bg-blue-50 text-blue-900 focus:outline-none focus:border-blue-400 transition"
                        placeholder="Ingresa el nombre del plan"
                    />
                </div>
                <div>
                    <label class="block text-blue-700 text-sm font-bold mb-2">Descripción</label>
                    <textarea
                        v-model="planForm.descripcion"
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
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                        Crear Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Editar Plan -->
    <div
        v-if="showEditModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-blue-200/60 via-white/80 to-blue-400/60 backdrop-blur-sm"
        @click="closeModals"
    >
        <div
            class="relative mx-auto p-6 w-full max-w-md bg-white rounded-2xl shadow-2xl border border-blue-200"
            @click.stop
        >
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7 text-blue-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 20V4m8 8H4"
                        />
                    </svg>
                    <h3 class="text-xl font-bold text-blue-700">
                        Editar Plan
                    </h3>
                </div>
                <button
                    @click="closeModals"
                    class="text-gray-400 hover:text-blue-600 text-2xl font-bold transition-colors"
                >
                    ✕
                </button>
            </div>
            <form @submit.prevent="updatePlan" class="space-y-4">
                <div>
                    <label class="block text-blue-700 text-sm font-bold mb-2">Nombre del Plan</label>
                    <input
                        v-model="planForm.nombre"
                        type="text"
                        required
                        class="w-full px-4 py-2 rounded-lg border-2 border-blue-200 bg-blue-50 text-blue-900 focus:outline-none focus:border-blue-400 transition"
                        placeholder="Ingresa el nombre del plan"
                    />
                </div>
                <div>
                    <label class="block text-blue-700 text-sm font-bold mb-2">Descripción</label>
                    <textarea
                        v-model="planForm.descripcion"
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
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 20V4m8 8H4"
                            />
                        </svg>
                        Actualizar Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

