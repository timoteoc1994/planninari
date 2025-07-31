<template>
  <AppLayout title="Análisis Financiero - TIR y VAN">
    <template #header>
      <h2 class="text-2xl font-bold text-center">Análisis Financiero - TIR y VAN</h2>
    </template>

    <div class="max-w-5xl mx-auto py-8 space-y-6">
      <!-- Entrada TNA -->
      <div class="bg-blue-50 p-4 rounded-lg">
        <label class="block font-medium text-lg mb-2">TNA de Inversión Alternativa (%)</label>
        <input
          type="number"
          v-model.number="form.tna_inversion_alternativa"
          class="border p-2 rounded w-40"
          step="0.01"
          min="0"
        />
      </div>

      <!-- Resultados principales -->
      <div class="bg-white rounded-lg p-6 shadow space-y-4">
        <div class="grid sm:grid-cols-2 gap-6 text-center">
          <div>
            <p class="text-gray-600 font-medium">Valor Actual Neto (VAN):</p>
            <p class="text-2xl font-bold text-green-700">{{ formatCurrency(van) }}</p>
          </div>
          <div>
            <p class="text-gray-600 font-medium">Tasa Interna de Retorno (TIR Anual):</p>
            <p class="text-2xl font-bold text-blue-700">{{ tirAnual }}%</p>
          </div>
        </div>
        <p class="text-center text-lg font-semibold mt-4" :class="conclusionClass">
          {{ conclusion }}
        </p>
      </div>

      <!-- Desglose detallado -->
      <div class="bg-yellow-50 p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-bold mb-4">Desglose del Flujo de Fondos</h3>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4 text-sm">
          <div><strong>Nombre del Proyecto:</strong> {{ proyecto_nombre }}</div>
          <div><strong>Periodicidad:</strong> Mensual</div>
          <div><strong>Períodos:</strong> 12</div>
          <div><strong>TNA:</strong> {{ formatPercent(form.tna_inversion_alternativa) }}</div>
          <div><strong>TEA (mercado):</strong> {{ tea.toFixed(3) }}%</div>
          <div><strong>Tasa Mensual (mercado):</strong> {{ (tasaMensual * 100).toFixed(3) }}%</div>
        </div>

        <table class="min-w-full border-collapse border border-gray-300 text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="border p-2">Período</th>
              <th class="border p-2">Flujo de Fondos</th>
              <th class="border p-2">Comentario</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(val, i) in flujoFondos" :key="i">
              <td class="border p-2 text-center">{{ i }}</td>
              <td class="border p-2 text-right">{{ formatCurrency(val) }}</td>
              <td class="border p-2 text-xs">
                <template v-if="i === 0">Inversión inicial</template>
                <template v-else-if="i === 1">Inicio de recuperación del capital</template>
                <template v-else-if="i === 4">
                  Me conviene invertir en {{ proyecto_nombre }} dado que me da un rendimiento de {{ tirAnual }}% y el mercado de {{ tea.toFixed(2) }}%
                </template>
                <template v-else-if="i === 8">
                  Me conviene invertir en {{ proyecto_nombre }} ya que su VAN es mayor que cero
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Botón Guardar -->
      <div class="text-center">
        <button
          @click="guardarAnalisis"
          class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 font-semibold"
          :disabled="isSaving"
        >
          {{ isSaving ? 'Guardando...' : 'Guardar Análisis Financiero' }}
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed, ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  flujo_id: Number,
  proyecto_id: Number,
  proyecto_nombre: String,
  saldo_inicial: Number,
  ingresos: Array,
  gastos: Array,
  financiamiento_recibido: Array,
  pagos_financiamiento: Array,
  tna_inversion_alternativa: [Number, String, null],
  tir_anual: [Number, String, null],
  van: [Number, String, null],
})

const isSaving = ref(false)

const form = useForm({
  tna_inversion_alternativa: Number(props.tna_inversion_alternativa ?? 10),
  tir_anual: Number(props.tir_anual ?? 0),
  van: Number(props.van ?? 0),
})

// Cálculo de tasas de mercado
const tasaMensual = computed(() =>
  Math.pow(1 + form.tna_inversion_alternativa / 100, 1 / 12) - 1
)
const tea = computed(() =>
  (Math.pow(1 + tasaMensual.value, 12) - 1) * 100
)

