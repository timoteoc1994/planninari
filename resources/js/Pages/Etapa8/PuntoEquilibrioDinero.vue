<template>
  <AppLayout :title="'Punto de Equilibrio Dinero'">
    <div class="max-w-3xl mx-auto py-8 space-y-6">
      <h2 class="text-2xl font-bold text-center">Cálculo de Punto de Equilibrio</h2>

      <form @submit.prevent="submit" class="space-y-4 bg-white p-6 rounded shadow">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm">Total precios de ventas</label>
            <input
              v-model.number="form.total_ventas"
              type="number"
              step="0.01"
              class="w-full border rounded p-2 text-right"
            />
          </div>
          <div>
            <label class="block text-sm">Días trabajados por semana</label>
            <input
              v-model.number="form.dias_semana"
              type="number"
              class="w-full border rounded p-2 text-right"
            />
          </div>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          {{ form.processing ? 'Guardando…' : 'Guardar Cálculo' }}
        </button>
      </form>

      <div v-if="hasInputs" class="bg-white p-6 rounded shadow">
        <table class="w-full text-left">
          <tbody>
            <tr>
              <td>Total margen de contribución</td>
              <td class="text-right">${{ margenContribucion.toFixed(2) }}</td>
            </tr>
            <tr>
              <td>% Margen de contribución</td>
              <td class="text-right">{{ porcentajeMargen.toFixed(2) }}%</td>
            </tr>
            <tr class="border-t font-semibold">
              <td>Punto de equilibrio mensual</td>
              <td class="text-right">${{ puntoEquilibrioMensual.toFixed(2) }}</td>
            </tr>
            <tr>
              <td>Punto de equilibrio semanal</td>
              <td class="text-right">${{ puntoEquilibrioSemanal.toFixed(2) }}</td>
            </tr>
            <tr>
              <td>Punto de equilibrio diario</td>
              <td class="text-right">${{ puntoEquilibrioDiario.toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  proyecto_id:            Number,
  registro:               Object,
  totalCostosFijosDeudas: Number,
  totalVariables:         Number,
})

const form = useForm({
  total_ventas:              props.registro?.total_ventas || 0,
  total_costos_variables:    props.totalVariables,
  total_costos_fijos_deudas: props.totalCostosFijosDeudas,
  dias_semana:               props.registro?.dias_semana || 6,
})

const hasInputs = computed(() =>
  form.total_ventas > 0 &&
  form.dias_semana > 0
)

// 1) Margen de contribución = Venta Total − Costos Variables
const margenContribucion = computed(() =>
  form.total_ventas - form.total_costos_variables
)

// 2) % Margen = (Margen / Venta Total) × 100
const porcentajeMargen = computed(() =>
  form.total_ventas > 0
    ? (margenContribucion.value / form.total_ventas) * 100
    : 0
)

// 3) Punto equilibrio mensual = Costos fijos / ( %Margen/100 )
const puntoEquilibrioMensual = computed(() =>
  porcentajeMargen.value
    ? form.total_costos_fijos_deudas / (porcentajeMargen.value / 100)
    : 0
)

// 4) Semanal y diario
const puntoEquilibrioSemanal = computed(() =>
  puntoEquilibrioMensual.value * (form.dias_semana / (52 / 12))
)
const puntoEquilibrioDiario = computed(() =>
  puntoEquilibrioSemanal.value / form.dias_semana
)

function submit() {
  form.post(
    route('equilibrio.store', props.proyecto_id),
    { preserveScroll: true }
  )
}
</script>
