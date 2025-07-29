<template>
  <AppLayout title="Etapa 9">
    <template #header>
      <div class="bg-gradient-to-r from-cyan-600 to-cyan-400 py-8">
        <div class="max-w-4xl mx-auto px-4">
          <nav class="flex items-center space-x-2 text-cyan-100 mb-4">
            <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
              {{ proyectoactual.name }}
            </Link>
            <span>/</span>
            <span class="text-white font-medium">Etapa 9</span>
          </nav>
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
              9
            </div>
            <div>
              <h2 class="font-bold text-3xl text-white leading-tight">
                Pitch de Presentación
              </h2>
              <p class="text-cyan-100 mt-1">Sube y gestiona el video de presentación de tu proyecto</p>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-cyan-50 py-12">
      <div class="max-w-4xl mx-auto px-4">
        <!-- Panel educativo -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-cyan-500">
          <div class="flex items-center mb-4">
            <svg class="w-7 h-7 mr-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A2 2 0 0020 6.382V5a2 2 0 00-2-2H6a2 2 0 00-2 2v1.382a2 2 0 00.447 1.342L9 10m6 0v4m0 0l-3 3m3-3l3 3" />
            </svg>
            <h3 class="text-xl font-bold text-cyan-800">¿Por qué es importante el Pitch?</h3>
          </div>
          <p class="text-gray-700 mb-2">El Pitch de Negocio es tu oportunidad para convencer al jurado sobre el potencial de tu emprendimiento. Presenta tu propuesta de valor, experiencia y visión.</p>
          <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
            <li>Expón el problema y la solución que ofrece tu proyecto.</li>
            <li>Destaca el valor agregado y tu experiencia como emprendedor(a).</li>
            <li>Comparte las lecciones aprendidas y tu motivación.</li>
          </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
          <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="w-full flex flex-col items-center">
              <!-- Si hay video guardado, mostrar reproductor y botón eliminar -->
              <div v-if="isEditing && etapa9.video_pitch_path" class="w-full flex flex-col items-center mb-6">
                <video
                  :src="`/storage/${etapa9.video_pitch_path}`"
                  controls
                  class="max-h-52 rounded shadow mb-2 w-full"
                ></video>
                <button
                  type="button"
                  @click="deleteVideo()"
                  class="modern-btn-red"
                  :disabled="form.processing"
                >
                  Eliminar video
                </button>
              </div>

              <!-- Si NO hay video guardado, mostrar input para subir -->
              <div v-else>
                <svg class="w-12 h-12 text-cyan-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A2 2 0 0020 6.382V5a2 2 0 00-2-2H6a2 2 0 00-2 2v1.382a2 2 0 00.447 1.342L9 10m6 0v4m0 0l-3 3m3-3l3 3" />
                </svg>
                <p class="text-lg font-semibold text-cyan-700">Agregar Video Pitch</p>
                <input
                  type="file"
                  name="video"
                  accept="video/mp4,video/avi,video/mpeg"
                  @change="handleFileChange"
                  :disabled="form.processing"
                  class="mt-2 modern-input-cyan"
                />
                <p v-if="form.errors.video" class="mt-2 text-sm text-red-600">
                  {{ form.errors.video }}
                </p>

                <!-- Vista previa del video seleccionado -->
                <div v-if="previewUrl" class="mt-4 w-full flex flex-col items-center">
                  <video
                    :src="previewUrl"
                    controls
                    class="max-h-40 rounded shadow w-full"
                  ></video>
                  <p class="mt-2 text-sm text-green-600">
                    Vista previa: {{ form.video.name }}
                  </p>
                </div>

                <!-- Archivo seleccionado sin vista previa -->
                <p v-else-if="form.video" class="mt-2 text-sm text-green-600">
                  Archivo seleccionado: {{ form.video.name }}
                </p>
              </div>
            </div>

            <p class="text-sm text-gray-700 mb-4 mt-6">
              Para el Pitch de Negocio deberá presentar un video que responda a:<br />
              ¿Por qué su emprendimiento debería ser beneficiario del Fondo de Capital Semilla?
            </p>
            <ul class="text-sm text-gray-700 list-disc list-inside mb-6">
              <li>Problema–Solución del emprendimiento</li>
              <li>Valor agregado</li>
              <li>Conocimiento sobre el emprendimiento</li>
              <li>Experiencia como emprendedor(a)</li>
              <li>Principales lecciones aprendidas en el desarrollo</li>
            </ul>

            <button
              type="submit"
              class="modern-btn-cyan"
              :disabled="form.processing || (isEditing && etapa9.video_pitch_path)"
            >
              {{ form.processing
                ? 'Procesando...'
                : isEditing && etapa9.video_pitch_path
                  ? 'Video ya subido'
                  : isEditing
                    ? 'Actualizar'
                    : 'Guardar' }}
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
  etapa9: Object,
  proyecto_id: Number,
  proyectoactual: Object
})

