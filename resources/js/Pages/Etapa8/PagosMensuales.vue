<template>
  <AppLayout title="Pagos Mensuales de Deudas">
    <template #header>
      <h2 class="text-center text-2xl font-bold">Pagos mensuales de deudas (Negocio y personal)</h2>
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
          Crear
        </button>
      </div>

      <!-- Tabla de Pagos -->
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
              v-for="p in filteredPagos"
              :key="p.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-2">{{ p.concepto }}</td>
              <td class="px-4 py-2 text-right">
                ${{ Number(p.valor || 0).toFixed(2) }}
              </td>
              <td class="px-4 py-2 space-x-2">
                <button
                  @click="openModal(p)"
                  class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm"
                >
                  Editar
                </button>
                <button
                  @click="deletePago(p)"
                  class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"
                >
                  Eliminar
                </button>
              </td>
            </tr>
            <tr v-if="!filteredPagos.length">
              <td colspan="3" class="text-center py-8 text-gray-500">
                No hay pagos mensuales.
              </td>
            </tr>

            <!-- Totales parciales -->
            <tr class="border-t font-semibold bg-gray-50">
              <td class="px-4 py-2">TOTAL PAGOS DEUDAS</td>
              <td class="px-4 py-2 text-right">
                ${{ totalPagos.toFixed(2) }}
              </td>
              <td></td>
            </tr>
            <tr class="font-semibold bg-gray-50">
              <td class="px-4 py-2">TOTAL COSTOS FIJOS MENSUALES Y DEUDAS</td>
              <td class="px-4 py-2 text-right">
                ${{ totalCostosFijosDeudas.toFixed(2) }}
              </td>
              <td></td>
            </tr>
            <tr class="bg-gray-50 font-semibold">
              <td class="px-4 py-2">COSTOS VARIABLES</td>
              <td class="px-4 py-2 text-right">
                ${{ totalVariablesNum.toFixed(2) }}
              </td>
              <td></td>
            </tr>

            <!-- Total General -->
            <tr class="border-t font-semibold bg-gray-200">
              <td class="px-4 py-2">TOTAL GENERAL</td>
              <td class="px-4 py-2 text-right">
                ${{ totalGeneral.toFixed(2) }}
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
          {{ editing ? 'Editar pago' : 'Nuevo pago' }}
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
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              {{ editing
                ? (form.processing ? 'Actualizando…' : 'Actualizar')
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
  proyecto_id:    Number,
  pagos:          { type: Array, default: () => [] },
  totalFijos:     { type: [Number, String], default: 0 },
  totalVariables: { type: [Number, String], default: 0 },
  totalSalarios:  { type: [Number, String], default: 0 },
})

const search = ref('')

// Filtrar pagos por concepto
const filteredPagos = computed(() => {
  const term = search.value.toLowerCase().trim()
  return (props.pagos || []).filter(p =>
    p.concepto && p.concepto.toLowerCase().includes(term)
  )
})

// Sumar pagos
const totalPagos = computed(() =>
  (props.pagos || []).reduce((sum, p) => sum + (parseFloat(p.valor) || 0), 0)
)

// Convertir props a números
const fijos = computed(() => Number(props.totalFijos) || 0)
const salarios = computed(() => Number(props.totalSalarios) || 0)
const totalVariablesNum = computed(() => Number(props.totalVariables) || 0)

// Totales intermedios
const totalCostosFijosMensuales = computed(() => fijos.value + salarios.value)
const totalCostosFijosDeudas = computed(() =>
  totalCostosFijosMensuales.value + totalPagos.value
)

// **Total General** = fijos + salarios + préstamos + variables
const totalGeneral = computed(() =>
  fijos.value + salarios.value + totalPagos.value + totalVariablesNum.value
)

// Formulario y modal
const form      = useForm({ concepto: '', valor: '' })
const showModal = ref(false)
const editing   = ref(false)
const editId    = ref(null)

function openModal(p = null) {
  form.reset()
  form.clearErrors()
  if (p) {
    editing.value = true
    editId.value  = p.id
    form.concepto = p.concepto || ''
    form.valor    = Number(p.valor) || 0
  } else {
    editing.value = false
    editId.value  = null
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  form.reset()
  form.clearErrors()
  editing.value = false
  editId.value  = null
}

function submitCreate() {
  form.post(
    route('pagos-mensuales.store', { id: props.proyecto_id }),
    {
      onSuccess: closeModal,
      onFinish: () => router.reload({ only: ['pagos'] }),
    }
  )
}

function submitEdit() {
  form.put(
    route('pagos-mensuales.update', {
      id: props.proyecto_id,
      pago_mensual: editId.value,
    }),
    {
      onSuccess: closeModal,
      onFinish: () => router.reload({ only: ['pagos'] }),
    }
  )
}

function deletePago(p) {
  Swal.fire({
    title: '¿Eliminar pago?',
    text: p.concepto || 'Pago sin nombre',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e3342f',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (result.isConfirmed) {
      router.delete(
        route('pagos-mensuales.destroy', {
          id: props.proyecto_id,
          pago_mensual: p.id,
        }),
        { onSuccess: () => Swal.fire('Eliminado', '', 'success') }
      )
    }
  })
}

// Cerrar modal con ESC
document.addEventListener('keydown', e => {
  if (e.key === 'Escape' && showModal.value) closeModal()
})
</script>

<style scoped>
th, td {
  text-align: center;
}
</style>
