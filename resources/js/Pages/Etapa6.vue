<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import Swal from 'sweetalert2'

const props = defineProps({
  etapa6: Object,
  proyecto_id: Number,
})

const form = useForm({
  mitigacion_dano_ambiental: props.etapa6?.mitigacion_dano_ambiental ?? '',
  proyecto_id: props.proyecto_id,
})

const editando = computed(() => !!props.etapa6)

function submit() {
  if (editando.value) {
    form.put(route('etapa6.update', props.etapa6.id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Actualizado',
          text: 'Mitigación del daño ambiental actualizado correctamente.',
          timer: 2000,
          showConfirmButton: false,
        })
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al actualizar.',
        })
      },
    })
  } else {
    form.post(route('etapa6.store'), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Guardado',
          text: 'Mitigación del daño ambiental guardado correctamente.',
          timer: 2000,
          showConfirmButton: false,
        })
      },
      onError: () => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al guardar.',
        })
      },
    })
  }
}
</script>

<template>
  <AppLayout title="Etapa 6">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Etapa 6 – {{ editando ? 'Editar' : 'Registrar' }} Mitigación del daño ambiental
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block mb-1 font-semibold">Mitigación del daño ambiental</label>
            <textarea
              v-model="form.mitigacion_dano_ambiental"
              class="w-full border rounded px-3 py-2"
              rows="6"
              required
            ></textarea>
          </div>
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            {{ editando ? 'Actualizar' : 'Guardar' }}
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
