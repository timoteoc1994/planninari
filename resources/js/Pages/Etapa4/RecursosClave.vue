<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  proyecto_id: Number,
  disponibles: Array,
  necesarios: Array,
})

// Control de modales
const showModalDisp = ref(false)
const showModalNec = ref(false)

// Dropdown options
const tiposRecurso = [
  'Equipos y materiales',
  'Humanos',
  'Infraestructura',
  'Intelectuales',
  'Materiales e insumos',
  'Tecnológicos',
  'Otras'
]
const estados = ['Malo', 'Regular', 'Bueno', 'Excelente']
const importancias = ['Alto', 'Mediano', 'Bajo']

// Form for disponibles
const disponibleForm = useForm({
  recursos_clave_id: props.proyecto_id,
  tipo_recurso: tiposRecurso[0],
  descripcion: '',
  cantidad: '',
  estado: estados[0],
  importancia: importancias[0],
  descripcion_importancia: '',
})
let editDispId = ref(null)

// Form for necesarios
const necesarioForm = useForm({
  recursos_clave_id: props.proyecto_id,
  tipo_recurso: tiposRecurso[0],
  descripcion: '',
  cantidad: '',
  aporte_objetivo: '',
  porcentaje: '',
  precio: '',
  puede_adquirir: false,
})
let editNecId = ref(null)

function openNewDisponible() {
  editDispId.value = null
  disponibleForm.reset()
  disponibleForm.tipo_recurso = tiposRecurso[0]
  disponibleForm.estado = estados[0]
  disponibleForm.importancia = importancias[0]
  showModalDisp.value = true
}

function editDisponible(item) {
  editDispId.value = item.id
  disponibleForm.tipo_recurso = item.tipo_recurso
  disponibleForm.descripcion = item.descripcion
  disponibleForm.cantidad = item.cantidad
  disponibleForm.estado = item.estado
  disponibleForm.importancia = item.importancia
  disponibleForm.descripcion_importancia = item.descripcion_importancia
  showModalDisp.value = true
}

function submitDisponible() {
  const method = editDispId.value ? 'put' : 'post'
  const url = editDispId.value
    ? route('recursos.disponibles.update', editDispId.value)
    : route('recursos.disponibles.store')
  disponibleForm[method](url, {
    onSuccess: () => {
      showModalDisp.value = false
      disponibleForm.reset()
    },
  })
}

function deleteDisponible(id) {
  if (confirm('¿Eliminar recurso disponible?')) {
    disponibleForm.delete(route('recursos.disponibles.destroy', id))
  }
}

function openNewNecesario() {
  editNecId.value = null
  necesarioForm.reset()
  necesarioForm.tipo_recurso = tiposRecurso[0]
  necesarioForm.puede_adquirir = false
  showModalNec.value = true
}

function editNecesario(item) {
  editNecId.value = item.id
  necesarioForm.tipo_recurso = item.tipo_recurso
  necesarioForm.descripcion = item.descripcion
  necesarioForm.cantidad = item.cantidad
  necesarioForm.aporte_objetivo = item.aporte_objetivo
  necesarioForm.porcentaje = item.porcentaje
  necesarioForm.precio = item.precio
  necesarioForm.puede_adquirir = item.puede_adquirir === 1 || item.puede_adquirir === true
  showModalNec.value = true
}

function submitNecesario() {
  const method = editNecId.value ? 'put' : 'post'
  const url = editNecId.value
    ? route('recursos.necesarios.update', editNecId.value)
    : route('recursos.necesarios.store')
  necesarioForm[method](url, {
    onSuccess: () => {
      showModalNec.value = false
      necesarioForm.reset()
    },
  })
}

function deleteNecesario(id) {
  if (confirm('¿Eliminar recurso necesario?')) {
    necesarioForm.delete(route('recursos.necesarios.destroy', id))
  }
}
</script>

