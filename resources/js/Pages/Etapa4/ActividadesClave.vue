<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
  actividadesclave: Object,
  procesocomercial: Array, // Cambiado a Array para múltiples pasos
  procesoproductivo: Array, // Cambiado a Array para múltiples pasos
  proyecto_id: Number,
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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Actividades Clave
                </h2>
            </div>
        </template>

        <div class="py-12">
            <!-- Mensaje de éxito -->
            <div v-if="successMessage" class="max-w-4xl mx-auto sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ successMessage }}
                </div>
            </div>
            
            <!-- Mensaje de error -->
            <div v-if="errorMessage" class="max-w-4xl mx-auto sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ errorMessage }}
                </div>
            </div>

            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Actividades clave</label>
                        <textarea
                            v-model="form.actividad_clave"
                            class="w-full border rounded px-3 py-2"
                            required
                        ></textarea>
                    </div>
                    
                    <!-- Proceso Productivo -->
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Proceso productivo</label>
                        <div class="p-4 bg-slate-100 rounded-lg">
                            <button 
                                type="button"
                                @click="openModalProcesoProductivo()"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4"
                            >
                                Nuevo paso
                            </button>
                            
                            <!-- Lista de pasos del proceso productivo -->

                            <div v-if="procesoproductivo && procesoproductivo.length > 0" class="space-y-2">
                                <div 
                                    v-for="(item, index) in procesoproductivo" 
                                    :key="item.id"
                                    class="flex items-center justify-between p-3 bg-white rounded border"
                                >
                                    <div class="flex-1">
                                        <span class="text-sm text-gray-600">Paso {{ index + 1 }}:</span>
                                        <p class="text-gray-900">{{ item.paso }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            type="button"
                                            @click="openModalProcesoProductivo(item)"
                                            class="text-blue-600 hover:text-blue-800 text-sm"
                                        >
                                            Editar
                                        </button>
                                        <button
                                            type="button"
                                            @click="deleteProcesoProductivo(item.id)"
                                            class="text-red-600 hover:text-red-800 text-sm"
                                        >
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 text-sm">
                                No hay pasos registrados aún.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Proceso Comercial -->
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Proceso comercial</label>
                        <div class="p-4 bg-slate-100 rounded-lg">
                            <button 
                                type="button"
                                @click="openModalProcesoComercial()"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4"
                            >
                                Nuevo paso
                            </button>
                            
                            <!-- Lista de pasos del proceso comercial -->
                            <div v-if="procesocomercial && procesocomercial.length > 0" class="space-y-2">
                                <div 
                                    v-for="(item, index) in procesocomercial" 
                                    :key="item.id"
                                    class="flex items-center justify-between p-3 bg-white rounded border"
                                >
                                    <div class="flex-1">
                                        <span class="text-sm text-gray-600">Paso {{ index + 1 }}:</span>
                                        <p class="text-gray-900">{{ item.paso }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            type="button"
                                            @click="openModalProcesoComercial(item)"
                                            class="text-blue-600 hover:text-blue-800 text-sm"
                                        >
                                            Editar
                                        </button>
                                        <button
                                            type="button"
                                            @click="deleteProcesoComercial(item.id)"
                                            class="text-red-600 hover:text-red-800 text-sm"
                                        >
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 text-sm">
                                No hay pasos registrados aún.
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold">Adjunte un archivo (max: 5mb)</label>
                        <input type="file" class="input" @change="e => form.archivo = e.target.files[0]"  />
                        <div v-if="props.actividadesclave?.archivo" class="mt-2">
                            <a
                                class="text-blue-600 hover:underline"
                                :href="`/storage/${props.actividadesclave.archivo}`"
                                target="_blank"
                            >
                                Ver archivo adjunto
                            </a>
                        </div>
                    </div>
                    
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-6"
                    >
                        {{ editando ? "Actualizar" : "Guardar" }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Modal Proceso Productivo -->
        <div v-if="showModalProcesoProductivo" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ editingProcesoProductivo ? 'Editar' : 'Nuevo' }} Paso - Proceso Productivo
                    </h3>
                    <form @submit.prevent="submitProcesoProductivo">
                        <div class="mb-4">
                            <label for="paso_productivo" class="block text-sm font-medium text-gray-700">
                                Descripción del paso
                            </label>
                            <input
                                id="paso_productivo"
                                type="text"
                                v-model="formmodalprocesoproductivo.paso"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required
                            />
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="closeModalProcesoProductivo"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                {{ editingProcesoProductivo ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Proceso Comercial -->
        <div v-if="showModalProcesoComercial" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ editingProcesoComercial ? 'Editar' : 'Nuevo' }} Paso - Proceso Comercial
                    </h3>
                    <form @submit.prevent="submitProcesoComercial">
                        <div class="mb-4">
                            <label for="paso_comercial" class="block text-sm font-medium text-gray-700">
                                Descripción del paso
                            </label>
                            <input
                                id="paso_comercial"
                                type="text"
                                v-model="formmodalprocesocomercial.paso"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required
                            />
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="closeModalProcesoComercial"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                {{ editingProcesoComercial ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>