// Flujos acumulados (para VAN y tabla)
const flujoFondos = computed(() => {
  const ingresos = props.ingresos || []
  const gastos = props.gastos || []
  const financiamientos = props.financiamiento_recibido || []
  const pagosFin = props.pagos_financiamiento || []
  const meses = 12

  const saldos = []
  let saldo = parseFloat(props.saldo_inicial || 0)
  saldos.push(Number(-saldo.toFixed(2)))

  for (let i = 0; i < meses; i++) {
    const ingresoMes = ingresos.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    const gastoMes = gastos.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    const finMes = financiamientos.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    const pagoMes = pagosFin.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    saldo += ingresoMes + finMes - gastoMes - pagoMes
    saldos.push(Number(saldo.toFixed(2)))
  }

  return saldos
})

// Flujos netos (para calcular TIR Anual)
const flujosNetos = computed(() => {
  const ingresos = props.ingresos || []
  const gastos = props.gastos || []
  const financiamientos = props.financiamiento_recibido || []
  const pagosFin = props.pagos_financiamiento || []
  const meses = 12

  const flujos = []
  // Inversión inicial como flujo negativo
  flujos.push(Number((-parseFloat(props.saldo_inicial ?? 0)).toFixed(2)))

  for (let i = 0; i < meses; i++) {
    const ingresoMes = ingresos.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    const gastoMes = gastos.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    const finMes = financiamientos.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    const pagoMes = pagosFin.reduce((s, it) => s + (parseFloat(it.valores[i]) || 0), 0)
    const neto = ingresoMes + finMes - gastoMes - pagoMes
    flujos.push(Number(neto.toFixed(2)))
  }

  return flujos
})

// Logs para depuración
onMounted(() => {
  console.log('Flujos Acumulados:', flujoFondos.value)
  console.log('Flujos Netos:', flujosNetos.value)
  console.log('Iniciando cálculo de TIR con flujos netos…')
})

// Funciones VAN y TIR
function calcularVAN(flujos, tasa) {
  return flujos.reduce((sum, val, i) => sum + val / Math.pow(1 + tasa, i), 0)
}

function calcularTIR(flujos, guess = 0.1) {
  const maxIter = 1000
  const precision = 1e-7
  let tasa = guess
  console.log('--- calcularTIR inicio: guess =', guess)
  for (let iter = 0; iter < maxIter; iter++) {
    let f = 0, df = 0
    for (let i = 0; i < flujos.length; i++) {
      f += flujos[i] / Math.pow(1 + tasa, i)
      df += -i * flujos[i] / Math.pow(1 + tasa, i + 1)
    }
    console.log(`iter ${iter}: tasa=${tasa.toFixed(6)}, f=${f.toFixed(6)}, df=${df.toFixed(6)}`)
    const nueva = tasa - f / df
    if (Math.abs(nueva - tasa) < precision) {
      console.log(`Convergió en iter ${iter}:`, nueva)
      return nueva
    }
    tasa = nueva
  }
  console.warn('calcularTIR no convergió tras', maxIter, 'iteraciones')
  return NaN
}

// Cálculo de VAN y TIR basado en flujos netos\


const van = computed(() => {
  const v = calcularVAN(flujoFondos.value, tasaMensual.value)
  console.log('VAN calculado:', v)
  return Number(v.toFixed(2))
})

const tirMensual = computed(() => {
  console.log('--- tirMensual: flujosNetos =', flujosNetos.value)
  const tm = calcularTIR(flujosNetos.value)
  console.log('tirMensual (decimal):', tm)
  return Number((tm * 100).toFixed(2))
})

const tirAnual = computed(() => {
  console.log('--- tirAnual: calculando a partir de tirMensual')
  const tm = calcularTIR(flujosNetos.value)
  const ta = Math.pow(1 + tm, 12) - 1
  console.log('tirAnual (decimal anual):', ta)
  return Number((ta * 100).toFixed(2))
})

// Conclusión
const conclusion = computed(() =>
  tirAnual.value > form.tna_inversion_alternativa
    ? '✅ El proyecto es rentable: su TIR anual supera la TNA alternativa.'
    : '❌ El proyecto no es rentable: su TIR anual está por debajo de la TNA alternativa.'
)

const conclusionClass = computed(() =>
  tirAnual.value > form.tna_inversion_alternativa
    ? 'text-green-700'
    : 'text-red-700'
)

// Guardar análisis
function guardarAnalisis() {
  isSaving.value = true
  Inertia.patch(
    route('flujo-caja.update', props.proyecto_id),
    {
      tna_inversion_alternativa: form.tna_inversion_alternativa,
      tir_anual: tirAnual.value,
      van: van.value,
    },
    {
      preserveState: true,
      onFinish: () => (isSaving.value = false),
    }
  )
}

// Formateadores
function formatCurrency(val) {
  return new Intl.NumberFormat('es-EC', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
  }).format(val || 0)
}

function formatPercent(val) {
  return typeof val === 'number' ? val.toFixed(2) + '%' : '0.00%'
}


</script>