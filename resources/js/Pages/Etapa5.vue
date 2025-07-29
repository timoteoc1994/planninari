<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import Swal from 'sweetalert2'

const props = defineProps({
  etapa5: Object,
  proyecto_id: Number,
  proyectoactual: Object
})

// inicia con el texto existente o vacío
const form = useForm({
  igualdad_genero: props.etapa5?.igualdad_genero ?? '',
  proyecto_id: props.proyecto_id,
})

const editando = computed(() => !!props.etapa5)
const actual = 'etapa5'; // No existe en el menú, pero para navegación contextual

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
      <div class="bg-gradient-to-r from-pink-600 to-pink-500 py-8">
        <div class="max-w-4xl mx-auto px-4">
          <nav class="flex items-center space-x-2 text-pink-100 mb-4">
            <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
              {{ proyectoactual.name }}
            </Link>
            <span>/</span>
            <span class="text-white font-medium">Etapa 5</span>
          </nav>
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
              5
            </div>
            <div>
              <h2 class="font-bold text-3xl text-white leading-tight">
                Igualdad de Género
              </h2>
              <p class="text-pink-100 mt-1">Promueve la inclusión y equidad en tu proyecto</p>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-pink-50 py-12">
      <div class="max-w-4xl mx-auto px-4">
        <!-- Panel educativo -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-pink-500">
          <div class="flex items-center mb-4">
            <svg class="w-7 h-7 mr-3 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-xl font-bold text-pink-800">¿Por qué es importante la Igualdad de Género?</h3>
          </div>
          <p class="text-gray-700 mb-2">La igualdad de género fomenta la participación equitativa y el respeto en todos los ámbitos del proyecto, mejorando el ambiente laboral y los resultados.</p>
          <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
            <li>Promueve la inclusión y diversidad en el equipo.</li>
            <li>Evita la discriminación y fomenta el respeto.</li>
            <li>Mejora la innovación y la toma de decisiones.</li>
          </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
          <form @submit.prevent="submit">
            <div class="mb-6">
              <label class="block text-sm font-medium text-pink-700 mb-2">Igualdad de Género</label>
              <textarea
                v-model="form.igualdad_genero"
                class="modern-textarea-pink"
                rows="6"
                required
              ></textarea>
              <div v-if="form.errors.igualdad_genero" class="text-red-500 text-sm mt-1">{{ form.errors.igualdad_genero }}</div>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="form.processing"
                class="modern-btn-pink"
              >
                {{ form.processing ? 'Guardando...' : (editando ? 'Actualizar' : 'Guardar') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Botón moderno rosado */
.modern-btn-pink {
  display: inline-flex;
  align-items: center;
  background: linear-gradient(90deg, #ec4899 0%, #f472b6 100%);
  color: #fff;
  font-weight: 600;
  padding: 0.75rem 2rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px 0 rgba(236,72,153,0.10);
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.modern-btn-pink:hover {
  background: linear-gradient(90deg, #f472b6 0%, #ec4899 100%);
  transform: scale(1.04);
}

/* Textarea moderno rosado */
.modern-textarea-pink {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #f472b6;
  background: linear-gradient(135deg, #fce7f3 0%, #fdf2f8 100%);
  padding: 1rem;
  font-size: 1rem;
  color: #ec4899;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(236,72,153,0.08);
  resize: vertical;
}
.modern-textarea-pink:focus {
  border-color: #ec4899;
  outline: none;
  box-shadow: 0 0 0 2px #f472b6;
}
</style>
