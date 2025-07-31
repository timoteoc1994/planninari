<template>
  <AppLayout title="Presupuesto de Marketing y ROI">
    <template #header>
      <h2 class="text-3xl font-bold text-center mb-8">PRESUPUESTO DE MARKETING Y RETORNO DE LA INVERSIÓN</h2>
    </template>

    <div class="p-6 space-y-8 max-w-full mx-auto">
      
      <!-- Sección para agregar medios -->
      <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Agregar Medio de Marketing</h3>
        <div class="flex gap-4 items-center">
          <input 
            v-model="nuevoMedio" 
            type="text" 
            placeholder="Nombre del nuevo medio (ej: Google Ads, Facebook Ads, etc.)" 
            class="border p-3 rounded flex-1"
            @keyup.enter="agregarMedio"
          />
          <button 
            @click="agregarMedio" 
            class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 font-semibold"
          >
            Agregar Medio
          </button>
        </div>
      </div>

      <!-- TABLA DE PRESUPUESTO -->
      <div>
        <h3 class="text-2xl font-bold mb-4 text-center">PRESUPUESTO</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full border-collapse border-2 border-gray-800">
            <thead>
              <tr class="bg-gray-200">
                <th class="border-2 border-gray-800 p-3 text-left font-bold">MEDIO</th>
                <th v-for="n in 8" :key="'header-' + n" class="border-2 border-gray-800 p-3 text-center font-bold">
                  TRIMESTRE {{ n }}
                </th>
                <th class="border-2 border-gray-800 p-3 text-center font-bold bg-gray-300">TOTAL</th>
                <th class="border-2 border-gray-800 p-3 text-center font-bold">ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(medio, index) in form.medios" :key="'medio-' + index" class="hover:bg-gray-50">
                <td class="border-2 border-gray-800 p-3 font-semibold">
                  <input 
                    v-model="medio.nombre" 
                    class="border p-2 rounded w-full font-semibold" 
                    placeholder="Nombre del medio"
                  />
                </td>
                <td v-for="trimestre in 8" :key="'presupuesto-' + index + '-' + (trimestre-1)" class="border-2 border-gray-800 p-3">
                  <input 
                    v-model.number="medio.valores[trimestre-1]" 
                    type="number" 
                    step="0.01"
                    min="0"
                    class="w-full text-right p-2 border rounded" 
                    placeholder="0.00"
                    @input="calcularTotales"
                  />
                </td>
                <td class="border-2 border-gray-800 p-3 text-right font-bold bg-gray-100">
                  {{ formatCurrency(getTotalMedio(medio.valores)) }}
                </td>
                <td class="border-2 border-gray-800 p-3 text-center">
                  <button 
                    @click="eliminarMedio(index)" 
                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
              <!-- Fila TOTAL -->
              <tr class="bg-blue-100 font-bold">
                <td class="border-2 border-gray-800 p-3 text-center font-bold text-lg">TOTAL</td>
                <td v-for="trimestre in 8" :key="'total-' + trimestre" class="border-2 border-gray-800 p-3 text-right font-bold text-lg">
                  {{ formatCurrency(totalesPorTrimestre[trimestre-1]) }}
                </td>
                <td class="border-2 border-gray-800 p-3 text-right font-bold text-lg bg-blue-200">
                  {{ formatCurrency(totalGeneral) }}
                </td>
                <td class="border-2 border-gray-800 p-3"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TABLA DE CÁLCULO DE RETORNO DE INVERSIÓN -->
      <div class="mt-12">
        <h3 class="text-2xl font-bold mb-4 text-center">CÁLCULO DE RETORNO DE INVERSIÓN</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full border-collapse border-2 border-gray-800">
            <thead>
              <tr class="bg-gray-200">
                <th class="border-2 border-gray-800 p-3 text-left font-bold">MEDIO</th>
                <th v-for="n in 8" :key="'roi-header-' + n" class="border-2 border-gray-800 p-3 text-center font-bold">
                  TRIMESTRE {{ n }}
                </th>
                <th class="border-2 border-gray-800 p-3 text-center font-bold bg-gray-300">TOTAL</th>
              </tr>
            </thead>
            <tbody>
              <!-- Fila COSTO -->
              <tr class="bg-red-100">
                <td class="border-2 border-gray-800 p-3 font-bold text-lg">COSTO</td>
                <td v-for="trimestre in 8" :key="'costo-' + trimestre" class="border-2 border-gray-800 p-3 text-right font-bold">
                  {{ formatCurrency(totalesPorTrimestre[trimestre-1]) }}
                </td>
                <td class="border-2 border-gray-800 p-3 text-right font-bold bg-red-200">
                  {{ formatCurrency(totalGeneral) }}
                </td>
              </tr>
              <!-- Fila GANANCIA -->
              <tr class="bg-green-100">
                <td class="border-2 border-gray-800 p-3 font-bold text-lg">GANANCIA</td>
                <td v-for="trimestre in 8" :key="'ganancia-' + trimestre" class="border-2 border-gray-800 p-3">
                  <input 
                    v-model.number="form.ganancias_trimestrales[trimestre-1]" 
                    type="number" 
                    step="0.01"
                    min="0"
                    class="w-full text-right p-2 border rounded font-bold" 
                    placeholder="0.00"
                    @input="calcularRoi"
                  />
                </td>
                <td class="border-2 border-gray-800 p-3 text-right font-bold bg-green-200">
                  {{ formatCurrency(totalGanancias) }}
                </td>
              </tr>
              <!-- Fila ROI ($) -->
              <tr class="bg-yellow-100">
                <td class="border-2 border-gray-800 p-3 font-bold text-lg">ROI ($)</td>
                <td v-for="trimestre in 8" :key="'roi-dolares-' + trimestre" class="border-2 border-gray-800 p-3 text-right font-bold">
                  {{ formatCurrency(roiDolaresPorTrimestre[trimestre-1]) }}
                </td>
                <td class="border-2 border-gray-800 p-3 text-right font-bold bg-yellow-200">
                  {{ formatCurrency(totalRoiDolares) }}
                </td>
              </tr>
              <!-- Fila ROI (%) -->
              <tr class="bg-blue-100">
                <td class="border-2 border-gray-800 p-3 font-bold text-lg">ROI (%)</td>
                <td v-for="trimestre in 8" :key="'roi-porcentaje-' + trimestre" class="border-2 border-gray-800 p-3 text-right font-bold">
                  {{ roiPorcentajePorTrimestre[trimestre-1].toFixed(2) }}
                </td>
                <td class="border-2 border-gray-800 p-3 text-right font-bold bg-blue-200">
                  {{ roiPorcentajeTotal.toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Botón de guardar -->
      <div class="mt-8 text-center">
        <button 
          @click="saveData" 
          :disabled="form.processing"
          class="bg-blue-600 text-white px-10 py-4 rounded-lg hover:bg-blue-700 disabled:bg-gray-400 text-lg font-bold"
        >
          {{ form.processing ? 'Guardando...' : 'Guardar Presupuesto y Cálculos' }}
        </button>
      </div>

      <!-- Mensaje de éxito -->
      <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-center">
        {{ $page.props.flash.success }}
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  presupuestoMarketing: {
    type: Object,
    default: () => ({
      medios: [],
      costos_trimestrales: Array(8).fill(0),
      ganancias_trimestrales: Array(8).fill(0),
      roi_dolares: Array(8).fill(0),
      roi_porcentajes: Array(8).fill(0)
    })
  },
  proyecto_id: Number,
})

