<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  proyecto_id: Number,
  disponibles: Array,
  necesarios: Array,
  inversiones: Array,
  proyectoactual: Object
})

console.log('📦 Props iniciales:', props)

// Estado modal y edición
const showModal = ref(false)
const editId    = ref(null)

// Formulario de inversión
const inversionForm = useForm({
  proyecto_id: props.proyecto_id,
  tipo_origen: 'disponible',
  recurso_id:  null,
  cantidad:   '',
  precio_unitario: '',
  clasificacion:   '',
  vida_util:       '',
})

console.log('🧾 Form inicial:', inversionForm)

// Obtener IDs de recursos ya utilizados (excluyendo el que se está editando)
const recursosUtilizados = computed(() => {
  const tipo = inversionForm.tipo_origen
  return props.inversiones
    .filter(inv => inv.tipo_origen === tipo) 
    .filter(inv => editId.value === null || inv.id !== editId.value)
    .filter(inv => inv.recurso_id)
    .map(inv => inv.recurso_id)
})


// Opciones de recursos según origen, filtradas por recursos no utilizados
const recursoOptions = computed(() => {
  const recursos = inversionForm.tipo_origen === 'disponible'
    ? props.disponibles
    : props.necesarios
  const filtrados = recursos.filter(recurso => !recursosUtilizados.value.includes(recurso.id))
  console.log(`📋 Recursos ${inversionForm.tipo_origen}:`, filtrados)
  return filtrados
})

// Abrir modal para nueva inversión
function openNew() {
  console.log('🟢 Abrir NUEVA inversión')
  editId.value = null
  inversionForm.reset()
  inversionForm.tipo_origen = 'disponible'
  showModal.value = true
}

// Cargar datos al editar
function openEdit(item) {
  console.log('✏️ Editar inversión:', item)
  editId.value = item.id
  inversionForm.tipo_origen     = item.tipo_origen
  inversionForm.recurso_id      = item.recurso_id
  inversionForm.cantidad        = item.cantidad
  inversionForm.precio_unitario = item.precio_unitario
  inversionForm.clasificacion   = item.clasificacion
  inversionForm.vida_util       = item.vida_util
  showModal.value = true
}

// Enviar formulario (POST o PUT)
function submit() {
  const method = editId.value ? 'put' : 'post'
  const url = editId.value
    ? route('etapa7.inversiones.update', editId.value)
    : route('etapa7.inversiones.store')

  console.log('📤 Enviando inversión (form):', inversionForm.data())
  console.log('🔗 Método:', method.toUpperCase(), '| URL:', url)

  inversionForm[method](url, {
    onSuccess: () => {
      console.log('✅ Éxito al guardar inversión.')
      showModal.value = false
      inversionForm.reset()

      Swal.fire({
        icon: 'success',
        title: editId.value ? 'Inversión actualizada correctamente' : 'Inversión registrada correctamente',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      }).then(() => {
        window.location.reload() // ⚠️ Esta recarga hace que pierdas datos dinámicos, es intencional en este caso
      })
    },
    onError: (errors) => {
      console.error('❌ Error al guardar inversión:', errors)
      Swal.fire({
        icon: 'error',
        title: 'Ocurrió un problema al guardar la inversión.',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
      })
    }
  })
}

// Borrar inversión
async function remove(id) {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: 'Esta acción eliminará la inversión.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  })

  if (result.isConfirmed) {
    console.log('🗑 Eliminando inversión ID:', id)
    inversionForm.delete(route('etapa7.inversiones.destroy', id), {
      onSuccess: () => {
        console.log('✅ Inversión eliminada')
        Swal.fire({
          icon: 'success',
          title: 'Inversión eliminada correctamente',
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true
        })
      },
      onError: (e) => {
        console.error('❌ Error al eliminar:', e)
        Swal.fire({
          icon: 'error',
          title: 'No se pudo eliminar la inversión.',
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        })
      }
    })
  }
}

const inversionesDisponibles = computed(() => {
  const disponibles = props.inversiones.filter(inv => inv.tipo_origen === 'disponible')
  console.log('📗 Inversiones disponibles:', disponibles)
  return disponibles
})

