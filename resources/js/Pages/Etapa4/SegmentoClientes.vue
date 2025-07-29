<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import MenuNavegacion from './MenuNavegacion.vue';

const props = defineProps({
    segmentoclientes: Object,
    proyecto_id: Number,
    proyectoactual: Object,
});

// Inicializa el formulario con los datos si existen, o vacío si no
const form = useForm({
    caracteristicas_segmento: props.segmentoclientes?.caracteristicas_segmento ?? "",
    segmento_empresas_geografico:
        props.segmentoclientes?.segmento_empresas_geografico ?? "",
    segmento_empresas_nivel_ingreso:
        props.segmentoclientes?.segmento_empresas_nivel_ingreso ?? "",
    segmento_empresas_numero_empleador:
        props.segmentoclientes?.segmento_empresas_numero_empleador ?? "",
    segmento_empresas_tamano_empresa:
        props.segmentoclientes?.segmento_empresas_tamano_empresa ?? "",
    segmento_personas_conductal:
        props.segmentoclientes?.segmento_personas_conductal ?? "",
    segmento_personas_geografico:
        props.segmentoclientes?.segmento_personas_geografico ?? "",
    segmento_personas_psicografico:
        props.segmentoclientes?.segmento_personas_psicografico ?? "",
    segmento_personas_tamano_mercado:
        props.segmentoclientes?.segmento_personas_tamano_mercado ?? "",
    proyecto_id: props.proyecto_id,
});

const editando = computed(() => !!props.segmentoclientes);

function submit() {
    if (editando.value) {
        router.post(
            route("segmentoclientes.update", props.segmentoclientes.id),
            form.data()
        );
    } else {
        form.post(route("segmentoclientes.store"));
    }
}
</script>

<template>
    <AppLayout title="Segmento de Clientes">
        <template #header>
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 py-8 rounded-b-3xl shadow">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-green-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <Link class="hover:text-white transition-colors" :href="route('etapa4', proyectoactual.id)">
                            Modelo de negocio
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Segmento de Clientes</span>
                    </nav>
                    <h2 class="font-bold text-3xl text-white leading-tight flex items-center gap-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Segmento de Clientes
                    </h2>
                    <p class="text-green-100 mt-2">Define claramente a quién va dirigido tu producto o servicio.</p>
                </div>
            </div>
        </template>
 <!-- Menú de navegación entre secciones -->
        <MenuNavegacion :proyecto_id="proyecto_id" :proyectoactual="proyectoactual" :actual="actual" />


        <div class="py-12 bg-gradient-to-br from-slate-50 to-green-50 min-h-screen">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-2xl shadow-lg p-8 space-y-8">
                    <!-- Características del segmento -->
                    <div>
                        <label class="block mb-2 font-semibold text-green-700 text-lg">
                            Características del nicho de mercado
                        </label>
                        <textarea
                            v-model="form.caracteristicas_segmento"
                            class="form-textarea-modern"
                            rows="4"
                            placeholder="Describe las características principales de tu segmento de clientes. Ejemplo: Jóvenes profesionales urbanos, empresas tecnológicas medianas, etc."
                            required
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Segmento de empresas -->
                        <div class="bg-gradient-to-br from-green-100 to-emerald-50 rounded-xl shadow p-6">
                            <h3 class="font-bold text-green-800 text-lg mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                                    <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                Segmento de Empresas
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Geográfico</label>
                                    <input v-model="form.segmento_empresas_geografico" type="text" class="input-modern" placeholder="Ej: Lima, Perú" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Nivel de ingresos</label>
                                    <input v-model="form.segmento_empresas_nivel_ingreso" type="text" class="input-modern" placeholder="Ej: Alto, medio, bajo" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Número de empleados</label>
                                    <input v-model="form.segmento_empresas_numero_empleador" type="text" class="input-modern" placeholder="Ej: 10-50" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Tamaño del mercado</label>
                                    <input v-model="form.segmento_empresas_tamano_empresa" type="text" class="input-modern" placeholder="Ej: Mediana empresa" />
                                </div>
                            </div>
                        </div>
                        <!-- Segmento de personas -->
                        <div class="bg-gradient-to-br from-green-100 to-emerald-50 rounded-xl shadow p-6">
                            <h3 class="font-bold text-green-800 text-lg mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Segmento de Personas
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Conductal</label>
                                    <input v-model="form.segmento_personas_conductal" type="text" class="input-modern" placeholder="Ej: Hábitos de compra, uso de tecnología" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Geográfico</label>
                                    <input v-model="form.segmento_personas_geografico" type="text" class="input-modern" placeholder="Ej: Ciudad, región" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Psicográfico</label>
                                    <input v-model="form.segmento_personas_psicografico" type="text" class="input-modern" placeholder="Ej: Estilo de vida, valores" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-green-700">Tamaño del mercado</label>
                                    <input v-model="form.segmento_personas_tamano_mercado" type="text" class="input-modern" placeholder="Ej: 5000 personas" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button
                            type="submit"
                            class="btn-primary-modern btn-super-visible"
                            :disabled="form.processing"
                        >
                            <span class="flex items-center justify-center gap-2">
                                <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#fff" stroke="#22c55e" stroke-width="2"/>
                                    <path stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4"/>
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
    min-height: 100px;
}
.form-textarea-modern:focus {
    border-color: #34d399;
    box-shadow: 0 0 0 2px #34d39933;
}
.input-modern {
    width: 100%;
    border: 2px solid #cbd5e1;
    background: #f8fafc;
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    color: #334155;
    transition: border 0.2s, box-shadow 0.2s;
    outline: none;
}
.input-modern:focus {
    border-color: #34d399;
    box-shadow: 0 0 0 2px #34d39933;
}
.btn-primary-modern {
    background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    color: #fff;
    font-weight: bold;
    font-size: 1.1rem;
    padding: 0.75rem 2.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 24px 0 #10b98133;
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
    background: linear-gradient(90deg, #059669 0%, #10b981 100%);
    box-shadow: 0 6px 32px 0 #10b98155;
    transform: scale(0.98);
}
.btn-primary-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.btn-super-visible {
    border: 2px solid #059669;
    box-shadow: 0 6px 32px 0 #10b98155;
}
</style>
