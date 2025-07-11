<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

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

// Opciones de recursos según origen
const recursoOptions = computed(() =>
  inversionForm.tipo_origen === 'disponible'
    ? props.disponibles
    : props.necesarios
)

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
    }
  })
}

// Borrar inversión
function remove(id) {
  if (confirm('¿Eliminar esta inversión?')) {
    inversionForm.delete(route('etapa7.inversiones.destroy', id))
  }
}
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
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Vista de tabla en desktop -->
        <div class="hidden lg:block overflow-x-auto">
          <table class="w-full table-auto">
            <thead class="bg-gray-50">
              <tr class="text-left">
                <th class="px-4 py-3 text-sm font-medium text-gray-900">Origen</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-900">Recurso</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-900">Cantidad</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-900">Precio Unit.</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-900">Clasificación</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-900">Vida útil</th>
                <th class="px-4 py-3 text-sm font-medium text-gray-900">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="inv in inversiones"
                :key="inv.id"
                class="hover:bg-gray-50"
              >
                <td class="px-4 py-3 text-sm text-gray-900">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="inv.tipo_origen === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                    {{ inv.tipo_origen }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                  <div class="font-medium">{{ inv.recurso.tipo_recurso }}</div>
                  <div class="text-gray-500">{{ inv.recurso.descripcion }}</div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ inv.cantidad }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${{ inv.precio_unitario }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ inv.clasificacion }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ inv.vida_util }} años</td>
                <td class="px-4 py-3 text-sm">
                  <div class="flex space-x-2">
                    <button
                      @click="openEdit(inv)"
                      class="text-blue-600 hover:text-blue-800 font-medium"
                    >
                      Editar
                    </button>
                    <button
                      @click="remove(inv.id)"
                      class="text-red-600 hover:text-red-800 font-medium"
                    >
                      Eliminar
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Vista de cards en mobile y tablet -->
        <div class="lg:hidden">
          <div class="divide-y divide-gray-200">
            <div
              v-for="inv in inversiones"
              :key="inv.id"
              class="p-4 hover:bg-gray-50"
            >
              <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="inv.tipo_origen === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                      {{ inv.tipo_origen }}
                    </span>
                  </div>
                  <h4 class="font-medium text-gray-900">{{ inv.recurso.tipo_recurso }}</h4>
                  <p class="text-sm text-gray-500">{{ inv.recurso.descripcion }}</p>
                </div>
                <div class="flex space-x-2 ml-4">
                  <button
                    @click="openEdit(inv)"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                  >
                    Editar
                  </button>
                  <button
                    @click="remove(inv.id)"
                    class="text-red-600 hover:text-red-800 text-sm font-medium"
                  >
                    Eliminar
                  </button>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span class="text-gray-500">Cantidad:</span>
                  <span class="ml-1 font-medium">{{ inv.cantidad }}</span>
                </div>
                <div>
                  <span class="text-gray-500">Precio Unit.:</span>
                  <span class="ml-1 font-medium">${{ inv.precio_unitario }}</span>
                </div>
                <div>
                  <span class="text-gray-500">Clasificación:</span>
                  <span class="ml-1 font-medium">{{ inv.clasificacion }}</span>
                </div>
                <div>
                  <span class="text-gray-500">Vida útil:</span>
                  <span class="ml-1 font-medium">{{ inv.vida_util }} años</span>
                </div>
              </div>
            </div>
          </div>
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