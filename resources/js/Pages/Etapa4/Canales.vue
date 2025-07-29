<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router, usePage, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import MenuNavegacion from './MenuNavegacion.vue';

const props = defineProps({
    canales: Object,
    proyecto_id: [Number, String],
    proyectoactual: Object,
});
const form = useForm({
    canalescomunicacion: props.canales?.canalescomunicacion ?? "",
    canalesdistribucion: props.canales?.canalesdistribucion ?? "",
    facebook: props.canales?.facebook ?? "",
    instagram: props.canales?.instagram ?? "",
    whatsapp: props.canales?.whatsapp ?? "",
    linkedin: props.canales?.linkedin ?? "",
    tiktok: props.canales?.tiktok ?? "",
    twitter: props.canales?.twitter ?? "",
    youtube: props.canales?.youtube ?? "",
    web: props.canales?.web ?? "",
    otro: props.canales?.otro ?? "",
    proyecto_id: props.proyecto_id,
});

const page = usePage();
const successMessage = computed(() => page.props.flash?.success || page.props.success);
const errorMessage = computed(() => page.props.error);
const editando = computed(() => !!props.canales);

function submit() {
    if (editando.value) {
        router.post(route("canales.update", props.canales.id), form.data());
    } else {
        form.post(route("canales.store"));
    }
}
</script>

<template>
    <AppLayout title="Canales">
        <template #header>
            <div class="bg-gradient-to-r from-purple-600 to-purple-400 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-purple-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <Link class="hover:text-white transition-colors" :href="route('etapa4', proyectoactual.id)">
                            Modelo de Negocio
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Canales</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Canales
                            </h2>
                            <p class="text-purple-100 mt-1">Define cómo llegas y entregas valor a tus clientes</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

         <!-- Menú de navegación entre secciones -->
        <MenuNavegacion :proyecto_id="proyecto_id" :proyectoactual="proyectoactual" :actual="actual" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-8">
            <div class="max-w-4xl mx-auto px-4">
                <!-- Panel educativo -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-purple-500">
                    <div class="flex items-center mb-4">
                        <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <h3 class="text-xl font-bold text-purple-800">¿Qué son los Canales?</h3>
                    </div>
                    <p class="text-gray-700 mb-2">Los canales son los medios a través de los cuales tu empresa se comunica y entrega valor a los clientes. Incluyen tanto canales de comunicación como de distribución.</p>
                    <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
                        <li>Describe cómo informas a tus clientes sobre tu producto o servicio.</li>
                        <li>Explica cómo haces llegar tu propuesta de valor a los clientes.</li>
                        <li>Puedes incluir redes sociales, página web, tiendas físicas, apps, etc.</li>
                    </ul>
                </div>

                <!-- Mensajes -->
                <div v-if="errorMessage" class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ errorMessage }}
                    </div>
                </div>
                <div v-if="successMessage" class="mb-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ successMessage }}
                    </div>
                </div>

                <!-- Formulario moderno -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label for="canalescomunicacion" class="block font-semibold text-purple-700 mb-2">Canales de comunicación</label>
                            <textarea id="canalescomunicacion" v-model="form.canalescomunicacion" rows="3" class="modern-textarea" placeholder="Ej: Redes sociales, email, llamadas, etc..."></textarea>
                        </div>
                        <div class="mb-6">
                            <label for="canalesdistribucion" class="block font-semibold text-purple-700 mb-2">Canales de distribución</label>
                            <textarea id="canalesdistribucion" v-model="form.canalesdistribucion" rows="3" class="modern-textarea" placeholder="Ej: Tienda física, app, web, distribuidores..."></textarea>
                        </div>
                        <div class="mb-8">
                            <h4 class="font-semibold text-purple-700 mb-3">Redes y otros canales</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="(label, key) in redes" :key="key">
                                    <label :for="key" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
                                    <input :id="key" type="text" v-model="form[key]" class="modern-input" :placeholder="'Enlace o usuario de ' + label" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="modern-btn-purple">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar Canales
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.modern-textarea {
    width: 100%;
    border-radius: 0.75rem;
    border: 1.5px solid #a78bfa;
    background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%);
    padding: 1rem;
    font-size: 1rem;
    color: #4b006e;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px 0 rgba(168,139,250,0.08);
    resize: vertical;
}
.modern-textarea:focus {
    border-color: #7c3aed;
    outline: none;
    box-shadow: 0 0 0 2px #c4b5fd;
}
.modern-input {
    width: 100%;
    border-radius: 0.75rem;
    border: 1.5px solid #a78bfa;
    background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%);
    padding: 0.75rem 1rem;
    font-size: 1rem;
    color: #4b006e;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px 0 rgba(168,139,250,0.08);
}
.modern-input:focus {
    border-color: #7c3aed;
    outline: none;
    box-shadow: 0 0 0 2px #c4b5fd;
}
.modern-btn-purple {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(90deg, #a78bfa 0%, #7c3aed 100%);
    color: #fff;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    box-shadow: 0 2px 8px 0 rgba(124,58,237,0.10);
    border: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.modern-btn-purple:hover {
    background: linear-gradient(90deg, #7c3aed 0%, #a78bfa 100%);
    transform: scale(1.04);
}
@media (max-width: 640px) {
    .modern-btn-purple {
        width: 100%;
        justify-content: center;
    }
    .p-8 {
        padding: 1.25rem !important;
    }
}
</style>