<template>
   <AppLayout title="Etapa 4 – Recursos Clave">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800">Recursos Clave</h2>
    </template>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12">

      <!-- Recursos Disponibles -->
      <section>
        <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
          <h3 class="text-lg font-bold">Recursos Disponibles</h3>
          <button @click="openNewDisponible" class="px-4 py-2 bg-blue-600 text-white rounded">
            Nuevo Disponible
          </button>
        </div>
        <div class="overflow-x-auto rounded shadow bg-white">
          <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
              <tr class="text-left text-sm">
                <th class="px-4 py-2">Tipo</th>
                <th class="px-4 py-2">Descripción</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Importancia</th>
                <th class="px-4 py-2">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in disponibles" :key="item.id" class="border-b text-sm">
                <td class="px-4 py-2">{{ item.tipo_recurso }}</td>
                <td class="px-4 py-2">{{ item.descripcion }}</td>
                <td class="px-4 py-2">{{ item.cantidad }}</td>
                <td class="px-4 py-2">{{ item.estado }}</td>
                <td class="px-4 py-2">{{ item.importancia }}</td>
                <td class="px-4 py-2 flex flex-wrap gap-2">
                  <button @click="editDisponible(item)" class="text-blue-600 hover:underline">Editar</button>
                  <button @click="deleteDisponible(item.id)" class="text-red-600 hover:underline">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Recursos Necesarios -->
      <section>
        <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
          <h3 class="text-lg font-bold">Recursos Necesarios</h3>
          <button @click="openNewNecesario" class="px-4 py-2 bg-blue-600 text-white rounded">
            Nuevo Necesario
          </button>
        </div>
        <div class="overflow-x-auto rounded shadow bg-white">
          <table class="min-w-full table-auto">
            <thead class="bg-gray-100 text-sm">
              <tr class="text-left">
                <th class="px-4 py-2">Tipo</th>
                <th class="px-4 py-2">Descripción</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Aporte</th>
                <th class="px-4 py-2">%</th>
                <th class="px-4 py-2">Precio</th>
                <th class="px-4 py-2">Puede Adquirir</th>
                <th class="px-4 py-2">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in necesarios" :key="item.id" class="border-b text-sm">
                <td class="px-4 py-2">{{ item.tipo_recurso }}</td>
                <td class="px-4 py-2">{{ item.descripcion }}</td>
                <td class="px-4 py-2">{{ item.cantidad }}</td>
                <td class="px-4 py-2">{{ item.aporte_objetivo }}</td>
                <td class="px-4 py-2">{{ item.porcentaje }}%</td>
                <td class="px-4 py-2">{{ item.precio }}</td>
                <td class="px-4 py-2">{{ item.puede_adquirir ? 'Sí' : 'No' }}</td>
                <td class="px-4 py-2 flex flex-wrap gap-2">
                  <button @click="editNecesario(item)" class="text-blue-600 hover:underline">Editar</button>
                  <button @click="deleteNecesario(item.id)" class="text-red-600 hover:underline">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Modal Nuevo Recurso Disponible -->
      <div v-if="showModalDisp" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h4 class="text-lg font-semibold mb-4">{{ editDispId ? 'Editar' : 'Nuevo' }} Recurso Disponible</h4>
          <form @submit.prevent="submitDisponible" class="space-y-3">
            <div>
              <label class="block text-sm">Tipo de Recurso</label>
              <select v-model="disponibleForm.tipo_recurso" class="mt-1 w-full border-gray-300 rounded">
                <option v-for="t in tiposRecurso" :key="t">{{ t }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm">Descripción</label>
              <input
                type="text"
                v-model="disponibleForm.descripcion"
                class="mt-1 w-full border-gray-300 rounded"
              />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm">Cantidad</label>
                <input
                  type="number"
                  v-model="disponibleForm.cantidad"
                  class="mt-1 w-full border-gray-300 rounded"
                />
              </div>
              <div>
                <label class="block text-sm">Estado</label>
                <select v-model="disponibleForm.estado" class="mt-1 w-full border-gray-300 rounded">
                  <option v-for="e in estados" :key="e">{{ e }}</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-sm">Importancia</label>
              <select v-model="disponibleForm.importancia" class="mt-1 w-full border-gray-300 rounded">
                <option v-for="i in importancias" :key="i">{{ i }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm">Detalle Importancia</label>
              <textarea
                v-model="disponibleForm.descripcion_importancia"
                rows="2"
                class="mt-1 w-full border-gray-300 rounded"
              ></textarea>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
              <button @click="showModalDisp = false" type="button" class="px-4 py-2">Cancelar</button>
              <button
                type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded"
                :disabled="disponibleForm.processing"
              >Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal Nuevo Recurso Necesario -->
      <div v-if="showModalNec" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
          <h4 class="text-lg font-semibold mb-4">{{ editNecId ? 'Editar' : 'Nuevo' }} Recurso Necesario</h4>
          <form @submit.prevent="submitNecesario" class="space-y-3">
            <div>
              <label class="block text-sm">Tipo de Recurso</label>
              <select v-model="necesarioForm.tipo_recurso" class="mt-1 w-full border-gray-300 rounded">
                <option v-for="t in tiposRecurso" :key="t">{{ t }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm">Descripción</label>
              <input
                type="text"
                v-model="necesarioForm.descripcion"
                class="mt-1 w-full border-gray-300 rounded"
              />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm">Cantidad</label>
                <input
                  type="number"
                  v-model="necesarioForm.cantidad"
                  class="mt-1 w-full border-gray-300 rounded"
                />
              </div>
              <div>
                <label class="block text-sm">%</label>
                <input
                  type="number"
                  step="0.01"
                  v-model="necesarioForm.porcentaje"
                  class="mt-1 w-full border-gray-300 rounded"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm">Aporte Objetivo</label>
              <textarea
                v-model="necesarioForm.aporte_objetivo"
                rows="2"
                class="mt-1 w-full border-gray-300 rounded"
              ></textarea>
            </div>
            <div>
              <label class="block text-sm">Precio</label>
              <input
                type="number"
                step="0.01"
                v-model="necesarioForm.precio"
                class="mt-1 w-full border-gray-300 rounded"
              />
            </div>
            <div>
              <label class="block text-sm">Puede Adquirir</label>
              <select v-model="necesarioForm.puede_adquirir" class="mt-1 w-full border-gray-300 rounded">
                <option :value="false">No</option>
                <option :value="true">Sí</option>
              </select>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
              <button @click="showModalNec = false" type="button" class="px-4 py-2">Cancelar</button>
              <button
                type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded"
                :disabled="necesarioForm.processing"
              >Guardar</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<style scoped>
/* Estilos adicionales si los necesitas */
</style>
