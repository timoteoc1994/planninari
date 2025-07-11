<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  proyecto_id: Number,
  disponibles: Array,
  necesarios: Array,
  inversiones: Array,
})

// Estado modal y edición
const showModal = ref(false)
const editId    = ref(null)

// Formulario de inversión
const inversionForm = useForm({
  proyecto_id: props.proyecto_id,
  tipo_origen: 'disponible',
  recurso_id:  null,
  cantidad:   '',
  precio_unitario: '',
  clasificacion:   '',
  vida_util:       '',
})

// Obtener IDs de recursos ya utilizados (excluyendo el que se está editando)
const recursosUtilizados = computed(() => {
  return props.inversiones
    .filter(inv => editId.value === null || inv.id !== editId.value)
    .filter(inv => inv.recurso_id) // Solo considerar inversiones con recurso_id válido
    .map(inv => inv.recurso_id)
})

// Opciones de recursos según origen, filtradas por recursos no utilizados
const recursoOptions = computed(() => {
  const recursos = inversionForm.tipo_origen === 'disponible'
    ? props.disponibles
    : props.necesarios
  
  // Filtrar recursos que no estén ya utilizados
  return recursos.filter(recurso => !recursosUtilizados.value.includes(recurso.id))
})

// Abrir modal para nueva inversión
function openNew() {
  editId.value = null
  inversionForm.reset()
  inversionForm.tipo_origen = 'disponible'
  showModal.value = true
}

// Cargar datos al editar
function openEdit(item) {
  editId.value = item.id
  inversionForm.tipo_origen     = item.tipo_origen
  inversionForm.recurso_id      = item.recurso_id
  inversionForm.cantidad        = item.cantidad
  inversionForm.precio_unitario = item.precio_unitario
  inversionForm.clasificacion   = item.clasificacion
  inversionForm.vida_util       = item.vida_util
  showModal.value = true
}

// Enviar formulario (POST o PUT)
function submit() {
  const method = editId.value ? 'put' : 'post'
  const url = editId.value
    ? route('etapa7.inversiones.update', editId.value)
    : route('etapa7.inversiones.store')

  inversionForm[method](url, {
    onSuccess: () => {
      showModal.value = false
      inversionForm.reset()
      Swal.fire({
        icon: 'success',
        title: editId.value ? 'Inversión actualizada correctamente' : 'Inversión registrada correctamente',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Ocurrió un problema al guardar la inversión.',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
      })
    }
  })
}

// Borrar inversión
async function remove(id) {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: 'Esta acción eliminará la inversión.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  })

  if (result.isConfirmed) {
    inversionForm.delete(route('etapa7.inversiones.destroy', id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Inversión eliminada correctamente',
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true
        })
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'No se pudo eliminar la inversión.',
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        })
      }
    })
  }
}

const inversionesDisponibles = computed(() =>
  props.inversiones.filter(inv => inv.tipo_origen === 'disponible')
)

const inversionesNecesarias = computed(() =>
  props.inversiones.filter(inv => inv.tipo_origen === 'necesario')
)

</script>

<template>
  <AppLayout title="Detalle de Inversión">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800">Detalle de Inversión</h2>
    </template>

    <div class="py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">

      <!-- Header con botón -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800">Inversiones Registradas</h3>
        <button
          @click="openNew"
          class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-sm transition-colors self-start sm:self-auto"
        >
          <span class="hidden sm:inline">Nueva Inversión</span>
          <span class="sm:hidden">+ Nuevo</span>
        </button>
      </div>

      <!-- Tabla responsive -->
      <!-- Tabla: Recursos Disponibles -->
