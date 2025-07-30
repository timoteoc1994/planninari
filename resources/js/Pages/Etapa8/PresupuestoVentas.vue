<template>
  <AppLayout title="Presupuesto Mensual de Ventas y Proyecciones">
    <template #header>
      <h2 class="text-center text-2xl font-bold">
        Punto de Equilibrio y Proyecciones
      </h2>
    </template>

    <div class="py-8 max-w-6xl mx-auto space-y-6">
      <!-- Botón Limpiar datos -->
      <button
        @click="clearData"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
      >
        Limpiar datos
      </button>

      <!-- Proyección 100% -->
      <section>
        <h3 class="text-xl font-semibold mb-4">
          PRESUPUESTO MENSUAL DE VENTAS
          <br />
          <small class="text-sm">Proyección 100% sobre Punto de Equilibrio</small>
        </h3>
        <div class="overflow-x-auto mb-6">
          <table class="w-full table-auto border-collapse bg-white rounded shadow">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-2">Producto</th>
                <th class="px-4 py-2">Unidad</th>
                <th class="px-4 py-2">Punto eq. mensual</th>
                <th class="px-4 py-2">Unid. mensuales</th>
                <th class="px-4 py-2">Unid. semanales</th>
                <th class="px-4 py-2">Unid. diarias</th>
                <th class="px-4 py-2">Precio unit.</th>
                <th class="px-4 py-2">Venta est. mensual</th>
                <th class="px-4 py-2">Margen est.</th>
                <th class="px-4 py-2">Utilidad est. mensual</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in recetas" :key="r.ref" class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">{{ r.nombre_producto }}</td>
                <td class="px-4 py-2 text-center">{{ r.unidad_medida }}</td>
                <td class="px-4 py-2 text-right">
                  {{ formatInteger(r.unidades_mensuales_necesarias) }}
                </td>
                <td class="px-4 py-2 text-right">
                  <input
                    type="number"
                    v-model.number="metas100[r.ref]"
                    class="w-20 text-right border rounded p-1"
                  />
                </td>
                <td class="px-4 py-2 text-right">{{ formatInteger(proyect100[r.ref].semanal) }}</td>
                <td class="px-4 py-2 text-right">{{ formatInteger(proyect100[r.ref].diario) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(r.precio_venta) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(proyect100[r.ref].venta) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(proyect100[r.ref].margen) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(proyect100[r.ref].utilidad) }}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="font-semibold bg-gray-50">
                <td colspan="7" class="px-4 py-2 text-left">TOTALES</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(totales100.venta) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(totales100.margen) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(totales100.utilidad) }}</td>
              </tr>
              <tr class="font-semibold">
                <td colspan="7" class="px-4 py-2 text-left">Utilidad Anual</td>
                <td colspan="3" class="px-4 py-2 text-right">
                  ${{ formatNumber(totales100.utilidad * 12) }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </section>

      <!-- Proyección 125% -->
      <section>
        <h3 class="text-xl font-semibold mb-4">
          PROYECCIÓN DE CRECIMIENTO EN VENTAS
          <br />
          <small class="text-sm">125% Anual</small>
        </h3>
        <div class="overflow-x-auto">
          <table class="w-full table-auto border-collapse bg-white rounded shadow">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-2">Producto</th>
                <th class="px-4 py-2">Unidad</th>
                <th class="px-4 py-2">Punto eq. mensual</th>
                <th class="px-4 py-2">Unid. mensuales</th>
                <th class="px-4 py-2">Unid. semanales</th>
                <th class="px-4 py-2">Unid. diarias</th>
                <th class="px-4 py-2">Precio unit.</th>
                <th class="px-4 py-2">Venta est. mensual</th>
                <th class="px-4 py-2">Margen est.</th>
                <th class="px-4 py-2">Utilidad est. mensual</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in recetas" :key="r.ref" class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">{{ r.nombre_producto }}</td>
                <td class="px-4 py-2 text-center">{{ r.unidad_medida }}</td>
                <td class="px-4 py-2 text-right">
                  {{ formatInteger(r.unidades_mensuales_necesarias) }}
                </td>
                <td class="px-4 py-2 text-right">
                  <input
                    type="number"
                    v-model.number="metas125[r.ref]"
                    class="w-20 text-right border rounded p-1"
                  />
                </td>
                <td class="px-4 py-2 text-right">{{ formatInteger(proyect125[r.ref].semanal) }}</td>
                <td class="px-4 py-2 text-right">{{ formatInteger(proyect125[r.ref].diario) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(r.precio_venta) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(proyect125[r.ref].venta) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(proyect125[r.ref].margen) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(proyect125[r.ref].utilidad) }}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="font-semibold bg-gray-50">
                <td colspan="7" class="px-4 py-2 text-left">TOTALES</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(totales125.venta) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(totales125.margen) }}</td>
                <td class="px-4 py-2 text-right">${{ formatNumber(totales125.utilidad) }}</td>
              </tr>
              <tr class="font-semibold">
                <td colspan="7" class="px-4 py-2 text-left">Utilidad Anual</td>
                <td colspan="3" class="px-4 py-2 text-right">
                  ${{ formatNumber(totales125.utilidad * 12) }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </section>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, computed, onMounted, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  recetas: Array,
  dias_por_semana: Number,
  punto_equilibrio_mensual_global: Number,
  totalCostos: Number,
  margen_ponderado: Number,
})

