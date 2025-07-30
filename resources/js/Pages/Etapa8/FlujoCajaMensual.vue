<template>
  <AppLayout title="Flujo de Caja Mensual">
    <template #header>
      <h2 class="text-2xl font-bold text-center">Flujo de Caja Mensual</h2>
    </template>

    <div class="p-6 space-y-6 max-w-full mx-auto">
      <!-- Saldo Inicial -->
      <div class="bg-blue-50 p-4 rounded-lg overflow-x-auto">
        <div class="flex items-center space-x-4 mb-4">
          <label class="font-semibold text-lg">Saldo Inicial:</label>
          <input
            type="number"
            v-model.number="form.saldo_inicial"
            @change="recalculateAll"
            class="border p-2 rounded w-56 font-bold"
            step="0.01"
          />
          <button
            @click="saveField('saldo_inicial')"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
            :disabled="isSaving"
          >
            {{ isSaving ? 'Guardando...' : 'Guardar' }}
          </button>
        </div>
      </div>

      <!-- Tabla del Flujo de Caja -->
      <div class="bg-white p-4 rounded-lg shadow overflow-x-auto">
        <table class="min-w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 p-2 text-left min-w-48">CONCEPTO</th>
              <th
                v-for="m in 12"
                :key="m"
                class="border border-gray-300 p-2 text-center min-w-24"
              >
                MES {{ m }}
              </th>
              <th class="border border-gray-300 p-2 text-center bg-gray-200 min-w-32">
                TOTAL
              </th>
            </tr>
          </thead>
          <tbody>
            <!-- Saldo de Caja Acumulado -->
            <tr>
              <td class="border border-gray-300 p-2 font-semibold bg-blue-50">
                Saldo de Caja
              </td>
              <td
                v-for="(saldo, m) in saldosIniciales"
                :key="`saldo-${m}`"
                class="border border-gray-300 p-2 text-right bg-blue-50 font-semibold"
              >
                {{ formatCurrency(saldo) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-200 font-bold">
                {{ formatCurrency(getTotalSaldosIniciales()) }}
              </td>
            </tr>

            <!-- Espacio -->
            <tr><td colspan="14" class="border-0 h-4"></td></tr>

            <!-- Sección Ingresos -->
            <tr>
              <td
                class="border border-gray-300 p-2 font-bold text-green-700 bg-green-50"
                colspan="14"
              >
                INGRESOS
              </td>
            </tr>
            <tr v-for="(item, i) in form.ingresos" :key="`ing-${i}`">
              <td class="border border-gray-300 p-2">
                <div class="flex items-center space-x-2">
                  <input
                    type="text"
                    v-model="item.nombre"
                    @change="saveField('ingresos')"
                    class="border p-1 rounded flex-1"
                    placeholder="Nombre del ingreso"
                  />
                  <select
                    v-model="item.projectionType"
                    @change="onProjectionTypeChange('ingresos', i)"
                    class="border p-2 rounded text-xs w-48"
                  >
                    <option value="creciente">Creciente 20%</option>
                    <option value="constante">Constante</option>
                  </select>
                  <button
                    @click="removeRow('ingresos', i)"
                    class="text-red-600 hover:text-red-800 text-xs px-2"
                  >
                    ✕
                  </button>
                </div>
              </td>
              <td
                v-for="(val, j) in item.valores"
                :key="`ing-${i}-${j}`"
                class="border border-gray-300 p-2 text-right"
              >
                <input
                  type="number"
                  v-model.number="item.valores[j]"
                  @change="onValueChange('ingresos', i, j)"
                  class="w-full text-right p-1 border rounded"
                  step="0.01"
                />
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-100 font-semibold">
                {{ formatCurrency(getTotalItem(item)) }}
              </td>
            </tr>
            <tr class="bg-green-100">
              <td class="border border-gray-300 p-2 font-bold">
                Total Ingresos
                <button
                  @click="addRow('ingresos')"
                  class="ml-2 text-xs text-blue-600 hover:underline"
                >
                  + Agregar
                </button>
              </td>
              <td
                v-for="m in 12"
                :key="`total-ing-${m}`"
                class="border border-gray-300 p-2 text-right font-bold"
              >
                {{ formatCurrency(getTotalMes('ingresos', m - 1)) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-200 font-bold">
                {{ formatCurrency(getTotalSeccion('ingresos')) }}
              </td>
            </tr>

            <!-- Espacio -->
            <tr><td colspan="14" class="border-0 h-4"></td></tr>

            <!-- Sección Gastos -->
            <tr>
              <td
                class="border border-gray-300 p-2 font-bold text-red-700 bg-red-50"
                colspan="14"
              >
                GASTOS
              </td>
            </tr>
            <tr v-for="(item, i) in form.gastos" :key="`gas-${i}`">
              <td class="border border-gray-300 p-2">
                <div class="flex items-center space-x-2">
                  <input
                    type="text"
                    v-model="item.nombre"
                    @change="saveField('gastos')"
                    class="border p-1 rounded flex-1"
                    placeholder="Nombre del gasto"
                  />
                  <select
                    v-model="item.projectionType"
                    @change="onProjectionTypeChange('gastos', i)"
                    class="border p-2 rounded text-xs w-48"
                  >
                    <option value="creciente">Creciente 20%</option>
                    <option value="constante">Constante</option>
                  </select>
                  <button
                    @click="removeRow('gastos', i)"
                    class="text-red-600 hover:text-red-800 text-xs px-2"
                  >
                    ✕
                  </button>
                </div>
              </td>
              <td
                v-for="(val, j) in item.valores"
                :key="`gas-${i}-${j}`"
                class="border border-gray-300 p-2 text-right"
              >
                <input
                  type="number"
                  v-model.number="item.valores[j]"
                  @change="onValueChange('gastos', i, j)"
                  class="w-full text-right p-1 border rounded"
                  step="0.01"
                />
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-100 font-semibold">
                {{ formatCurrency(getTotalItem(item)) }}
              </td>
            </tr>
            <tr class="bg-red-100">
              <td class="border border-gray-300 p-2 font-bold">
                Total Gastos
                <button
                  @click="addRow('gastos')"
                  class="ml-2 text-xs text-blue-600 hover:underline"
                >
                  + Agregar
                </button>
              </td>
              <td
                v-for="m in 12"
                :key="`total-gas-${m}`"
                class="border border-gray-300 p-2 text-right font-bold"
              >
                {{ formatCurrency(getTotalMes('gastos', m - 1)) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-200 font-bold">
                {{ formatCurrency(getTotalSeccion('gastos')) }}
              </td>
            </tr>

            <!-- Flujo Neto Mensual -->
            <tr class="bg-yellow-50">
              <td class="border border-gray-300 p-2 font-bold text-yellow-800">
                Flujo Neto Mensual
              </td>
              <td
                v-for="m in 12"
                :key="`net-${m}`"
                class="border border-gray-300 p-2 text-right font-bold text-yellow-800"
              >
                {{ formatCurrency(getTotalMes('ingresos', m - 1) - getTotalMes('gastos', m - 1)) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-200 font-bold text-yellow-800">
                {{ formatCurrency(getTotalSeccion('ingresos') - getTotalSeccion('gastos')) }}
              </td>
            </tr>

            <!-- Flujo de Caja Económico Acumulado -->
            <tr class="bg-yellow-100">
              <td class="border border-gray-300 p-2 font-bold text-yellow-800">
                Flujo de Caja Económico
              </td>
              <td
                v-for="(saldo, m) in saldosEconomicos"
                :key="`eco-${m}`"
                class="border border-gray-300 p-2 text-right font-bold text-yellow-800"
              >
                {{ formatCurrency(saldo) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-200 font-bold text-yellow-800">
                {{ formatCurrency(getTotalFlujoCajaEconomico()) }}
              </td>
            </tr>

            <!-- Espacio -->
            <tr><td colspan="14" class="border-0 h-4"></td></tr>

            <!-- Sección Financiamiento Recibido -->
            <tr>
              <td
                class="border border-gray-300 p-2 font-bold text-blue-700 bg-blue-50"
                colspan="14"
              >
                FINANCIAMIENTO RECIBIDO
              </td>
            </tr>
            <tr
              v-for="(item, i) in form.financiamiento_recibido"
              :key="`fin-${i}`"
            >
              <td class="border border-gray-300 p-2">
                <div class="flex items-center space-x-2">
                  <input
                    type="text"
                    v-model="item.nombre"
                    @change="saveField('financiamiento_recibido')"
                    class="border p-1 rounded flex-1"
                    placeholder="Préstamo o financiamiento"
                  />
                  <select
                    v-model="item.projectionType"
                    @change="onProjectionTypeChange('financiamiento_recibido', i)"
                    class="border p-2 rounded text-xs w-48"
                  >
                    <option value="creciente">Creciente 20%</option>
                    <option value="constante">Constante</option>
                  </select>
                  <button
                    @click="removeRow('financiamiento_recibido', i)"
                    class="text-red-600 hover:text-red-800 text-xs px-2"
                  >
                    ✕
                  </button>
                </div>
              </td>
              <td
                v-for="(val, j) in item.valores"
                :key="`fin-${i}-${j}`"
                class="border border-gray-300 p-2 text-right"
              >
                <input
                  type="number"
                  v-model.number="item.valores[j]"
                  @change="onValueChange('financiamiento_recibido', i, j)"
                  class="w-full text-right p-1 border rounded"
                  step="0.01"
                />
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-100 font-semibold">
                {{ formatCurrency(getTotalItem(item)) }}
              </td>
            </tr>
            <tr class="bg-blue-100">
              <td class="border border-gray-300 p-2 font-bold">
                Total Financiamiento Recibido
                <button
                  @click="addRow('financiamiento_recibido')"
                  class="ml-2 text-xs text-blue-600 hover:underline"
                >
                  + Agregar
                </button>
              </td>
              <td
                v-for="m in 12"
                :key="`total-fin-${m}`"
                class="border border-gray-300 p-2 text-right font-bold"
              >
                {{ formatCurrency(getTotalMes('financiamiento_recibido', m - 1)) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-200 font-bold">
                {{ formatCurrency(getTotalSeccion('financiamiento_recibido')) }}
              </td>
            </tr>

            <!-- Espacio -->
            <tr><td colspan="14" class="border-0 h-4"></td></tr>

            <!-- Sección Pagos de Financiamiento -->
            <tr>
              <td
                class="border border-gray-300 p-2 font-bold text-orange-700 bg-orange-50"
                colspan="14"
              >
                PAGOS DE FINANCIAMIENTO
              </td>
            </tr>
            <tr
              v-for="(item, i) in form.pagos_financiamiento"
              :key="`pag-${i}`"
            >
              <td class="border border-gray-300 p-2">
                <div class="flex items-center space-x-2">
                  <input
                    type="text"
                    v-model="item.nombre"
                    @change="saveField('pagos_financiamiento')"
                    class="border p-1 rounded flex-1"
                    placeholder="Pago de préstamo"
                  />
                  <select
                    v-model="item.projectionType"
                    @change="onProjectionTypeChange('pagos_financiamiento', i)"
                    class="border p-2 rounded text-xs w-48"
                  >
                    <option value="creciente">Creciente 20%</option>
                    <option value="constante">Constante</option>
                  </select>
                  <button
                    @click="removeRow('pagos_financiamiento', i)"
                    class="text-red-600 hover:text-red-800 text-xs px-2"
                  >
                    ✕
                  </button>
                </div>
              </td>
              <td
                v-for="(val, j) in item.valores"
                :key="`pag-${i}-${j}`"
                class="border border-gray-300 p-2 text-right"
              >
                <input
                  type="number"
                  v-model.number="item.valores[j]"
                  @change="onValueChange('pagos_financiamiento', i, j)"
                  class="w-full text-right p-1 border rounded"
                  step="0.01"
                />
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-100 font-semibold">
                {{ formatCurrency(getTotalItem(item)) }}
              </td>
            </tr>
            <tr class="bg-orange-100">
              <td class="border border-gray-300 p-2 font-bold">
                Total Pagos Financiamiento
                <button
                  @click="addRow('pagos_financiamiento')"
                  class="ml-2 text-xs text-blue-600 hover:underline"
                >
                  + Agregar
                </button>
              </td>
              <td
                v-for="m in 12"
                :key="`total-pag-${m}`"
                class="border border-gray-300 p-2 text-right font-bold"
              >
                {{ formatCurrency(getTotalMes('pagos_financiamiento', m - 1)) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-200 font-bold">
                {{ formatCurrency(getTotalSeccion('pagos_financiamiento')) }}
              </td>
            </tr>

            <!-- Flujo de Caja Financiero Final -->
            <tr class="bg-green-200 border-t-4 border-green-500">
              <td class="border border-gray-300 p-2 font-bold text-green-800 text-lg">
                FLUJO DE CAJA FINANCIERO MENSUAL
              </td>
              <td
                v-for="m in 12"
                :key="`final-${m}`"
                class="border border-gray-300 p-2 text-right font-bold text-green-800 text-lg"
              >
                {{ formatCurrency(getFlujoCajaFinanciero(m - 1)) }}
              </td>
              <td class="border border-gray-300 p-2 text-right bg-gray-300 font-bold text-green-800 text-lg">
                {{ formatCurrency(getTotalFlujoCajaFinanciero()) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Proyección Anual -->
      <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="font-bold text-xl mb-4 text-center">
          FLUJO DE CAJA ANUAL - PROYECCIÓN
        </h3>
        <div class="overflow-x-auto">
          <table class="min-w-full border-collapse border border-gray-300">
            <thead>
              <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2 text-left">PROYECCIÓN</th>
                <th
                  v-for="year in 12"
                  :key="year"
                  class="border border-gray-300 p-2 text-center"
                >
                  Año {{ year }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="border border-gray-300 p-2 font-semibold">
                  Crecimiento 10% Anual
                </td>
                <td
                  v-for="year in 12"
                  :key="`10-${year}`"
                  class="border border-gray-300 p-2 text-right"
                >
                  {{ formatCurrency(getProyeccionAnual(year, 0.10)) }}
                </td>
              </tr>
              <tr class="bg-yellow-50">
                <td class="border border-gray-300 p-2 font-semibold">
                  Crecimiento 20% Anual
                </td>
                <td
                  v-for="year in 12"
                  :key="`20-${year}`"
                  class="border border-gray-300 p-2 text-right font-semibold"
                >
                  {{ formatCurrency(getProyeccionAnual(year, 0.20)) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Botón de Guardado General 
      <div class="text-center">
        <button
          @click="saveAll"
          class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 disabled:opacity-50 font-semibold"
          :disabled="isSaving"
        >
          {{ isSaving ? 'Guardando...' : 'Guardar Todo el Flujo de Caja' }}
        </button>
      </div>
      -->
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  proyecto_id: Number,
  flujo_id: Number,
  saldoInicial: Number,
  ingresos: Array,
  gastos: Array,
  financiamientoRecibido: Array,
  pagosFinanciamiento: Array,
})

const form = useForm({
  saldo_inicial: props.saldoInicial ?? 0,
  ingresos:
    props.ingresos?.map(i => ({
      ...i,
      projectionType: i.projectionType || 'creciente',
      valores: [...(i.valores || Array(12).fill(0))],
    })) || [],
  gastos:
    props.gastos?.map(i => ({
      ...i,
      projectionType: i.projectionType || 'creciente',
      valores: [...(i.valores || Array(12).fill(0))],
    })) || [],
  financiamiento_recibido:
    props.financiamientoRecibido?.map(i => ({
      ...i,
      projectionType: i.projectionType || 'constante',
      valores: [...(i.valores || Array(12).fill(0))],
    })) || [],
  pagos_financiamiento:
    props.pagosFinanciamiento?.map(i => ({
      ...i,
      projectionType: i.projectionType || 'constante',
      valores: [...(i.valores || Array(12).fill(0))],
    })) || [],
})

const isSaving = ref(false)

// Cálculo de saldos acumulados (saldo inicial + ingresos - gastos)
const saldosIniciales = computed(() => {
  const s = []
  for (let m = 0; m < 12; m++) {
    if (m === 0) {
      s.push(parseFloat(form.saldo_inicial) || 0)
    } else {
      const prev = s[m - 1]
      const netAnterior = getTotalMes('ingresos', m - 1) - getTotalMes('gastos', m - 1)
      s.push(Number((prev + netAnterior).toFixed(2)))
    }
  }
  return s
})

// — Flujo de Caja Económico (incluye ya neto de cada mes) —
const saldosEconomicos = computed(() => {
  const e = []
  for (let m = 0; m < 12; m++) {
    const netoMes = getTotalMes('ingresos', m) - getTotalMes('gastos', m)
    if (m === 0) {
      const inicio = parseFloat(form.saldo_inicial) || 0
      e.push(Number((inicio + netoMes).toFixed(2)))
    } else {
      e.push(Number((e[m - 1] + netoMes).toFixed(2)))
    }
  }
  return e
})

function formatCurrency(val) {
  return new Intl.NumberFormat('es-EC', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
  }).format(val || 0)
}

function getTotalItem(item) {
  return item.valores.reduce((sum, v) => sum + (parseFloat(v) || 0), 0)
}

function getTotalMes(sec, idx) {
  return (form[sec] || []).reduce(
    (sum, it) => sum + (parseFloat(it.valores[idx]) || 0),
    0
  )
}

function getTotalSeccion(sec) {
  return (form[sec] || []).reduce((sum, it) => sum + getTotalItem(it), 0)
}

function getTotalSaldosIniciales() {
  return saldosIniciales.value.reduce((sum, x) => sum + x, 0)
}

function getTotalFlujoCajaEconomico() {
  return saldosEconomicos.value.reduce((sum, x) => sum + x, 0)
}

function getFlujoCajaFinanciero(m) {
  return (
    saldosEconomicos.value[m] +
    getTotalMes('financiamiento_recibido', m) -
    getTotalMes('pagos_financiamiento', m)
  )
}

function getTotalFlujoCajaFinanciero() {
  return Array.from({ length: 12 }, (_, i) => getFlujoCajaFinanciero(i)).reduce(
    (a, b) => a + b,
    0
  )
}

function getProyeccionAnual(year, rate) {
  const base = getTotalFlujoCajaFinanciero()
  return base * Math.pow(1 + rate, year - 1)
}

function recalculateAll() {
  // computed se actualizan automáticamente
}

function saveField(fieldKey) {
  if (isSaving.value) return
  isSaving.value = true
  Inertia.patch(
    route('flujo-caja.update', props.proyecto_id),
    { [fieldKey]: form[fieldKey] },
    {
      preserveState: true,
      onFinish: () => (isSaving.value = false),
      onError: () => (isSaving.value = false),
    }
  )
}

function saveAll() {
  if (isSaving.value) return
  isSaving.value = true
  Inertia.patch(
    route('flujo-caja.update', props.proyecto_id),
    form.data(),
    {
      preserveState: true,
      onFinish: () => (isSaving.value = false),
      onError: () => (isSaving.value = false),
    }
  )
}

function addRow(sec) {
  form[sec].push({ nombre: '', projectionType: 'creciente', valores: Array(12).fill(0) })
  saveField(sec)
}

function removeRow(sec, idx) {
  form[sec].splice(idx, 1)
  saveField(sec)
}

function onValueChange(sec, idx, j) {
  const item = form[sec][idx]
  const newValue = parseFloat(item.valores[j]) || 0

  // Si cambiaste el primer mes, recalcula automáticamente los otros meses
  if (j === 0) {
    for (let m = 1; m < 12; m++) {
      item.valores[m] =
        item.projectionType === 'creciente'
          ? Number((newValue * Math.pow(1.2, m)).toFixed(2))
          : newValue
    }
  }

  recalculateAll()
  saveField(sec)
}


 function onProjectionTypeChange(sec, idx) {
   recalculateItem(sec, idx)
   recalculateAll()
   saveField(sec)
 }

function recalculateItem(sec, idx) {
  const item = form[sec][idx]
  const base = parseFloat(item.valores[0]) || 0

  for (let m = 1; m < 12; m++) {
    item.valores[m] =
      item.projectionType === 'creciente'
        ? Number((base * Math.pow(1.2, m)).toFixed(2))
        : base
  }
}


</script>