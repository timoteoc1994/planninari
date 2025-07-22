<!-- resources/js/Pages/Etapa8/Empleados.vue -->
<template>
  <AppLayout title="Empleados">
    <template #header>
      <h2 class="text-center text-2xl font-bold">Empleados y Salarios</h2>
    </template>

    <div class="py-8 max-w-4xl mx-auto space-y-6">
      <!-- Buscador + Nuevo -->
      <div class="flex items-center gap-4">
        <input
          v-model="search"
          type="text"
          placeholder="Buscar..."
          class="border w-full rounded p-2 focus:outline-none focus:ring"
        />
        <button
          @click="openCreateModal"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
        >
          Crear
        </button>
      </div>

      <!-- Tabla 1: Empleados -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse bg-white rounded shadow mb-8">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 text-left">Nombre</th>
              <th class="px-4 py-2 text-left">Salario Nominal</th>
              <th class="px-4 py-2 text-left">% Beneficios</th>
              <th class="px-4 py-2 text-left">Salario Real</th>
              <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="e in filteredEmpleados"
              :key="e.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-2">{{ e.nombre }}</td>
              <td class="px-4 py-2">${{ Number(e.salario_nominal).toFixed(2) }}</td>
              <td class="px-4 py-2">{{ Number(e.beneficios_pct).toFixed(2) }}%</td>
              <td class="px-4 py-2">${{ Number(e.salario_real).toFixed(2) }}</td>
              <td class="px-4 py-2 space-x-2">
                <button
                  @click="openEditModal(e)"
                  class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm"
                >Editar</button>
                <button
                  @click="deleteEmpleado(e)"
                  class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"
                >Eliminar</button>
              </td>
            </tr>
            <tr v-if="!filteredEmpleados.length">
              <td colspan="5" class="text-center py-8 text-gray-500">
                No hay empleados.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Tabla 2: Empresarios -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse bg-white rounded shadow">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 text-left">Nombre (Empresario)</th>
              <th class="px-4 py-2 text-left">Salario Nominal</th>
              <th class="px-4 py-2 text-left">% Beneficios</th>
              <th class="px-4 py-2 text-left">Salario Real</th>
              <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="e in filteredEmpresarios"
              :key="e.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-2">{{ e.nombre }}</td>
              <td class="px-4 py-2">${{ Number(e.salario_nominal).toFixed(2) }}</td>
              <td class="px-4 py-2">{{ Number(e.beneficios_pct).toFixed(2) }}%</td>
              <td class="px-4 py-2">${{ Number(e.salario_real).toFixed(2) }}</td>
              <td class="px-4 py-2 space-x-2">
                <button
                  @click="openEditModal(e)"
                  class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm"
                >Editar</button>
                <button
                  @click="deleteEmpleado(e)"
                  class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"
                >Eliminar</button>
              </td>
            </tr>
            <tr v-if="!filteredEmpresarios.length">
              <td colspan="5" class="text-center py-4 text-gray-500">
                No hay empresario definido.
              </td>
            </tr>
            <!-- TOTAL SALARIOS -->
            <tr class="border-t font-semibold bg-gray-50">
              <td colspan="3" class="px-4 py-2 text-center">TOTAL SALARIOS</td>
              <td class="px-4 py-2">${{ Number(totalSalarios).toFixed(2) }}</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Crear / Editar -->
    <div
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
      @click.self="closeModal"
    >
      <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4 text-center">
          {{ editing ? 'Editar Empleado' : 'Nuevo Empleado' }}
        </h3>
        <form @submit.prevent="editing ? submitEdit() : submitCreate()" class="space-y-4">
          <div>
            <label class="block text-sm mb-1">Nombre *</label>
            <input
              v-model="form.nombre"
              type="text"
              required
              class="w-full border rounded p-2"
            />
          </div>
          <div>
            <label class="block text-sm mb-1">Salario Nominal *</label>
            <input
              v-model="form.salario_nominal"
              type="number"
              step="0.01"
              required
              class="w-full border rounded p-2"
            />
          </div>
          <div>
            <label class="block text-sm mb-1">% Beneficios *</label>
            <input
              v-model="form.beneficios_pct"
              type="number"
              step="0.01"
              required
              class="w-full border rounded p-2"
            />
          </div>
     <div class="flex items-center space-x-2">
       <input
         type="checkbox"
         v-model="form.es_empresario"
         id="es_empresario"
         class="form-checkbox"
       />
       <label for="es_empresario" class="text-sm">Es empresario</label>
     </div>
          <div class="flex justify-end space-x-2 mt-4">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
            >Cancelar</button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              {{ editing ? (form.processing ? 'Actualizando…' : 'Actualizar') 
                         : (form.processing ? 'Guardando…' : 'Guardar') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  proyecto_id: { type: Number, required: true },
  empleados:   { type: Array,  required: true },
})

const search = ref('')

// Filtrar empleados (no empresario)
const filteredEmpleados = computed(() => {
  const term = search.value.toLowerCase().trim()
  return props.empleados
    .filter(e => !e.es_empresario)
    .filter(e => e.nombre.toLowerCase().includes(term))
})

// Filtrar empresarios (puede haber varios)
const filteredEmpresarios = computed(() => {
  const term = search.value.toLowerCase().trim()
  return props.empleados
    .filter(e => e.es_empresario)
    .filter(e => !term || e.nombre.toLowerCase().includes(term))
})

// Total de todos los salarios
const totalSalarios = computed(() => {
  return props.empleados
    .reduce((sum, e) => sum + parseFloat(e.salario_real), 0)
})

const form       = useForm({
  nombre:           '',
  salario_nominal:  '',
  beneficios_pct:   '',
  es_empresario:    false,
})
const showModal  = ref(false)
const editing    = ref(false)
const editId     = ref(null)

function openCreateModal() {
  form.reset(); form.clearErrors()
  editing.value = false
  editId.value  = null
  showModal.value = true
}

function openEditModal(e) {
  editing.value = true
  editId.value  = e.id
  form.reset(); form.clearErrors()
  form.nombre           = e.nombre
  form.salario_nominal  = e.salario_nominal
  form.beneficios_pct   = e.beneficios_pct
  form.es_empresario    = !!e.es_empresario
  showModal.value       = true
}

function closeModal() {
  showModal.value = false
  editing.value   = false
  editId.value    = null
  form.reset(); form.clearErrors()
}

function submitCreate() {
  form.post(
    route('empleados.store', { id: props.proyecto_id }),
    {
      onSuccess: closeModal,
      onFinish: () => router.reload({ only: ['empleados'] }),
    }
  )
}

function submitEdit() {
  form.put(
    route('empleados.update', {
      id: props.proyecto_id,
      empleado: editId.value,
    }),
    {
      onSuccess: closeModal,
      onFinish: () => router.reload({ only: ['empleados'] }),
    }
  )
}

function deleteEmpleado(e) {
  Swal.fire({
    title: '¿Eliminar empleado?',
    text: e.nombre,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e3342f',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (result.isConfirmed) {
      router.delete(
        route('empleados.destroy', {
          id: props.proyecto_id,
          empleado: e.id,
        }),
        { onSuccess: () => Swal.fire('Eliminado', '', 'success') }
      )
    }
  })
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape' && showModal.value) closeModal()
})
</script>

<style scoped>
th, td { text-align: center; }
</style>