const inversionesNecesarias = computed(() => {
  const necesarias = props.inversiones.filter(inv => inv.tipo_origen === 'necesario')
  console.log('📘 Inversiones necesarias:', necesarias)
  return necesarias
})
</script>


<template>
  <AppLayout title="Detalle de Inversión">
    <template #header>
      <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 py-8">
        <div class="max-w-4xl mx-auto px-4">
          <nav class="flex items-center space-x-2 text-indigo-100 mb-4">
            <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
              {{ proyectoactual.name }}
            </Link>
            <span>/</span>
            <span class="text-white font-medium">Etapa 7</span>
          </nav>
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
              7
            </div>
            <div>
              <h2 class="font-bold text-3xl text-white leading-tight">
                Inversiones
              </h2>
              <p class="text-indigo-100 mt-1">Registra y gestiona las inversiones necesarias para tu proyecto</p>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50 py-12">
      <div class="max-w-7xl mx-auto px-4 space-y-6">
        <!-- Panel educativo -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-indigo-500">
          <div class="flex items-center mb-4">
            <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-xl font-bold text-indigo-800">¿Por qué es importante registrar las inversiones?</h3>
          </div>
          <p class="text-gray-700 mb-2">Llevar un control de las inversiones permite planificar el capital necesario, optimizar recursos y asegurar la viabilidad financiera del proyecto.</p>
          <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
            <li>Identifica los recursos clave y su costo.</li>
            <li>Facilita la toma de decisiones financieras.</li>
            <li>Permite evaluar la rentabilidad y sostenibilidad.</li>
          </ul>
        </div>

        <!-- Header con botón -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
          <h3 class="text-lg sm:text-xl font-bold text-gray-800">Inversiones Registradas</h3>
          <button
            @click="openNew"
            class="modern-btn-indigo self-start sm:self-auto"
          >
            <span class="hidden sm:inline">Nueva Inversión</span>
            <span class="sm:hidden">+ Nuevo</span>
          </button>
        </div>

        <!-- Tabla: Recursos Disponibles -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <h3 class="text-base font-semibold bg-green-50 px-4 py-3 text-green-800">Recursos Disponibles</h3>
          <div class="w-full overflow-x-auto">
            <table class="min-w-full table-auto">
              <thead class="bg-gray-50">
                <tr class="text-left">
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Recurso</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Cantidad</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Precio Unit.</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Clasificación</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Vida útil</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Acciones</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr
                  v-for="inv in inversionesDisponibles"
                  :key="inv.id"
                  class="hover:bg-gray-50"
                >
                  <td class="px-4 py-3">
                    <div class="font-medium">{{ inv.recurso?.tipo_recurso || 'Recurso no encontrado' }}</div>
                    <div class="text-gray-500">{{ inv.recurso?.descripcion || 'Sin descripción' }}</div>
                  </td>
                  <td class="px-4 py-3">{{ inv.cantidad }}</td>
                  <td class="px-4 py-3">${{ inv.precio_unitario }}</td>
                  <td class="px-4 py-3">{{ inv.clasificacion }}</td>
                  <td class="px-4 py-3">{{ inv.vida_util }} años</td>
                  <td class="px-4 py-3">
                    <div class="flex space-x-2">
                      <button @click="openEdit(inv)" class="text-indigo-600 hover:text-indigo-800 font-medium">Editar</button>
                      <button @click="remove(inv.id)" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tabla: Recursos Necesarios -->
        <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
          <h3 class="text-base font-semibold bg-blue-50 px-4 py-3 text-blue-800">Recursos Necesarios</h3>
          <div class="w-full overflow-x-auto">
            <table class="min-w-full table-auto">
              <thead class="bg-gray-50">
                <tr class="text-left">
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Recurso</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Cantidad</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Precio Unit.</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Clasificación</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Vida útil</th>
                  <th class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">Acciones</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr
                  v-for="inv in inversionesNecesarias"
                  :key="inv.id"
                  class="hover:bg-gray-50"
                >
                  <td class="px-4 py-3">
                    <div class="font-medium">{{ inv.recurso?.tipo_recurso || 'Recurso no encontrado' }}</div>
                    <div class="text-gray-500">{{ inv.recurso?.descripcion || 'Sin descripción' }}</div>
                  </td>
                  <td class="px-4 py-3">{{ inv.cantidad }}</td>
                  <td class="px-4 py-3">${{ inv.precio_unitario }}</td>
                  <td class="px-4 py-3">{{ inv.clasificacion }}</td>
                  <td class="px-4 py-3">{{ inv.vida_util }} años</td>
                  <td class="px-4 py-3">
                    <div class="flex space-x-2">
                      <button @click="openEdit(inv)" class="text-indigo-600 hover:text-indigo-800 font-medium">Editar</button>
                      <button @click="remove(inv.id)" class="text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Modal Inversión -->
        <div
          v-if="showModal"
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
        >
          <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6">
              <div class="flex justify-between items-center mb-6">
                <h4 class="text-lg font-semibold text-gray-900">
                  {{ editId ? 'Editar' : 'Nueva' }} Inversión
                </h4>
                <button
                  @click="showModal = false"
                  class="text-gray-400 hover:text-gray-600"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>

              <form @submit.prevent="submit" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Disponibilidad del recurso
                  </label>
                  <select
                    v-model="inversionForm.tipo_origen"
                    class="modern-input-indigo"
                  >
                    <option value="disponible">Recursos Disponibles</option>
                    <option value="necesario">Recursos Necesarios</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Seleccione recurso
                  </label>
                  <select
                    v-model="inversionForm.recurso_id"
                    class="modern-input-indigo"
                  >
                    <option :value="null" disabled>Seleccione...</option>
                    <option
                      v-for="r in recursoOptions"
                      :key="r.id"
                      :value="r.id"
                    >
                      {{ r.tipo_recurso }} – {{ r.descripcion }}
                    </option>
                  </select>
                  <!-- Mensaje informativo si no hay recursos disponibles -->
                  <p v-if="recursoOptions.length === 0" class="text-sm text-gray-500 mt-1">
                    No hay recursos {{ inversionForm.tipo_origen === 'disponible' ? 'disponibles' : 'necesarios' }} sin utilizar.
                  </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Cantidad
                    </label>
                    <input
                      type="number"
                      v-model="inversionForm.cantidad"
                      class="modern-input-indigo"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Precio unitario
                    </label>
                    <input
                      type="number"
                      step="0.01"
                      v-model="inversionForm.precio_unitario"
                      class="modern-input-indigo"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Clasificación
                  </label>
                  <input
                    type="text"
                    v-model="inversionForm.clasificacion"
                    class="modern-input-indigo"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Vida útil (años)
                  </label>
                  <input
                    type="number"
                    v-model="inversionForm.vida_util"
                    class="modern-input-indigo"
                  />
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4">
                  <button
                    @click="showModal = false"
                    type="button"
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors"
                  >
                    Cancelar
                  </button>
                  <button
                    type="submit"
                    class="modern-btn-indigo"
                    :disabled="recursoOptions.length === 0"
                    :class="{ 'opacity-50 cursor-not-allowed': recursoOptions.length === 0 }"
                  >
                    Guardar
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Botón moderno indigo */
.modern-btn-indigo {
  display: inline-flex;
  align-items: center;
  background: linear-gradient(90deg, #6366f1 0%, #818cf8 100%);
  color: #fff;
  font-weight: 600;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px 0 rgba(99,102,241,0.10);
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.modern-btn-indigo:hover {
  background: linear-gradient(90deg, #818cf8 0%, #6366f1 100%);
  transform: scale(1.04);
}

/* Input moderno indigo */
.modern-input-indigo {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #818cf8;
  background: linear-gradient(135deg, #e0e7ff 0%, #f5f3ff 100%);
  padding: 0.75rem 1rem;
  font-size: 1rem;
  color: #6366f1;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(99,102,241,0.08);
}
.modern-input-indigo:focus {
  border-color: #6366f1;
  outline: none;
  box-shadow: 0 0 0 2px #818cf8;
}
</style>