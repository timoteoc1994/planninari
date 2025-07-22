<template>
  <AppLayout title="Punto de Equilibrio (Dinero)">
    <template #header>
      <h2 class="text-center text-2xl font-bold">Cálculo de Punto de Equilibrio</h2>
    </template>

    <div class="py-8 max-w-4xl mx-auto space-y-8">

      <!-- 1) Margen de contribución -->
      <section class="space-y-2">
        <h3 class="font-semibold">1. % Margen de Contribución Promedio</h3>
        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded">
          <div>Total Margen de Contribución:</div>
          <div class="text-right">${{ totalMarginNum.toFixed(2) }}</div>
          <div>Total Precios de Venta:</div>
          <div class="text-right">${{ totalSalesNum.toFixed(2) }}</div>
          <div class="font-semibold">% Margen:</div>
          <div class="text-right font-semibold">{{ marginPctNum.toFixed(0) }}%</div>
        </div>
      </section>

      <!-- 2) Punto de Equilibrio -->
      <section class="space-y-2">
        <h3 class="font-semibold">2. Punto de Equilibrio</h3>
        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded">
          <div>Costos Fijos Mensuales + Salarios:</div>
          <div class="text-right">${{ contribMarginNum.toFixed(2) }}</div>
          <div>% Margen de Contribución:</div>
          <div class="text-right font-semibold">{{ marginPctNum.toFixed(0) }}%</div>

          <div>Punto de Equilibrio Mensual:</div>
          <div class="text-right font-semibold">${{ breakEvenMonthlyNum.toFixed(2) }}</div>

          <div>Días por semana:</div>
          <div class="text-right">
            <span v-if="config && config.dias_por_semana">
              {{ config.dias_por_semana }}
            </span>
            <span v-else class="text-red-600">Seleccione días</span>
          </div>

          <div>Punto de Equilibrio Diario:</div>
          <div class="text-right font-semibold">
            <span v-if="breakEvenDailyNum !== null">
              ${{ breakEvenDailyNum.toFixed(2) }}
            </span>
            <span v-else>—</span>
          </div>

          <div>Punto de Equilibrio Semanal:</div>
          <div class="text-right font-semibold">
            ${{ breakEvenWeeklyNum.toFixed(2) }}
          </div>
        </div>
      </section>

      <!-- 3) Análisis -->
      <section class="space-y-2">
        <h3 class="font-semibold">3. Análisis de Punto de Equilibrio</h3>
        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded">
          <div>Ventas mensuales promedio:</div>
          <div class="text-right">${{ breakEvenMonthlyNum.toFixed(2) }}</div>

          <div>- Costos Variables:</div>
          <div class="text-right">${{ variableCost.toFixed(2) }}</div>

          <div>= Margen de Contribución:</div>
          <div class="text-right">${{ contribMarginNum.toFixed(2) }}</div>

          <div>(-) Costos Fijos Mensuales:</div>
          <div class="text-right">${{ contribMarginNum.toFixed(2) }}</div>

          <div class="font-semibold">Utilidad Neta:</div>
          <div class="text-right font-semibold">
            ${{ utilidadNeta.toFixed(2) }}
          </div>
        </div>
      </section>

      <!-- Botón modal -->
      <div class="text-center">
        <button
          @click="openModal()"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >Configurar días por semana</button>
      </div>

      <!-- Modal configuración -->
      <div
        v-if="showModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click.self="closeModal"
      >
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
          <h3 class="text-lg font-semibold mb-4 text-center">Días por Semana</h3>
          <form @submit.prevent="submitUpdate">
            <div class="mb-4">
              <label class="block text-sm mb-1">Días por semana:</label>
              <input
                v-model.number="form.dias_por_semana"
                type="number"
                min="1"
                max="7"
                required
                class="w-full border rounded p-2 text-right"
              />
            </div>
            <div class="flex justify-end space-x-2">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
              >Cancelar</button>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
              >
                {{ form.processing ? 'Guardando…' : 'Guardar' }}
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  proyecto_id:      Number,
  config:           Object,
  totalFijos:       [Number, String],
  totalVariables:   [Number, String],
  totalSales:       [Number, String],
  totalMargin:      [Number, String],
  totalSalarios:    [Number, String],
  marginPct:        [Number, String],
  breakEvenMonthly: [Number, String],
  breakEvenDaily:   [Number, String],
  breakEvenWeekly:  [Number, String],
})

const totalFijosNum       = computed(() => Number(props.totalFijos) || 0)
const totalSalariosNum    = computed(() => Number(props.totalSalarios) || 0)
const totalSalesNum       = computed(() => Number(props.totalSales) || 0)
const totalMarginNum      = computed(() => Number(props.totalMargin) || 0)
const marginPctNum        = computed(() => Number(props.marginPct) || 0)
const breakEvenMonthlyNum = computed(() => Number(props.breakEvenMonthly) || 0)
const breakEvenDailyNum   = computed(() => {
  const v = props.breakEvenDaily
  return v != null ? Number(v) : null
})
const breakEvenWeeklyNum  = computed(() => {
  const v = props.breakEvenWeekly
  return v != null ? Number(v) : null
})

// Margen de Contribución (usado también como "Costos Fijos + Salarios")
const contribMarginNum = computed(() => totalFijosNum.value + totalSalariosNum.value)

// Costo variable = PuntoEquilibrioMensual * (1 - %Margen)
const variableCost = computed(() =>
  breakEvenMonthlyNum.value * (1 - marginPctNum.value / 100)
)

// Utilidad neta = Margen de Contribución − Costos Fijos Mensuales
const utilidadNeta = computed(() =>
  contribMarginNum.value - contribMarginNum.value
)

const showModal = ref(false)
const form      = useForm({ dias_por_semana: props.config?.dias_por_semana ?? '' })

function openModal() {
  form.reset()
  form.clearErrors()
  form.dias_por_semana = props.config?.dias_por_semana ?? ''
  showModal.value = true
}
function closeModal() {
  showModal.value = false
  form.reset()
  form.clearErrors()
}
function submitUpdate() {
  form.put(
    route('punto-equilibrio.update', {
      id: props.proyecto_id,
      punto_equilibrio: props.config.id
    }),
    {
      onSuccess: () => {
        closeModal()
        router.reload({ only: ['config','breakEvenMonthly','breakEvenDaily','breakEvenWeekly'] })
      }
    }
  )
}
</script>

<style scoped>
th, td { text-align: center; }
section h3 { margin-top: 1rem; }
</style>
