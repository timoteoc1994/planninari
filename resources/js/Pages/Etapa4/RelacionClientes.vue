<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import MenuNavegacion from './MenuNavegacion.vue';

const props = defineProps({
  relacionclientes: Object,
  proyecto_id: Number,
  proyectoactual: Object,
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
    <AppLayout title="Relación con los Clientes">
        <template #header>
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 py-8 rounded-b-3xl shadow">
                <div class="max-w-4xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-blue-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <Link class="hover:text-white transition-colors" :href="route('etapa4', proyectoactual.id)">
                            Modelo de negocio
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Relación con los Clientes</span>
                    </nav>
                    <h2 class="font-bold text-3xl text-white leading-tight flex items-center gap-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Relación con los Clientes
                    </h2>
                    <p class="text-blue-100 mt-2">¿Cómo interactúas y mantienes a tus clientes? Define los canales y estrategias de fidelización.</p>
                </div>
            </div>
        </template>

         <!-- Menú de navegación entre secciones -->
        <MenuNavegacion :proyecto_id="proyecto_id" :proyectoactual="proyectoactual" :actual="actual" />

        <div class="py-12 bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
                    <!-- Panel educativo -->
                    <div class="bg-gradient-to-r from-blue-100 to-indigo-50 p-6 rounded-lg border-l-4 border-blue-500 mb-6">
                        <h3 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ¿Qué es la relación con los clientes?
                        </h3>
                        <ul class="list-disc list-inside text-blue-700 text-sm space-y-1">
                            <li>¿Cómo acompañas al cliente antes, durante y después de la compra?</li>
                            <li>¿Qué canales usas para comunicarte y resolver dudas?</li>
                            <li>¿Qué estrategias usas para fidelizar y retener?</li>
                            <li>Ejemplo: Soporte personalizado, comunidad, programas de lealtad, automatización, etc.</li>
                        </ul>
                    </div>

                    <div>
                        <label for="relacion_clientes" class="block mb-2 font-semibold text-blue-700 text-lg">Relación con los clientes</label>
                        <textarea id="relacion_clientes" v-model="form.relacion_clientes" rows="8" class="form-textarea-modern" placeholder="Describe cómo interactúas, acompañas y fidelizas a tus clientes. Ejemplo: Seguimiento post-venta, atención personalizada, comunidad online, etc." required></textarea>
                    </div>
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="btn-primary-modern btn-super-visible" :disabled="form.processing">
                            <span class="flex items-center justify-center gap-2">
                                <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#fff" stroke="#2563eb" stroke-width="2"/>
                                    <path stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4"/>
                                </svg>
                                <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#6366f1" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="#6366f1" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="font-bold tracking-wide text-base">
                                    {{ form.processing ? 'Guardando...' : (editando ? 'Actualizar' : 'Guardar') }}
                                </span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.form-textarea-modern {
    width: 100%;
    border: 2px solid #cbd5e1;
    background: #f8fafc;
    border-radius: 0.75rem;
    padding: 1rem;
    font-size: 1rem;
    color: #334155;
    transition: border 0.2s, box-shadow 0.2s;
    outline: none;
    resize: vertical;
    min-height: 120px;
}
.form-textarea-modern:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px #2563eb33;
}
.btn-primary-modern {
    background: linear-gradient(90deg, #2563eb 0%, #6366f1 100%);
    color: #fff;
    font-weight: bold;
    font-size: 1.1rem;
    padding: 0.75rem 2.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 24px 0 #2563eb33;
    border: none;
    outline: none;
    transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.btn-primary-modern:active,
.btn-primary-modern:focus {
    background: linear-gradient(90deg, #3730a3 0%, #2563eb 100%);
    box-shadow: 0 6px 32px 0 #2563eb55;
    transform: scale(0.98);
}
.btn-primary-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.btn-super-visible {
    border: 2px solid #2563eb;
    box-shadow: 0 6px 32px 0 #2563eb55;
}
</style>