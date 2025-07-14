<template>
  <AppLayout title="Etapa 9">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Etapa 9 – {{ isEditing ? 'Editar' : 'Subir' }} Pitch de Presentación
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="w-full h-52 bg-gray-200 flex flex-col justify-center items-center rounded mb-4">
            <p class="text-lg font-semibold">AGREGAR VIDEO</p>
            <input
              type="file"
              name="video"
              accept="video/mp4,video/avi,video/mpeg"
              @change="handleFileChange"
              :disabled="form.processing"
              class="mt-2"
            />
            <p v-if="form.errors.video" class="mt-2 text-sm text-red-600">
              {{ form.errors.video }}
            </p>

            <!-- Vista previa del video seleccionado -->
            <div v-else-if="previewUrl" class="mt-4 w-full flex flex-col items-center">
              <video
                :src="previewUrl"
                controls
                class="max-h-40 rounded"
              ></video>
              <p class="mt-2 text-sm text-green-600">
                Vista previa: {{ form.video.name }}
              </p>
            </div>

            <!-- Archivo seleccionado sin vista previa -->
            <p v-else-if="form.video" class="mt-2 text-sm text-green-600">
              Archivo seleccionado: {{ form.video.name }}
            </p>

            <!-- Video guardado y opciones -->
            <div v-if="isEditing && etapa9.video_pitch_path" class="mt-4 text-sm text-gray-600 flex flex-col items-center">
              <p>Video actual:</p>
              <a
                :href="`/storage/${etapa9.video_pitch_path}`"
                class="underline text-blue-600"
                target="_blank"
              >
                Ver video
              </a>
              <button
  type="button"
  @click="deleteVideo()"
  class="mt-2 text-red-600 hover:underline"
  :disabled="form.processing"
>
  Eliminar video
</button>

            </div>
          </div>

          <p class="text-sm text-gray-700 mb-4">
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
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
            :disabled="form.processing"
          >
            {{ form.processing
              ? 'Procesando...'
              : isEditing
                ? 'Actualizar'
                : 'Guardar' }}
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
  etapa9: Object,
  proyecto_id: Number,
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
