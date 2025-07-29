
<template>
  <AppLayout title="Etapa 10">
    <template #header>
      <div class="flex items-center gap-2 mb-2">
        <Link class="hover:text-white transition-colors font-bold text-blue-700" :href="route('projects.stages.show', proyectoactual.id)">
          {{ proyectoactual.name }}
        </Link>
        <span class="text-gray-400">/</span>
        <h2 class="font-semibold text-2xl text-blue-800 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-1m5 2a1 1 0 001-1v-1m-6-2h6" /></svg>
          Etapa 10 – {{ isEditing ? 'Editar' : 'Subir' }} Documentos Necesarios
        </h2>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-200 py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white/90 p-8 rounded-2xl shadow-xl border border-blue-100">
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="w-full bg-blue-50 p-6 rounded-xl mb-6 border-2 border-dashed border-blue-300 flex flex-col items-center justify-center transition-shadow hover:shadow-lg">
              <div class="flex flex-col items-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16V4a1 1 0 011-1h14a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-1m5 2a1 1 0 001-1v-1m-6-2h6" /></svg>
                <p class="text-lg font-bold text-blue-700">Sube tus documentos necesarios</p>
                <span class="text-xs text-blue-500">PDF, Word, Excel, JPG, PNG. Puedes seleccionar varios archivos.</span>
              </div>
              <input
                type="file"
                name="documentos"
                accept=".pdf,.doc,.docx,.xlsx,.jpg,.jpeg,.png"
                multiple
                @change="handleFileChange"
                :disabled="form.processing"
                class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-all"
              />
              <p v-if="form.errors.documentos" class="mt-2 text-sm text-red-600 animate-pulse">
                {{ form.errors.documentos }}
              </p>
            </div>

            <!-- Previsualización nombres de archivos nuevos -->
            <transition-group name="fade" tag="ul" v-if="previewDocs.length" class="mt-3 flex flex-wrap gap-2">
              <li v-for="(name, idx) in previewDocs" :key="name" class="bg-green-100 text-green-700 px-3 py-1 rounded-full shadow text-xs font-semibold animate-fadein">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ name }}
              </li>
            </transition-group>

            <!-- Documentos ya guardados -->
            <div v-if="isEditing && existingDocs.length" class="mt-6">
              <p class="text-sm text-blue-600 mb-2 font-semibold">Documentos actuales:</p>
              <ul class="flex flex-wrap gap-2">
                <li v-for="(path, idx) in existingDocs" :key="path" class="flex items-center bg-blue-100 rounded-full px-3 py-1 shadow text-blue-700 text-xs font-semibold animate-fadein">
                  <a
                    :href="`/storage/${path}`"
                    target="_blank"
                    class="underline text-blue-700 mr-2 hover:text-blue-900"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ path.split('/').pop() }}
                  </a>
                  <button
                    type="button"
                    @click="deleteDocument(idx)"
                    class="ml-2 text-red-600 hover:text-red-800 hover:underline transition-colors"
                    :disabled="form.processing"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Eliminar
                  </button>
                </li>
              </ul>
            </div>

            <button
              type="submit"
              class="mt-8 w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow hover:bg-blue-700 transition-all disabled:opacity-50"
              :disabled="form.processing"
            >
              <span v-if="form.processing" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                {{ isEditing ? 'Actualizando...' : 'Guardando...' }}
              </span>
              <span v-else>{{ isEditing ? 'Actualizar' : 'Guardar' }}</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>


<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import Swal from 'sweetalert2'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  etapa10: Object,
  proyecto_id: Number,
  proyectoactual: Object
})

const isEditing = computed(() => Boolean(props.etapa10 && props.etapa10.id))

// Inertia form
const form = useForm({
  proyecto_id: props.proyecto_id,
  documentos: []
})

// Preview de nombres de archivos seleccionados
const previewDocs = ref([])

// Documentos existentes (array de rutas)
const existingDocs = computed(() => {
  if (!props.etapa10 || !props.etapa10.document_paths) return []
  try {
    return JSON.parse(props.etapa10.document_paths)
  } catch {
    return []
  }
})

function handleFileChange(event) {
  const files = Array.from(event.target.files)
  const allowed = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'image/jpeg',
    'image/png'
  ]

  form.documentos = []
  previewDocs.value = []

  files.forEach(file => {
    if (!allowed.includes(file.type)) {
      Swal.fire('Tipo no válido', 'Solo PDF, Word, Excel, JPG y PNG.', 'warning')
      return
    }
    form.documentos.push(file)
    previewDocs.value.push(file.name)
  })
}

function submit() {
  // Si estamos creando, asegurar que haya al menos un archivo
  if (!isEditing.value && form.documentos.length === 0) {
    Swal.fire('Campo requerido', 'Debes seleccionar al menos un documento.', 'warning')
    return
  }

  // Elegimos URL según modo (store o update)
  const url = isEditing.value
    ? route('etapa10.update', props.etapa10.id)  // POST a /etapa10/{id}
    : route('etapa10.store')                     // POST a /etapa10

  form.post(url, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire(
        'Listo',
        isEditing.value
          ? 'Etapa 10 actualizada correctamente.'
          : 'Documentos guardados correctamente.',
        'success'
      )
      previewDocs.value = []
      form.documentos = []
    },
    onError: errors => {
      console.error(errors)
      Swal.fire('Error', Object.values(errors).join('<br>'), 'error')
    }
  })
}

function deleteDocument(index) {
  Swal.fire({
    title: '¿Eliminar documento?',
    text: 'Este documento se borrará permanentemente.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (!result.isConfirmed) return

    form.delete(route('etapa10.destroyDocument', [props.etapa10.id, index]), {
      preserveScroll: true,
      onSuccess: () => {
        Swal.fire('Eliminado', 'Documento eliminado correctamente.', 'success')
      },
      onError: errors => {
        console.error(errors)
        Swal.fire('Error', 'No se pudo eliminar el documento.', 'error')
      }
    })
  })
}
</script>
<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.animate-fadein {
  animation: fadein 0.7s;
}
@keyframes fadein {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>