<div class="bg-white rounded-lg shadow overflow-hidden">
  <h3 class="text-base font-semibold bg-green-50 px-4 py-3 text-green-800">Recursos Disponibles</h3>
  <div class="w-full overflow-x-auto">
    <table class="min-w-full table-auto">
      <thead class="bg-gray-50">
        <tr class="text-left">
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Recurso</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Cantidad</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Precio Unit.</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Clasificación</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Vida útil</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <tr
          v-for="inv in inversionesDisponibles"
          :key="inv.id"
          class="hover:bg-gray-50"
        >
          <td class="px-4 py-3">
            <div class="font-medium">{{ inv.recurso?.tipo_recurso || 'Recurso no encontrado' }}</div>
            <div class="text-gray-500">{{ inv.recurso?.descripcion || 'Sin descripción' }}</div>
          </td>
          <td class="px-4 py-3">{{ inv.cantidad }}</td>
          <td class="px-4 py-3">${{ inv.precio_unitario }}</td>
          <td class="px-4 py-3">{{ inv.clasificacion }}</td>
          <td class="px-4 py-3">{{ inv.vida_util }} años</td>
          <td class="px-4 py-3">
            <div class="flex space-x-2">
              <button @click="openEdit(inv)" class="text-blue-600 hover:text-blue-800 font-medium">Editar</button>
              <button @click="remove(inv.id)" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Tabla: Recursos Necesarios -->
<div class="bg-white rounded-lg shadow overflow-hidden mt-6">
  <h3 class="text-base font-semibold bg-blue-50 px-4 py-3 text-blue-800">Recursos Necesarios</h3>
  <div class="w-full overflow-x-auto">
    <table class="min-w-full table-auto">
      <thead class="bg-gray-50">
        <tr class="text-left">
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Recurso</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Cantidad</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Precio Unit.</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Clasificación</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Vida útil</th>
          <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <tr
          v-for="inv in inversionesNecesarias"
          :key="inv.id"
          class="hover:bg-gray-50"
        >
          <td class="px-4 py-3">
            <div class="font-medium">{{ inv.recurso?.tipo_recurso || 'Recurso no encontrado' }}</div>
            <div class="text-gray-500">{{ inv.recurso?.descripcion || 'Sin descripción' }}</div>
          </td>
          <td class="px-4 py-3">{{ inv.cantidad }}</td>
          <td class="px-4 py-3">${{ inv.precio_unitario }}</td>
          <td class="px-4 py-3">{{ inv.clasificacion }}</td>
          <td class="px-4 py-3">{{ inv.vida_util }} años</td>
          <td class="px-4 py-3">
            <div class="flex space-x-2">
              <button @click="openEdit(inv)" class="text-blue-600 hover:text-blue-800 font-medium">Editar</button>
              <button @click="remove(inv.id)" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>


      <!-- Modal Inversión -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
      >
        <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h4 class="text-lg font-semibold text-gray-900">
                {{ editId ? 'Editar' : 'Nueva' }} Inversión
              </h4>
              <button
                @click="showModal = false"
                class="text-gray-400 hover:text-gray-600"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Disponibilidad del recurso
                </label>
                <select
                  v-model="inversionForm.tipo_origen"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
                  <option value="disponible">Recursos Disponibles</option>
                  <option value="necesario">Recursos Necesarios</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Seleccione recurso
                </label>
                <select
                  v-model="inversionForm.recurso_id"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
                  <option :value="null" disabled>Seleccione...</option>
                  <option
                    v-for="r in recursoOptions"
                    :key="r.id"
                    :value="r.id"
                  >
                    {{ r.tipo_recurso }} – {{ r.descripcion }}
                  </option>
                </select>
                <!-- Mensaje informativo si no hay recursos disponibles -->
                <p v-if="recursoOptions.length === 0" class="text-sm text-gray-500 mt-1">
                  No hay recursos {{ inversionForm.tipo_origen === 'disponible' ? 'disponibles' : 'necesarios' }} sin utilizar.
                </p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Cantidad
                  </label>
                  <input
                    type="number"
                    v-model="inversionForm.cantidad"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Precio unitario
                  </label>
                  <input
                    type="number"
                    step="0.01"
                    v-model="inversionForm.precio_unitario"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Clasificación
                </label>
                <input
                  type="text"
                  v-model="inversionForm.clasificacion"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Vida útil (años)
                </label>
                <input
                  type="number"
                  v-model="inversionForm.vida_util"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
              </div>

              <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4">
                <button
                  @click="showModal = false"
                  type="button"
                  class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors"
                  :disabled="recursoOptions.length === 0"
                  :class="{ 'opacity-50 cursor-not-allowed': recursoOptions.length === 0 }"
                >
                  Guardar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<style scoped>
/* Estilos adicionales si son necesarios */
</style>