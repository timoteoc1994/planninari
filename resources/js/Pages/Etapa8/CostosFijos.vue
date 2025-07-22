<!-- resources/js/Pages/Etapa8/CostosFijos.vue -->
<template>
  <AppLayout title="Costos Fijos Mensuales">
    <template #header>
      <h2 class="text-center text-2xl font-bold">Costos Fijos Mensuales</h2>
    </template>

    <div class="py-8 max-w-4xl mx-auto space-y-6">
      <!-- Buscador + Nuevo -->
      <div class="flex items-center gap-4">
        <input
          v-model="search"
          type="text"
          placeholder="Buscar por concepto..."
          class="border w-full rounded p-2 focus:outline-none focus:ring"
        />
        <button
          @click="openModal()"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
        >
          + Nuevo costo
        </button>
      </div>

      <!-- Tabla de Costos Fijos -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse bg-white rounded shadow">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 text-left">Concepto</th>
              <th class="px-4 py-2 text-right">Valor (USD)</th>
              <th class="px-4 py-2 text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="c in filteredCostos"
              :key="c.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-2">{{ c.concepto }}</td>
              <td class="px-4 py-2 text-right">
                ${{ Number(c.valor).toFixed(2) }}
              </td>
              <td class="px-4 py-2 space-x-2">
                <button
                  @click="openModal(c)"
                  class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm"
                >Editar</button>
                <button
                  @click="deleteCosto(c)"
                  class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"
                >Eliminar</button>
              </td>
            </tr>
            <tr v-if="!filteredCostos.length">
              <td colspan="3" class="text-center py-8 text-gray-500">
                No hay costos fijos.
              </td>
            </tr>

            <!-- TOTAL SALARIOS -->
            <tr class="border-t font-semibold bg-gray-50">
              <td class="px-4 py-2">TOTAL SALARIOS</td>
              <td class="px-4 py-2 text-right">
                ${{ totalSalariosNum.toFixed(2) }}
              </td>
              <td></td>
            </tr>
            <!-- TOTAL COSTOS FIJOS MENSUALES (salarios + otros) -->
            <tr class="border-t font-semibold bg-gray-200">
              <td class="px-4 py-2">TOTAL COSTOS FIJOS MENSUALES</td>
              <td class="px-4 py-2 text-right">
                ${{ (totalFijos + totalSalariosNum).toFixed(2) }}
              </td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Crear/Editar -->
    <div
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
      @click.self="closeModal"
    >
      <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4 text-center">
          {{ editing ? 'Editar costo fijo' : 'Nuevo costo fijo' }}
        </h3>
        <form @submit.prevent="editing ? submitEdit() : submitCreate()" class="space-y-4">
          <div>
            <label class="block text-sm mb-1">Concepto *</label>
            <input
              v-model="form.concepto"
              type="text"
              required
              class="w-full border rounded p-2"
            />
          </div>
          <div>
            <label class="block text-sm mb-1">Valor (USD) *</label>
            <input
              v-model.number="form.valor"
              type="number"
              step="0.01"
              required
              class="w-full border rounded p-2 text-right"
            />
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
              {{ editing
                ? form.processing ? 'Actualizando…' : 'Actualizar'
                : form.processing ? 'Guardando…' : 'Guardar' }}
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
  proyecto_id:    Number,
  costosFijos:    Array,
  totalVariables: [Number, String],
  totalSalarios:  [Number, String],
})

const search = ref('')

const filteredCostos = computed(() => {
  const term = search.value.toLowerCase().trim()
  return props.costosFijos.filter(c =>
    c.concepto.toLowerCase().includes(term)
  )
})

const totalFijos = computed(() =>
  props.costosFijos.reduce((sum, c) => sum + parseFloat(c.valor), 0)
)

const totalSalariosNum = computed(() =>
  Number(props.totalSalarios) || 0
)

const form      = useForm({ concepto: '', valor: '' })
const showModal = ref(false)
const editing   = ref(false)
const editId    = ref(null)

function openModal(c = null) {
  form.reset(); form.clearErrors()
  if (c) {
    editing.value = true
    editId.value  = c.id
    form.concepto = c.concepto
    form.valor    = Number(c.valor)
  } else {
    editing.value = false
    editId.value  = null
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

function submitCreate() {
  form.post(
    route('costos-fijos.store', { id: props.proyecto_id }),
    {
      onSuccess: closeModal,
      onFinish: () => router.reload({ only: ['costosFijos'] }),
    }
  )
}

function submitEdit() {
  form.put(
    route('costos-fijos.update', {
      id: props.proyecto_id,
      costo_fijo: editId.value,
    }),
    {
      onSuccess: closeModal,
      onFinish: () => router.reload({ only: ['costosFijos'] }),
    }
  )
}

function deleteCosto(c) {
  Swal.fire({
    title: '¿Eliminar costo fijo?',
    text: c.concepto,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e3342f',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (result.isConfirmed) {
      router.delete(
        route('costos-fijos.destroy', {
          id: props.proyecto_id,
          costo_fijo: c.id,
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