const isEditing = computed(() => Boolean(props.etapa9 && props.etapa9.id))
const previewUrl = ref(null)

const form = useForm({
  proyecto_id: props.proyecto_id,
  video: null,
  delete_video: false,
})


function deleteVideo() {
  Swal.fire({
    title: '¿Eliminar video?',
    text: 'Esto removerá el video actual del pitch.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (!result.isConfirmed) return;

    // Usa Inertia para DELETE
    form.delete(route('etapa9.destroyVideo', props.etapa9.id), {
      preserveScroll: true,
      onSuccess: () => {
        Swal.fire('Eliminado', 'Video borrado correctamente.', 'success');
        previewUrl.value = null;          // limpia vista previa
        form.video = null;                // limpia input file
        // O bien recarga la página / datos
      },
      onError: errors => {
        console.error(errors);
        Swal.fire('Error', 'No se pudo eliminar el video.', 'error');
      },
    });
  });
}


function submit() {
  if (!isEditing.value && !form.video) {
    Swal.fire('Campo requerido', 'Debes seleccionar un video.', 'warning')
    return
  }

  const url = isEditing.value
    ? route('etapa9.update', props.etapa9.id)
    : route('etapa9.store')

  // Always POST with spoofed method for update
  const options = {
    data: isEditing.value ? { _method: 'PUT', delete_video: form.delete_video } : {},
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: isEditing.value ? 'Actualizado' : 'Guardado',
        text: isEditing.value
          ? 'Video actualizado correctamente.'
          : 'Video guardado correctamente.',
        timer: 2000,
        showConfirmButton: false,
      })
    },
    onError: errors => {
      console.error(errors)
      Swal.fire('Error', Object.values(errors).join('<br>'), 'error')
    },
  }

  form.post(url, options)
}

function handleFileChange(event) {
  const file = event.target.files[0]
  if (!file) {
    form.video = null
    previewUrl.value = null
    return
  }

  const allowed = ['video/mp4', 'video/avi', 'video/mpeg']
  if (!allowed.includes(file.type)) {
    Swal.fire('Tipo no válido', 'Solo MP4, AVI y MPEG.', 'warning')
    event.target.value = ''
    form.video = null
    previewUrl.value = null
    return
  }

  form.video = file
  previewUrl.value = URL.createObjectURL(file)
}

function confirmDelete() {
  Swal.fire({
    title: '¿Eliminar video?',
    text: 'Esto removerá el video actual del pitch.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
  }).then(result => {
    if (result.isConfirmed) {
      form.delete_video = true
      submit()
    }
  })
}
</script>

<style scoped>
.modern-btn-cyan {
  background: linear-gradient(90deg, #06b6d4 0%, #0ea5e9 100%);
  color: #fff;
  font-weight: 600;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px 0 rgba(6,182,212,0.10);
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.modern-btn-cyan:hover {
  background: linear-gradient(90deg, #0ea5e9 0%, #06b6d4 100%);
  transform: scale(1.04);
}
.modern-btn-red {
  background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
  color: #fff;
  font-weight: 600;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px 0 rgba(239,68,68,0.10);
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
  margin-top: 0.5rem;
}
.modern-btn-red:hover {
  background: linear-gradient(90deg, #f87171 0%, #ef4444 100%);
  transform: scale(1.04);
}
.modern-input-cyan {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #06b6d4;
  background: linear-gradient(135deg, #e0f2fe 0%, #f0fdfa 100%);
  padding: 0.5rem 1rem;
  font-size: 1rem;
  color: #0891b2;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(6,182,212,0.08);
}
.modern-input-cyan:focus {
  border-color: #06b6d4;
  outline: none;
  box-shadow: 0 0 0 2px #0ea5e9;
}
</style>
