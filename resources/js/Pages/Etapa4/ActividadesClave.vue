<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router, usePage,Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import MenuNavegacion from './MenuNavegacion.vue';

const props = defineProps({
  actividadesclave: Object,
  procesocomercial: Array, // Cambiado a Array para múltiples pasos
  procesoproductivo: Array, // Cambiado a Array para múltiples pasos
  proyecto_id: Number,
  proyectoactual: Object,
})

// Estados para los modales
const showModalProcesoProductivo = ref(false)
const showModalProcesoComercial = ref(false)
const editingProcesoProductivo = ref(null)
const editingProcesoComercial = ref(null)

// Acceder a los mensajes flash
const page = usePage()
const successMessage = computed(() => page.props.flash?.success)
const errorMessage = computed(() => page.props.flash?.error)

// Inicializa el formulario con los datos si existen, o vacío si no
const form = useForm({
  actividad_clave: props.actividadesclave?.actividad_clave ?? '',
  archivo: props.actividadesclave?.archivo ?? '',
  proyecto_id: props.proyecto_id,
  archivo: null, // Para manejar la subida de archivos
})

const formmodalprocesoproductivo = useForm({
  paso: '',
  proyecto_id: props.proyecto_id,
})

const formmodalprocesocomercial = useForm({
  paso: '',
  proyecto_id: props.proyecto_id,
})

const editando = computed(() => !!props.actividadesclave)

function submit() {
  if (editando.value) {
    form.forceFormData = true
    router.post(route('actividadesclave.update', props.actividadesclave.id), form.data())
  } else {
    form.post(route('actividadesclave.store'))
  }
}



function submitProcesoProductivo() {
  if (editingProcesoProductivo.value) {
    router.post(route('proceso-productivo.update', editingProcesoProductivo.value.id), formmodalprocesoproductivo.data())
  } else {
    formmodalprocesoproductivo.post(route('proceso-productivo.store'))
  }
  closeModalProcesoProductivo()
}

function submitProcesoComercial() {
  if (editingProcesoComercial.value) {
    router.post(route('proceso-comercial.update', editingProcesoComercial.value.id), formmodalprocesocomercial.data())
  } else {
    formmodalprocesocomercial.post(route('proceso-comercial.store'))
  }
  closeModalProcesoComercial()
}

// Funciones para manejar Proceso Productivo
function openModalProcesoProductivo(item = null) {
  editingProcesoProductivo.value = item
  formmodalprocesoproductivo.paso = item ? item.paso : ''
  showModalProcesoProductivo.value = true
}

function closeModalProcesoProductivo() {
  showModalProcesoProductivo.value = false
  editingProcesoProductivo.value = null
  formmodalprocesoproductivo.reset()
}

function deleteProcesoProductivo(id) {
  if (confirm('¿Estás seguro de que quieres eliminar este paso?')) {
    router.delete(route('proceso-productivo.destroy', id))
  }
}

// Funciones para manejar Proceso Comercial
function openModalProcesoComercial(item = null) {
  editingProcesoComercial.value = item
  formmodalprocesocomercial.paso = item ? item.paso : ''
  showModalProcesoComercial.value = true
}

function closeModalProcesoComercial() {
  showModalProcesoComercial.value = false
  editingProcesoComercial.value = null
  formmodalprocesocomercial.reset()
}

function deleteProcesoComercial(id) {
  if (confirm('¿Estás seguro de que quieres eliminar este paso?')) {
    router.delete(route('proceso-comercial.destroy', id))
  }
}
</script>