const nuevoMedio = ref('')

// Inicializar el formulario con la estructura correcta
const form = useForm({
  medios: props.presupuestoMarketing?.medios || [],
  ganancias_trimestrales: props.presupuestoMarketing?.ganancias_trimestrales || Array(8).fill(0)
})

// Asegurar que los medios tengan la estructura correcta
form.medios = form.medios.map(medio => ({
  nombre: medio.nombre || '',
  valores: Array.isArray(medio.valores) && medio.valores.length === 8 
    ? medio.valores 
    : Array(8).fill(0)
}))

// Computadas para cálculos automáticos
const totalesPorTrimestre = computed(() => {
  const totales = Array(8).fill(0)
  form.medios.forEach(medio => {
    if (medio.valores) {
      for (let i = 0; i < 8; i++) {
        totales[i] += parseFloat(medio.valores[i]) || 0
      }
    }
  })
  return totales
})

const totalGeneral = computed(() => {
  return totalesPorTrimestre.value.reduce((sum, total) => sum + total, 0)
})

const totalGanancias = computed(() => {
  return form.ganancias_trimestrales.reduce((sum, ganancia) => sum + (parseFloat(ganancia) || 0), 0)
})

const roiDolaresPorTrimestre = computed(() => {
  return form.ganancias_trimestrales.map((ganancia, index) => {
    return (parseFloat(ganancia) || 0) - (totalesPorTrimestre.value[index] || 0)
  })
})

const totalRoiDolares = computed(() => {
  return roiDolaresPorTrimestre.value.reduce((sum, roi) => sum + roi, 0)
})

const roiPorcentajePorTrimestre = computed(() => {
  return totalesPorTrimestre.value.map((costo, index) => {
    const ganancia = roiDolaresPorTrimestre.value[index] || 0; // La ganancia ya es (ganancia - costo)
    
    // Calcular ROI en porcentaje: (Ganancia - Costo) / Costo * 100
    if (costo > 0) {
      const roiPorcentaje = ((ganancia) - costo) / costo;  // Corrigiendo el cálculo de ROI (%)
      console.log(`ROI porcentual del trimestre ${index + 1}: ${roiPorcentaje}`); // Log de ROI porcentual por trimestre
      return roiPorcentaje
    }
    return 0
  })
})

const roiPorcentajeTotal = computed(() => {
  if (totalGeneral.value > 0) {
    return (totalRoiDolares.value / totalGeneral.value) * 100
  }
  return 0
})

// Funciones
function agregarMedio() {
  if (nuevoMedio.value.trim()) {
    form.medios.push({
      nombre: nuevoMedio.value.trim(),
      valores: Array(8).fill(0)
    })
    nuevoMedio.value = ''
  }
}

function eliminarMedio(index) {
  form.medios.splice(index, 1)
  saveData()
}

function getTotalMedio(valores) {
  if (!valores || !Array.isArray(valores)) return 0
  return valores.reduce((sum, value) => sum + (parseFloat(value) || 0), 0)
}

function saveData() {
  form.put(route('presupuesto-marketing.update', props.proyecto_id), {
    preserveScroll: true,
    onSuccess: (page) => {
      console.log('Datos guardados exitosamente')
    },
    onError: (errors) => {
      console.error('Error al guardar:', errors)
    }
  })
}

function formatCurrency(value) {
  return new Intl.NumberFormat('es-EC', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
  }).format(value || 0)
}

// Asegurar que las ganancias siempre sean un array de 8 elementos
watch(() => form.ganancias_trimestrales, (newValue) => {
  if (!Array.isArray(newValue) || newValue.length !== 8) {
    form.ganancias_trimestrales = Array(8).fill(0)
  }
}, { immediate: true })
</script>
