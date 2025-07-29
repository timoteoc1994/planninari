<template>
  <AppLayout title="Punto de Equilibrio (Productos)">
    <template #header>
      <h2 class="text-center text-2xl font-bold">
        Cálculo de Punto de Equilibrio (Productos)
      </h2>
    </template>

    <div class="py-8 max-w-6xl mx-auto">
      <!-- Días x semana -->
      <div class="mb-6 flex justify-between items-center">
        <div>
          <span class="font-semibold">Días a la semana que se trabaja:</span>
          {{ diasPorSemana }}
        </div>
      </div>

      <!-- Tabla -->
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse bg-white rounded shadow">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 text-left border">Producto</th>
              <th class="p-2 text-center border">Unidad</th>
              <th class="p-2 text-right border">% Participación</th>
              <th class="p-2 text-right border">Unid. Diarias</th>
              <th class="p-2 text-right border">Unid. Semanales</th>
              <th class="p-2 text-right border">Unid. Mensuales</th>
              <th class="p-2 text-right border">Margen x Unidad</th>
              <th class="p-2 text-right border">% Margen</th>
              <th class="p-2 text-right border">Margen Ponderado</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="r in recetas" :key="r.ref" class="border-t hover:bg-gray-50">
              <td class="p-2 border">{{ r.nombre_producto ?? '—' }}</td>
              <td class="p-2 text-center border">{{ r.unidad_medida }}</td>
              <td class="p-2 text-right border">
                {{ formatInteger(r.pct_participacion) }}%
              </td>
              <td class="p-2 text-right border">
  {{ formatInteger(((r.unidades_mensuales_necesarias * 12) / 52) / diasPorSemana) }}
</td>
<td class="p-2 text-right border">
  {{ formatInteger((r.unidades_mensuales_necesarias * 12) / 52) }}
</td>
              <td class="p-2 text-right border">{{ r.unidades_mensuales_necesarias }}</td>
              <td class="p-2 text-right border">
                ${{ formatNumber(r.margen_contribucion_unidad) }}
              </td>
              <td class="p-2 text-right border">
                {{ formatInteger(r.pct_margen) }}%
              </td>
              <td class="p-2 text-right border">
                {{ formatNumber(r.margen_ponderado) }}
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="font-semibold bg-gray-50">
              <td colspan="5" class="p-2 text-left border">TOTALES</td>
              <td class="p-2 text-right border">
                {{ formatInteger(totalUnidadesMensuales) }}
              </td>
              <td class="p-2 text-right border">—</td>
              <td class="p-2 text-right border">—</td>
              <td class="p-2 text-right border">
                {{ formatNumber(totalMargenPonderado) }}
              </td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Info de cálculos -->
      <div class="mt-6 bg-blue-50 p-4 rounded">
        <h3 class="font-semibold mb-2">Información de Cálculos:</h3>
        <ul class="text-sm space-y-1">
          <li>
            <strong>% Participación:</strong>
            (Total Venta del Producto / Total Ventas Global) × 100
          </li>
          <li>
            <strong>Margen por Producto:</strong> Precio Venta - Costo Unitario
          </li>
          <li>
            <strong>Margen Ponderado:</strong>
            (% Participación × Margen por Unidad) / 100
          </li>
          <li>
            <strong>Unidades Mensuales:</strong>
            Unid. Diarias × Días por Semana × (52 semanas / 12 meses)
          </li>
          <li>
            <strong>Unidades Mensuales Necesarias Global:</strong>
            (Total Costos Fijos + Salarios) / Total Margen Ponderado
          </li>
          <li>
            <strong>Unidades Mensuales Necesarias x Producto:</strong>
            (Global) × (% Participación / 100)
          </li>
        </ul>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  recetas: Array,
  dias_por_semana: { type: Number, default: 6 },
  totalCostosFijos: { type: Number, default: 0 },
  totalSalariosReales: { type: Number, default: 0 },
  totalMargenPonderado: { type: Number, default: 0 },
  unidadesMensualesNecesarias: { type: Number, default: 0 }, // global
})


const totalUnidadesMensuales = computed(() => {
  return props.recetas?.reduce((sum, r) => {
    return sum + (Number(r.unidades_mensuales_necesarias) || 0)
  }, 0)
})
// Alias para facilitar
const diasPorSemana = props.dias_por_semana

// Formateadores
const formatNumber = (v) =>
  v == null || isNaN(v) ? '0.00' : Number(v).toFixed(2)
const formatInteger = (v) =>
  v == null || isNaN(v) ? '0' : Math.round(v).toString()

</script>