// LocalStorage keys
const STORAGE_KEY_100 = 'metas100'
const STORAGE_KEY_125 = 'metas125'

// Iniciar metas
const metas100 = reactive({})
const metas125 = reactive({})
props.recetas.forEach(r => {
  metas100[r.ref] = 0
  metas125[r.ref] = 0
})

// Cargar del storage
onMounted(() => {
  const saved100 = localStorage.getItem(STORAGE_KEY_100)
  if (saved100) Object.assign(metas100, JSON.parse(saved100))
  const saved125 = localStorage.getItem(STORAGE_KEY_125)
  if (saved125) Object.assign(metas125, JSON.parse(saved125))
})

// Guardar cambios
watch(metas100, val => localStorage.setItem(STORAGE_KEY_100, JSON.stringify(val)), { deep: true })
watch(metas125, val => localStorage.setItem(STORAGE_KEY_125, JSON.stringify(val)), { deep: true })

// Limpiar datos
const clearData = () => {
  props.recetas.forEach(r => {
    metas100[r.ref] = 0
    metas125[r.ref] = 0
  })
  localStorage.removeItem(STORAGE_KEY_100)
  localStorage.removeItem(STORAGE_KEY_125)
}

// Cálculos proyección 100%
const proyect100 = computed(() => {
  const p = {}
  props.recetas.forEach(r => {
    const m = metas100[r.ref]
    const semanal = Math.round((m * 12) / 52)
    const diario = Math.round(semanal / props.dias_por_semana)
    
    // Venta total del producto
    const venta = m * r.precio_venta
    
    // Margen de contribución del producto
    const margen = m * r.margen_contribucion_unidad
    
    // Utilidad = Margen - (Costos totales * porcentaje de participación del producto)
    const utilidad = margen - (props.totalCostos * (r.pct_participacion / 100))
    
    p[r.ref] = {
      semanal,
      diario,
      venta,
      margen,
      utilidad
    }
  })
  return p
})

// Cálculos proyección 125%
const proyect125 = computed(() => {
  const p = {}
  props.recetas.forEach(r => {
    const m = metas125[r.ref]
    const semanal = Math.round((m * 12) / 52)
    const diario = Math.round(semanal / props.dias_por_semana)
    
    // Venta total del producto
    const venta = m * r.precio_venta
    
    // Margen de contribución del producto
    const margen = m * r.margen_contribucion_unidad
    
    // Utilidad = Margen - (Costos totales * porcentaje de participación del producto)
    const utilidad = margen - (props.totalCostos * (r.pct_participacion / 100))
    
    p[r.ref] = {
      semanal,
      diario,
      venta,
      margen,
      utilidad
    }
  })
  return p
})

// Totales proyección 100%
const totales100 = computed(() =>
  props.recetas.reduce((acc, r) => {
    const v = proyect100.value[r.ref]
    acc.venta    += v.venta
    acc.margen   += v.margen
    acc.utilidad += v.utilidad
    return acc
  }, { venta: 0, margen: 0, utilidad: 0 })
)

// Totales proyección 125%
const totales125 = computed(() =>
  props.recetas.reduce((acc, r) => {
    const v = proyect125.value[r.ref]
    acc.venta    += v.venta
    acc.margen   += v.margen
    acc.utilidad += v.utilidad
    return acc
  }, { venta: 0, margen: 0, utilidad: 0 })
)

// Consola: verificar valores de props
console.log('totalCostos:', props.totalCostos)
props.recetas.forEach(r => {
  console.log(`Margen ponderado de ${r.ref}:`, r.pct_participacion)
})

// Formateadores
const formatNumber  = v => isNaN(Number(v)) ? '0.00' : Number(v).toFixed(2)
const formatInteger = v => isNaN(Number(v)) ? '0'    : Math.round(v).toString()
</script>