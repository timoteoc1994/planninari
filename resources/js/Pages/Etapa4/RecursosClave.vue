<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import MenuNavegacion from './MenuNavegacion.vue';
const props = defineProps({
  proyecto_id: Number,
  disponibles: Array,
  necesarios: Array,
  proyectoactual: Object,
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
      Swal.fire({
        icon: 'success',
        title: editDispId.value ? 'Recurso disponible actualizado' : 'Recurso disponible registrado',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Error al guardar recurso disponible',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
      })
    }
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
      Swal.fire({
        icon: 'success',
        title: editNecId.value ? 'Recurso necesario actualizado' : 'Recurso necesario registrado',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Error al guardar recurso necesario',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
      })
    }
  })
}


async function deleteNecesario(id) {
  const result = await Swal.fire({
    title: '¿Eliminar recurso necesario?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  })

  if (result.isConfirmed) {
    necesarioForm.delete(route('recursos.necesarios.destroy', id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Recurso eliminado',
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true
        })
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'Error al eliminar recurso',
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        })
      }
    })
  }
}

</script>

<template>
   <AppLayout title="Etapa 4  Recursos Clave">
    <template #header>
      <div class="bg-gradient-to-r from-emerald-600 to-emerald-400 py-8">
        <div class="max-w-6xl mx-auto px-4">
          <nav class="flex items-center space-x-2 text-emerald-100 mb-4">
            <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
              {{ proyectoactual.name }}
            </Link>
            <span>/</span>
            <Link class="hover:text-white transition-colors" :href="route('etapa4', proyectoactual.id)">
              Modelo de Negocio
            </Link>
            <span>/</span>
            <span class="text-white font-medium">Recursos Clave</span>
          </nav>
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <div>
              <h2 class="font-bold text-3xl text-white leading-tight">Recursos Clave</h2>
              <p class="text-emerald-100 mt-1">Define los recursos más importantes que necesitas para operar tu modelo de negocio</p>
            </div>
          </div>
        </div>
      </div>
    </template>

     <!-- Menú de navegación entre secciones -->
        <MenuNavegacion :proyecto_id="proyecto_id" :proyectoactual="proyectoactual" :actual="actual" />
    

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-emerald-50 py-8">
      <div class="max-w-7xl mx-auto px-4 space-y-12">
        <!-- Panel educativo -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-emerald-500">
          <div class="flex items-center mb-4">
            <svg class="w-7 h-7 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="text-xl font-bold text-emerald-800">¿Qué son los Recursos Clave?</h3>
          </div>
          <p class="text-gray-700 mb-2">Son los activos más importantes que tu empresa necesita para que su modelo de negocio funcione. Incluyen recursos físicos, humanos, intelectuales y financieros.</p>
          <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
            <li>¿Qué recursos son esenciales para tu propuesta de valor?</li>
            <li>¿Cuáles son imprescindibles para llegar a tus clientes?</li>
            <li>¿Qué recursos requieren tus canales, relaciones y fuentes de ingresos?</li>
          </ul>
        </div>
      

      <!-- Recursos Disponibles -->
      <section>
        <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
          <h3 class="text-lg font-bold text-emerald-800">Recursos Disponibles</h3>
          <button @click="openNewDisponible" class="modern-btn-emerald">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Disponible
          </button>
        </div>
        <div class="overflow-x-auto rounded-2xl shadow bg-white">
          <table class="min-w-full table-auto">
            <thead class="bg-emerald-50">
              <tr class="text-left text-sm text-emerald-700">
                <th class="px-4 py-2">Tipo</th>
                <th class="px-4 py-2">Descripción</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Importancia</th>
                <th class="px-4 py-2">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in disponibles" :key="item.id" class="border-b text-sm hover:bg-emerald-50 transition">
                <td class="px-4 py-2">{{ item.tipo_recurso }}</td>
                <td class="px-4 py-2">{{ item.descripcion }}</td>
                <td class="px-4 py-2">{{ item.cantidad }}</td>
                <td class="px-4 py-2">{{ item.estado }}</td>
                <td class="px-4 py-2">{{ item.importancia }}</td>
                <td class="px-4 py-2 flex flex-wrap gap-2">
                  <button @click="editDisponible(item)" class="modern-link-emerald">Editar</button>
                  <button @click="deleteDisponible(item.id)" class="modern-link-red">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>


      <!-- Recursos Necesarios -->
      <section>
        <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
          <h3 class="text-lg font-bold text-emerald-800">Recursos Necesarios</h3>
          <button @click="openNewNecesario" class="modern-btn-emerald">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Necesario
          </button>
        </div>
        <div class="overflow-x-auto rounded-2xl shadow bg-white">
          <table class="min-w-full table-auto">
            <thead class="bg-emerald-50 text-sm text-emerald-700">
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
              <tr v-for="item in necesarios" :key="item.id" class="border-b text-sm hover:bg-emerald-50 transition">
                <td class="px-4 py-2">{{ item.tipo_recurso }}</td>
                <td class="px-4 py-2">{{ item.descripcion }}</td>
                <td class="px-4 py-2">{{ item.cantidad }}</td>
                <td class="px-4 py-2">{{ item.aporte_objetivo }}</td>
                <td class="px-4 py-2">{{ item.porcentaje }}%</td>
                <td class="px-4 py-2">{{ item.precio }}</td>
                <td class="px-4 py-2">{{ item.puede_adquirir ? 'Sí' : 'No' }}</td>
                <td class="px-4 py-2 flex flex-wrap gap-2">
                  <button @click="editNecesario(item)" class="modern-link-emerald">Editar</button>
                  <button @click="deleteNecesario(item.id)" class="modern-link-red">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      </div>

      <!-- Modal Nuevo Recurso Disponible -->
      <div v-if="showModalDisp" class="fixed inset-0 bg-emerald-900 bg-opacity-60 flex items-center justify-center z-50">
        <div class="modal-anim w-full max-w-md mx-auto p-6 rounded-2xl shadow-2xl bg-white border-l-4 border-emerald-500">
          <h4 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            {{ editDispId ? 'Editar' : 'Nuevo' }} Recurso Disponible
          </h4>
          <form @submit.prevent="submitDisponible" class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-emerald-700">Tipo de Recurso</label>
              <select v-model="disponibleForm.tipo_recurso" class="modern-input">
                <option v-for="t in tiposRecurso" :key="t">{{ t }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-emerald-700">Descripción</label>
              <input type="text" v-model="disponibleForm.descripcion" class="modern-input" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-emerald-700">Cantidad</label>
                <input type="number" v-model="disponibleForm.cantidad" class="modern-input" />
              </div>
              <div>
                <label class="block text-sm font-medium text-emerald-700">Estado</label>
                <select v-model="disponibleForm.estado" class="modern-input">
                  <option v-for="e in estados" :key="e">{{ e }}</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-emerald-700">Importancia</label>
              <select v-model="disponibleForm.importancia" class="modern-input">
                <option v-for="i in importancias" :key="i">{{ i }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-emerald-700">Detalle Importancia</label>
              <textarea v-model="disponibleForm.descripcion_importancia" rows="2" class="modern-textarea"></textarea>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
              <button @click="showModalDisp = false" type="button" class="modern-btn-gray">Cancelar</button>
              <button type="submit" class="modern-btn-emerald" :disabled="disponibleForm.processing">Guardar</button>
            </div>
          </form>
        </div>
      </div>


      <!-- Modal Nuevo Recurso Necesario -->
      <div v-if="showModalNec" class="fixed inset-0 bg-emerald-900 bg-opacity-60 flex items-center justify-center z-50">
        <div class="modal-anim w-full max-w-md mx-auto p-6 rounded-2xl shadow-2xl bg-white border-l-4 border-emerald-500">
          <h4 class="text-lg font-bold text-emerald-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            {{ editNecId ? 'Editar' : 'Nuevo' }} Recurso Necesario
          </h4>
          <form @submit.prevent="submitNecesario" class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-emerald-700">Tipo de Recurso</label>
              <select v-model="necesarioForm.tipo_recurso" class="modern-input">
                <option v-for="t in tiposRecurso" :key="t">{{ t }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-emerald-700">Descripción</label>
              <input type="text" v-model="necesarioForm.descripcion" class="modern-input" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-emerald-700">Cantidad</label>
                <input type="number" v-model="necesarioForm.cantidad" class="modern-input" />
              </div>
              <div>
                <label class="block text-sm font-medium text-emerald-700">%</label>
                <input type="number" step="0.01" v-model="necesarioForm.porcentaje" class="modern-input" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-emerald-700">Aporte Objetivo</label>
              <textarea v-model="necesarioForm.aporte_objetivo" rows="2" class="modern-textarea"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-emerald-700">Precio</label>
              <input type="number" step="0.01" v-model="necesarioForm.precio" class="modern-input" />
            </div>
            <div>
              <label class="block text-sm font-medium text-emerald-700">Puede Adquirir</label>
              <select v-model="necesarioForm.puede_adquirir" class="modern-input">
                <option :value="false">No</option>
                <option :value="true">Sí</option>
              </select>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
              <button @click="showModalNec = false" type="button" class="modern-btn-gray">Cancelar</button>
              <button type="submit" class="modern-btn-emerald" :disabled="necesarioForm.processing">Guardar</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<style scoped>
/* Modern Emerald Styles */
.modern-btn-emerald {
  display: inline-flex;
  align-items: center;
  background: linear-gradient(90deg, #34d399 0%, #059669 100%);
  color: #fff;
  font-weight: 600;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px 0 rgba(16,185,129,0.10);
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.modern-btn-emerald:hover {
  background: linear-gradient(90deg, #059669 0%, #34d399 100%);
  transform: scale(1.04);
}
.modern-btn-gray {
  display: inline-flex;
  align-items: center;
  background: #e5e7eb;
  color: #374151;
  font-weight: 500;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.modern-btn-gray:hover {
  background: #d1d5db;
  transform: scale(1.02);
}
.modern-link-emerald {
  color: #059669;
  font-weight: 600;
  text-decoration: underline;
  transition: color 0.2s;
}
.modern-link-emerald:hover {
  color: #065f46;
}
.modern-link-red {
  color: #dc2626;
  font-weight: 600;
  text-decoration: underline;
  transition: color 0.2s;
}
.modern-link-red:hover {
  color: #991b1b;
}
.modern-input {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #6ee7b7;
  background: linear-gradient(135deg, #d1fae5 0%, #f0fdf4 100%);
  padding: 0.75rem 1rem;
  font-size: 1rem;
  color: #065f46;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(16,185,129,0.08);
}
.modern-input:focus {
  border-color: #059669;
  outline: none;
  box-shadow: 0 0 0 2px #6ee7b7;
}
.modern-textarea {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #6ee7b7;
  background: linear-gradient(135deg, #d1fae5 0%, #f0fdf4 100%);
  padding: 1rem;
  font-size: 1rem;
  color: #065f46;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(16,185,129,0.08);
  resize: vertical;
}
.modern-textarea:focus {
  border-color: #059669;
  outline: none;
  box-shadow: 0 0 0 2px #6ee7b7;
}
.modal-anim {
  animation: modalIn 0.25s cubic-bezier(0.4,0,0.2,1);
}
@keyframes modalIn {
  from { opacity: 0; transform: translateY(40px) scale(0.98); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}
@media (max-width: 640px) {
  .modern-btn-emerald, .modern-btn-gray {
    width: 100%;
    justify-content: center;
  }
  .p-8 {
    padding: 1.25rem !important;
  }
}
</style>
