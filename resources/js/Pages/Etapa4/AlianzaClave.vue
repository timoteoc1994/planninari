<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";

import MenuNavegacion from './MenuNavegacion.vue';

const props = defineProps({
    alianzasclave: Array,
    proyecto_id: Number,
    proyectoactual: Object,
});

// Detectar la ruta actual para resaltar el menú
const actual = 'index.alianzasclave';

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
            <div class="bg-gradient-to-r from-teal-600 to-teal-400 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-teal-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <Link class="hover:text-white transition-colors" :href="route('etapa4', proyectoactual.id)">
                            Modelo de Negocio
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Alianzas Clave</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">Alianzas Clave</h2>
                            <p class="text-teal-100 mt-1">Establece las alianzas estratégicas necesarias para tu modelo de negocio</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Menú de navegación entre secciones -->
        <MenuNavegacion :proyecto_id="proyecto_id" :proyectoactual="proyectoactual" :actual="actual" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-teal-50 py-8">
            <div class="max-w-6xl mx-auto px-4">
                <!-- Panel educativo -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-teal-500">
                    <div class="flex items-center mb-4">
                        <svg class="w-7 h-7 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <h3 class="text-xl font-bold text-teal-800">¿Qué son las Alianzas Clave?</h3>
                    </div>
                    <p class="text-gray-700 mb-2">Son las asociaciones estratégicas que tu empresa necesita para operar y crecer. Incluyen proveedores, socios, aliados estratégicos, etc.</p>
                    <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
                        <li>¿Quiénes son tus socios clave?</li>
                        <li>¿Qué recursos obtienes de ellos?</li>
                        <li>¿Qué actividades realizan tus aliados?</li>
                    </ul>
                </div>

                <!-- Botón para abrir modal -->
                <div class="mb-6">
                    <button 
                        @click="openModal"
                        class="modern-btn-teal"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Aliado
                    </button>
                </div>

                <!-- Tabla de aliados -->
                <div class="overflow-x-auto rounded-2xl shadow bg-white">
                    <table class="min-w-full table-auto">
                        <thead class="bg-teal-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-teal-700 uppercase tracking-wider">
                                    Nombre del Aliado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-teal-700 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-teal-700 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-teal-100">
                            <tr v-for="aliado in alianzasclave" :key="aliado.id" class="hover:bg-teal-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ aliado.nombrealiado }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ aliado.descripcion }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button 
                                        @click="openEditModal(aliado)"
                                        class="modern-link-teal mr-3"
                                    >
                                        Editar
                                    </button>
                                    <button 
                                        @click="deleteAliado(aliado.id)"
                                        class="modern-link-red"
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
        <div v-if="showModal" class="fixed inset-0 bg-teal-900 bg-opacity-60 flex items-center justify-center z-50">
            <div class="modal-anim w-full max-w-md mx-auto p-6 rounded-2xl shadow-2xl bg-white border-l-4 border-teal-500">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-teal-800">
                        {{ editando ? 'Editar Aliado' : 'Nuevo Aliado' }}
                    </h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="nombrealiado" class="block text-sm font-medium text-teal-700 mb-2">Nombre del Aliado</label>
                        <input id="nombrealiado" v-model="form.nombrealiado" type="text" required class="modern-input" :class="{ 'border-red-500': form.errors.nombrealiado }" />
                        <div v-if="form.errors.nombrealiado" class="text-red-500 text-sm mt-1">{{ form.errors.nombrealiado }}</div>
                    </div>
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-teal-700 mb-2">Descripción</label>
                        <textarea id="descripcion" v-model="form.descripcion" rows="4" required class="modern-textarea" :class="{ 'border-red-500': form.errors.descripcion }"></textarea>
                        <div v-if="form.errors.descripcion" class="text-red-500 text-sm mt-1">{{ form.errors.descripcion }}</div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="closeModal" class="modern-btn-gray">Cancelar</button>
                        <button type="submit" :disabled="form.processing" class="modern-btn-teal">{{ form.processing ? 'Guardando...' : (editando ? 'Actualizar' : 'Guardar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
.modern-btn-teal {
  display: inline-flex;
  align-items: center;
  background: linear-gradient(90deg, #2dd4bf 0%, #14b8a6 100%);
  color: #fff;
  font-weight: 600;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px 0 rgba(20,184,166,0.10);
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.modern-btn-teal:hover {
  background: linear-gradient(90deg, #14b8a6 0%, #2dd4bf 100%);
  transform: scale(1.04);
}
.modern-btn-gray {
  display: inline-flex;
  align-items: center;
  background: #e5e7eb;
  color: #374151;
  font-weight: 500;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.modern-btn-gray:hover {
  background: #d1d5db;
  transform: scale(1.02);
}
.modern-link-teal {
  color: #14b8a6;
  font-weight: 600;
  text-decoration: underline;
  transition: color 0.2s;
}
.modern-link-teal:hover {
  color: #0f766e;
}
.modern-link-red {
  color: #dc2626;
  font-weight: 600;
  text-decoration: underline;
  transition: color 0.2s;
}
.modern-link-red:hover {
  color: #991b1b;
}
.modern-input {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #5eead4;
  background: linear-gradient(135deg, #ccfbf1 0%, #f0fdfa 100%);
  padding: 0.75rem 1rem;
  font-size: 1rem;
  color: #0f766e;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(20,184,166,0.08);
}
.modern-input:focus {
  border-color: #14b8a6;
  outline: none;
  box-shadow: 0 0 0 2px #5eead4;
}
.modern-textarea {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #5eead4;
  background: linear-gradient(135deg, #ccfbf1 0%, #f0fdfa 100%);
  padding: 1rem;
  font-size: 1rem;
  color: #0f766e;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(20,184,166,0.08);
  resize: vertical;
}
.modern-textarea:focus {
  border-color: #14b8a6;
  outline: none;
  box-shadow: 0 0 0 2px #5eead4;
}
.modal-anim {
  animation: modalIn 0.25s cubic-bezier(0.4,0,0.2,1);
}
@keyframes modalIn {
  from { opacity: 0; transform: translateY(40px) scale(0.98); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
@media (max-width: 640px) {
  .modern-btn-teal, .modern-btn-gray {
    width: 100%;
    justify-content: center;
  }
  .p-8 {
    padding: 1.25rem !important;
  }
}
</style>
