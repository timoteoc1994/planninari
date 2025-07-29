<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import Swal from 'sweetalert2'

const props = defineProps({
  etapa6: Object,
  proyecto_id: Number,
  proyectoactual: Object
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
      <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 py-8">
        <div class="max-w-4xl mx-auto px-4">
          <nav class="flex items-center space-x-2 text-emerald-100 mb-4">
            <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
              {{ proyectoactual.name }}
            </Link>
            <span>/</span>
            <span class="text-white font-medium">Etapa 6</span>
          </nav>
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
              6
            </div>
            <div>
              <h2 class="font-bold text-3xl text-white leading-tight">
                Mitigación del daño ambiental
              </h2>
              <p class="text-emerald-100 mt-1">Describe cómo tu proyecto reduce el impacto ambiental</p>
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-emerald-50 py-12">
      <div class="max-w-4xl mx-auto px-4">
        <!-- Panel educativo -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-emerald-500">
          <div class="flex items-center mb-4">
            <svg class="w-7 h-7 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3zm0 0V4m0 10v4m-4-4H4m16 0h-4" />
            </svg>
            <h3 class="text-xl font-bold text-emerald-800">¿Por qué es importante la mitigación ambiental?</h3>
          </div>
          <p class="text-gray-700 mb-2">La mitigación ambiental busca reducir el impacto negativo de las actividades del proyecto en el entorno, promoviendo la sostenibilidad y el cuidado de los recursos naturales.</p>
          <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
            <li>Implementa prácticas sostenibles y responsables.</li>
            <li>Reduce el consumo de recursos y la generación de residuos.</li>
            <li>Contribuye a la conservación del medio ambiente.</li>
          </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
          <form @submit.prevent="submit">
            <div class="mb-6">
              <label class="block text-sm font-medium text-emerald-700 mb-2">Mitigación del daño ambiental</label>
              <textarea
                v-model="form.mitigacion_dano_ambiental"
                class="modern-textarea-emerald"
                rows="6"
                required
              ></textarea>
              <div v-if="form.errors.mitigacion_dano_ambiental" class="text-red-500 text-sm mt-1">{{ form.errors.mitigacion_dano_ambiental }}</div>
            </div>
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="form.processing"
                class="modern-btn-emerald"
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
/* Botón moderno verde esmeralda */
.modern-btn-emerald {
  display: inline-flex;
  align-items: center;
  background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
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
  background: linear-gradient(90deg, #34d399 0%, #10b981 100%);
  transform: scale(1.04);
}

/* Textarea moderno verde esmeralda */
.modern-textarea-emerald {
  width: 100%;
  border-radius: 0.75rem;
  border: 1.5px solid #34d399;
  background: linear-gradient(135deg, #d1fae5 0%, #f0fdf4 100%);
  padding: 1rem;
  font-size: 1rem;
  color: #10b981;
  transition: border 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px 0 rgba(16,185,129,0.08);
  resize: vertical;
}
.modern-textarea-emerald:focus {
  border-color: #10b981;
  outline: none;
  box-shadow: 0 0 0 2px #34d399;
}
</style>
