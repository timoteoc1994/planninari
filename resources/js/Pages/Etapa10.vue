<template>
  <AppLayout title="Etapa 10">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Etapa 10 – {{ isEditing ? 'Editar' : 'Subir' }} Documentos Necesarios
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="w-full bg-gray-100 p-6 rounded mb-4">
            <p class="text-lg font-semibold mb-2">Documentos necesarios</p>
            <input
              type="file"
              name="documentos"
              accept=".pdf,.doc,.docx,.xlsx,.jpg,.jpeg,.png"
              multiple
              @change="handleFileChange"
              :disabled="form.processing"
              class="block w-full text-sm text-gray-700"
            />
            <p v-if="form.errors.documentos" class="mt-2 text-sm text-red-600">
              {{ form.errors.documentos }}
            </p>

            <!-- Previsualización nombres de archivos nuevos -->
            <ul v-if="previewDocs.length" class="mt-3 list-disc list-inside text-sm text-green-600">
              <li v-for="(name, idx) in previewDocs" :key="idx">{{ name }}</li>
            </ul>

            <!-- Documentos ya guardados -->
            <div v-if="isEditing && existingDocs.length" class="mt-4">
              <p class="text-sm text-gray-600 mb-2">Documentos actuales:</p>
              <ul class="list-disc list-inside text-sm text-gray-700">
                <li v-for="(path, idx) in existingDocs" :key="idx" class="flex items-center justify-between">
                  <a
                    :href="`/storage/${path}`"
                    target="_blank"
                    class="underline text-blue-600 mr-4"
                  >
                    {{ path.split('/').pop() }}
                  </a>
                  <button
                    type="button"
                    @click="deleteDocument(idx)"
                    class="text-red-600 hover:underline"
                    :disabled="form.processing"
                  >
                    Eliminar
                  </button>
                </li>
              </ul>
            </div>
          </div>

          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
            :disabled="form.processing"
          >
            {{ form.processing
              ? (isEditing ? 'Actualizando...' : 'Guardando...')
              : (isEditing ? 'Actualizar' : 'Guardar') }}
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import Swal from 'sweetalert2'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  etapa10: Object,
  proyecto_id: Number,
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