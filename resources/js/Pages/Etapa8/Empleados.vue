<template>
  <AppLayout title="Flujo de Caja Mensual">
    <template #header>
      <h2 class="text-2xl font-bold text-center">Flujo de Caja Mensual</h2>
    </template>

    <div class="p-6 space-y-6 max-w-6xl mx-auto">
      <!-- Saldo Inicial -->
      <div class="flex items-center space-x-4">
        <label class="font-semibold">Saldo Inicial:</label>
        <input
          type="number"
          v-model.number="saldo"
          @change="save()"
          class="border p-2 rounded w-32"
        />
      </div>

      <!-- Sección dinámica -->
      <div v-for="section in sections" :key="section.key" class="space-y-2">
        <h3 class="font-semibold capitalize">{{ section.label }}</h3>
        <button
          @click="addRow(section.key)"
          class="text-sm text-blue-600 hover:underline"
        >+ Agregar ítem</button>

        <div class="overflow-x-auto">
          <table class="min-w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-2">Nombre</th>
                <th v-for="m in 12" :key="m" class="p-2 text-center">Mes {{ m }}</th>
                <th class="p-2">Acción</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in flujo[section.key]" :key="i" class="border-t">
                <td class="p-2">
                  <input
                    type="text"
                    v-model="item.nombre"
                    @change="save()"
                    class="border p-1 rounded w-full"
                  />
                </td>
                <td
                  v-for="(val, j) in item.valores"
                  :key="j"
                  class="p-2 text-right"
                >
                  <input
                    type="number"
                    v-model.number="item.valores[j]"
                    @change="save()"
                    class="border p-1 rounded w-20"
                  />
                </td>
                <td class="p-2 text-center">
                  <button @click="removeRow(section.key, i)" class="text-red-600">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
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
  saldo_inicial: props.saldoInicial,
  ingresos: props.ingresos,
  gastos: props.gastos,
  financiamiento_recibido: props.financiamientoRecibido,
  pagos_financiamiento: props.pagosFinanciamiento,
})

const sections = [
  { key: 'ingresos', label: 'Ingresos' },
  { key: 'gastos', label: 'Gastos' },
  { key: 'financiamiento_recibido', label: 'Financiamiento Recibido' },
  { key: 'pagos_financiamiento', label: 'Pagos de Financiamiento' },
]

const flujo = reactive(form.data)
const saldo = ref(form.data.saldo_inicial)

function newItem() {
  return {
    nombre: '',
    valores: Array(12).fill(0),
  }
}

function addRow(section) {
  flujo[section].push(newItem())
  save()
}

function removeRow(section, index) {
  flujo[section].splice(index, 1)
  save()
}

function save() {
  form.data = {
    saldo_inicial: saldo.value,
    ingresos: flujo.ingresos,
    gastos: flujo.gastos,
    financiamiento_recibido: flujo.financiamiento_recibido,
    pagos_financiamiento: flujo.pagos_financiamiento,
  }
  form.put(route('flujo-caja.update', props.proyecto_id))
}
</script>
