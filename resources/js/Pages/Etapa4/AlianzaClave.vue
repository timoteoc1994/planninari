<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    alianzasclave: Array,
    proyecto_id: Number,
});

// Estados del modal
const showModal = ref(false);
const editingAliado = ref(null);

// Formulario
const form = useForm({
    nombrealiado: "",
    descripcion: "",
    proyecto_id: props.proyecto_id,
});

const editando = computed(() => !!editingAliado.value);

// Funciones del modal
function openModal() {
    showModal.value = true;
    editingAliado.value = null;
    form.reset();
    form.proyecto_id = props.proyecto_id;
}

function openEditModal(aliado) {
    showModal.value = true;
    editingAliado.value = aliado;
    form.nombrealiado = aliado.nombrealiado;
    form.descripcion = aliado.descripcion;
    form.proyecto_id = props.proyecto_id;
}

function closeModal() {
    showModal.value = false;
    editingAliado.value = null;
    form.reset();
}

function submit() {
    if (editando.value) {
        form.post(route("alianzasclave.update", editingAliado.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route("alianzasclave.store"), {
            onSuccess: () => closeModal(),
        });
    }
}

function deleteAliado(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este aliado?")) {
        router.delete(route("alianzasclave.destroy", id));
    }
}
</script>

<template>
    <AppLayout title="Alianzas Clave">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Alianzas Clave
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
                <!-- Botón para abrir modal -->
                <div class="mb-6">
                    <button 
                        @click="openModal"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Nuevo Aliado
                    </button>
                </div>

                <!-- Tabla de aliados -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre del Aliado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="aliado in alianzasclave" :key="aliado.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ aliado.nombrealiado }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ aliado.descripcion }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button 
                                        @click="openEditModal(aliado)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3"
                                    >
                                        Editar
                                    </button>
                                    <button 
                                        @click="deleteAliado(aliado.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!alianzasclave?.length">
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    No hay aliados registrados
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Header del modal -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ editando ? 'Editar Aliado' : 'Nuevo Aliado' }}
                        </h3>
                        <button 
                            @click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="nombrealiado" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Aliado
                            </label>
                            <input
                                id="nombrealiado"
                                v-model="form.nombrealiado"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.nombrealiado }"
                            />
                            <div v-if="form.errors.nombrealiado" class="text-red-500 text-sm mt-1">
                                {{ form.errors.nombrealiado }}
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea
                                id="descripcion"
                                v-model="form.descripcion"
                                rows="4"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.descripcion }"
                            ></textarea>
                            <div v-if="form.errors.descripcion" class="text-red-500 text-sm mt-1">
                                {{ form.errors.descripcion }}
                            </div>
                        </div>

                        <!-- Botones del modal -->
                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Guardando...' : (editando ? 'Actualizar' : 'Guardar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>