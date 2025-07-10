<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import Swal from 'sweetalert2'

const props = defineProps({
  etapa5: Object,
  proyecto_id: Number,
})

// inicia con el texto existente o vacío
const form = useForm({
  igualdad_genero: props.etapa5?.igualdad_genero ?? '',
  proyecto_id: props.proyecto_id,
})

const editando = computed(() => !!props.etapa5)

function submit() {
  if (editando.value) {
    form.put(route('etapa5.update', props.etapa5.id), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Actualizado',
          text: 'Igualdad de Género actualizado correctamente.',
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
      }
    })
  } else {
    form.post(route('etapa5.store'), {
      onSuccess: () => {
        Swal.fire({
          icon: 'success',
          title: 'Guardado',
          text: 'Igualdad de Género guardado correctamente.',
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
      }
    })
  }
}
</script>

<template>
  <AppLayout title="Etapa 5">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Etapa 5 – {{ editando ? 'Editar' : 'Registrar' }} Igualdad de Género
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block mb-1 font-semibold">Igualdad de Género</label>
            <textarea
              v-model="form.igualdad_genero"
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