<template>
    <AppLayout title="Actividades Clave">
        <template #header>
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-400 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-indigo-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <Link class="hover:text-white transition-colors" :href="route('etapa4', proyectoactual.id)">
                            Modelo de Negocio
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Actividades Clave</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3a2 2 0 112 0v2M9 5a2 2 0 112 0v2" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Actividades Clave
                            </h2>
                            <p class="text-indigo-100 mt-1">Identifica las actividades más importantes para operar tu modelo de negocio</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
         <!-- Menú de navegación entre secciones -->
        <MenuNavegacion :proyecto_id="proyecto_id" :proyectoactual="proyectoactual" :actual="actual" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50 py-8">
            <div class="max-w-4xl mx-auto px-4">
                <!-- Panel educativo -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-indigo-500">
                    <div class="flex items-center mb-4">
                        <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3a2 2 0 112 0v2M9 5a2 2 0 112 0v2" />
                        </svg>
                        <h3 class="text-xl font-bold text-indigo-800">¿Qué son las Actividades Clave?</h3>
                    </div>
                    <p class="text-gray-700 mb-2">Son las acciones más importantes que debe realizar tu empresa para que su modelo de negocio funcione. Incluyen producción, resolución de problemas, gestión de plataformas, etc.</p>
                    <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
                        <li>¿Qué actividades son esenciales para tu propuesta de valor?</li>
                        <li>¿Cuáles son imprescindibles para llegar a tus clientes?</li>
                        <li>¿Qué procesos internos no pueden faltar?</li>
                    </ul>
                </div>

                <!-- Mensajes -->
                <div v-if="successMessage" class="mb-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ successMessage }}
                    </div>
                </div>
                <div v-if="errorMessage" class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ errorMessage }}
                    </div>
                </div>

                <!-- Formulario moderno -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label class="block font-semibold text-indigo-700 mb-2">Actividades clave</label>
                            <textarea v-model="form.actividad_clave" class="modern-textarea" required placeholder="Describe aquí las actividades clave de tu negocio..."></textarea>
                        </div>

                        <!-- Proceso Productivo -->
                        <div class="mb-6">
                            <label class="block font-semibold text-indigo-700 mb-2">Proceso productivo</label>
                            <div class="p-4 bg-indigo-50 rounded-lg">
                                <button type="button" @click="openModalProcesoProductivo()" class="modern-btn-indigo mb-4">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Nuevo paso
                                </button>
                                <div v-if="procesoproductivo && procesoproductivo.length > 0" class="space-y-2">
                                    <div v-for="(item, index) in procesoproductivo" :key="item.id" class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-600">Paso {{ index + 1 }}:</span>
                                            <p class="text-gray-900">{{ item.paso }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button" @click="openModalProcesoProductivo(item)" class="text-indigo-600 hover:text-indigo-800 text-sm">Editar</button>
                                            <button type="button" @click="deleteProcesoProductivo(item.id)" class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 text-sm">No hay pasos registrados aún.</div>
                            </div>
                        </div>

                        <!-- Proceso Comercial -->
                        <div class="mb-6">
                            <label class="block font-semibold text-indigo-700 mb-2">Proceso comercial</label>
                            <div class="p-4 bg-indigo-50 rounded-lg">
                                <button type="button" @click="openModalProcesoComercial()" class="modern-btn-indigo mb-4">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Nuevo paso
                                </button>
                                <div v-if="procesocomercial && procesocomercial.length > 0" class="space-y-2">
                                    <div v-for="(item, index) in procesocomercial" :key="item.id" class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-600">Paso {{ index + 1 }}:</span>
                                            <p class="text-gray-900">{{ item.paso }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button" @click="openModalProcesoComercial(item)" class="text-indigo-600 hover:text-indigo-800 text-sm">Editar</button>
                                            <button type="button" @click="deleteProcesoComercial(item.id)" class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 text-sm">No hay pasos registrados aún.</div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-indigo-700 mb-2">Adjunta un archivo (max: 5mb)</label>
                            <input type="file" class="modern-input" @change="e => form.archivo = e.target.files[0]" />
                            <div v-if="props.actividadesclave?.archivo" class="mt-2">
                                <a class="text-indigo-600 hover:underline" :href="`/storage/${props.actividadesclave.archivo}`" target="_blank">Ver archivo adjunto</a>
                            </div>
                        </div>

                        <button type="submit" class="modern-btn-indigo mt-8">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ editando ? "Actualizar" : "Guardar" }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Proceso Productivo -->
        <div v-if="showModalProcesoProductivo" class="fixed inset-0 bg-gray-700 bg-opacity-60 flex items-center justify-center z-50">
            <div class="modal-anim w-full max-w-md mx-auto p-6 rounded-2xl shadow-2xl bg-white border-l-4 border-indigo-500">
                <h3 class="text-lg font-bold text-indigo-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3a2 2 0 112 0v2M9 5a2 2 0 112 0v2" />
                    </svg>
                    {{ editingProcesoProductivo ? 'Editar' : 'Nuevo' }} Paso - Proceso Productivo
                </h3>
                <form @submit.prevent="submitProcesoProductivo">
                    <div class="mb-4">
                        <label for="paso_productivo" class="block text-sm font-medium text-indigo-700">Descripción del paso</label>
                        <input id="paso_productivo" type="text" v-model="formmodalprocesoproductivo.paso" class="modern-input" required />
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="closeModalProcesoProductivo" class="modern-btn-gray">Cancelar</button>
                        <button type="submit" class="modern-btn-indigo">{{ editingProcesoProductivo ? 'Actualizar' : 'Guardar' }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Proceso Comercial -->
        <div v-if="showModalProcesoComercial" class="fixed inset-0 bg-gray-700 bg-opacity-60 flex items-center justify-center z-50">
            <div class="modal-anim w-full max-w-md mx-auto p-6 rounded-2xl shadow-2xl bg-white border-l-4 border-indigo-500">
                <h3 class="text-lg font-bold text-indigo-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3a2 2 0 112 0v2M9 5a2 2 0 112 0v2" />
                    </svg>
                    {{ editingProcesoComercial ? 'Editar' : 'Nuevo' }} Paso - Proceso Comercial
                </h3>
                <form @submit.prevent="submitProcesoComercial">
                    <div class="mb-4">
                        <label for="paso_comercial" class="block text-sm font-medium text-indigo-700">Descripción del paso</label>
                        <input id="paso_comercial" type="text" v-model="formmodalprocesocomercial.paso" class="modern-input" required />
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="closeModalProcesoComercial" class="modern-btn-gray">Cancelar</button>
                        <button type="submit" class="modern-btn-indigo">{{ editingProcesoComercial ? 'Actualizar' : 'Guardar' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.modern-textarea {
    width: 100%;
    border-radius: 0.75rem;
    border: 1.5px solid #818cf8;
    background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);
    padding: 1rem;
    font-size: 1rem;
    color: #312e81;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px 0 rgba(129,140,248,0.08);
    resize: vertical;
}
.modern-textarea:focus {
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 2px #a5b4fc;
}
.modern-input {
    width: 100%;
    border-radius: 0.75rem;
    border: 1.5px solid #818cf8;
    background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);
    padding: 0.75rem 1rem;
    font-size: 1rem;
    color: #312e81;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px 0 rgba(129,140,248,0.08);
}
.modern-input:focus {
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 2px #a5b4fc;
}
.modern-btn-indigo {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(90deg, #818cf8 0%, #6366f1 100%);
    color: #fff;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    box-shadow: 0 2px 8px 0 rgba(99,102,241,0.10);
    border: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.modern-btn-indigo:hover {
    background: linear-gradient(90deg, #6366f1 0%, #818cf8 100%);
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
.modal-anim {
    animation: modalIn 0.25s cubic-bezier(0.4,0,0.2,1);
}
@keyframes modalIn {
    from { opacity: 0; transform: translateY(40px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
@media (max-width: 640px) {
    .modern-btn-indigo, .modern-btn-gray {
        width: 100%;
        justify-content: center;
    }
    .p-8 {
        padding: 1.25rem !important;
    }
}
</style>
