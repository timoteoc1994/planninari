<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  relacionclientes: Object,
  proyecto_id: Number,
})

// Inicializa el formulario con los datos si existen, o vacío si no
const form = useForm({
  relacion_clientes: props.relacionclientes?.relacion_clientes ?? '',
  proyecto_id: props.proyecto_id,
})

const editando = computed(() => !!props.relacionclientes)

function submit() {
  if (editando.value) {
    router.post(route('relacionclientes.update', props.relacionclientes.id), form.data())
  } else {
    form.post(route('relacionclientes.store'))
  }
}
</script>


<template>
    <AppLayout title="Etapa 2">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Etapa 2
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="relacion_clientes" class="block mb-1 font-semibold">Relación con los clientes</label>
                        <textarea id="relacion_cientes" v-model="form.relacion_clientes" rows="8" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>