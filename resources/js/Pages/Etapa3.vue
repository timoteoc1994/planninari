<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    etapa3: Object,
    proyecto_id: Number,
    proyectoactual: Object
});

// Inicializa el formulario con los datos si existen, o vacío si no
const form = useForm({
    problema: props.etapa3?.problema ?? "",
    solucion: props.etapa3?.solucion ?? "",
    proyecto_id: props.proyecto_id,
});

const editando = computed(() => !!props.etapa3);

function submit() {
    if (editando.value) {
        router.put(route("etapa3.update", props.etapa3.id), form.data());
    } else {
        form.post(route("etapa3.store"));
    }
}
</script>

<template>
    <AppLayout title="Etapa 3 - Oportunidad de Negocio">
        <template #header>
            <div class="bg-gradient-to-r from-purple-600 to-violet-700 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-purple-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Etapa 3</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            3
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Oportunidad de Negocio
                            </h2>
                            <p class="text-purple-100 mt-1">
                                {{ editando ? "Actualiza" : "Define" }} el problema y la solución de tu emprendimiento
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-8">
            <div class="max-w-6xl mx-auto px-4">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-violet-50 px-8 py-6 border-b border-purple-100">
                        <h3 class="text-xl font-semibold text-gray-800">{{ editando ? "Editar" : "Registrar" }} Problema y Solución</h3>
                        <p class="text-gray-600 mt-1">Identifica claramente el problema que resuelve tu emprendimiento y la solución que ofreces</p>
                    </div>
                    
                    <form @submit.prevent="submit" class="p-8">
                        <div class="space-y-8">
                            <!-- Sección: Identificación del Problema -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-lg border-l-4 border-red-500">
                                    <h4 class="font-semibold text-red-800 mb-3 flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Identificación del Problema
                                    </h4>
                                    <p class="text-red-700 text-sm">
                                        Describe detalladamente el problema que identificaste en el mercado y que tu emprendimiento busca resolver.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Descripción del Problema *
                                        <span class="text-sm font-normal text-gray-500 ml-1">(Mínimo 100 caracteres)</span>
                                    </label>
                                    <div class="relative">
                                        <textarea
                                            v-model="form.problema"
                                            class="form-textarea"
                                            rows="6"
                                            placeholder="Describe el problema que has identificado en el mercado. Por ejemplo: 'Las pequeñas empresas en nuestra región tienen dificultades para acceder a servicios de marketing digital efectivos debido a los altos costos y la falta de conocimiento técnico...' "
                                            required
                                        ></textarea>
                                        <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                            {{ form.problema.length }} caracteres
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p class="font-medium mb-1">Considera incluir:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>¿Qué problema específico enfrentan tus clientes potenciales?</li>
                                            <li>¿Qué impacto tiene este problema en sus vidas o negocios?</li>
                                            <li>¿Por qué las soluciones actuales no son satisfactorias?</li>
                                            <li>¿Qué tan frecuente o urgente es este problema?</li>
                                        </ul>
                                    </div>
                                    <div v-if="form.errors.problema" class="error-message">{{ form.errors.problema }}</div>
                                </div>
                            </div>

                            <!-- Sección: Propuesta de Solución -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border-l-4 border-green-500">
                                    <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        Propuesta de Solución
                                    </h4>
                                    <p class="text-green-700 text-sm">
                                        Explica cómo tu emprendimiento resuelve el problema identificado de manera innovadora y efectiva.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Descripción de la Solución *
                                        <span class="text-sm font-normal text-gray-500 ml-1">(Mínimo 100 caracteres)</span>
                                    </label>
                                    <div class="relative">
                                        <textarea
                                            v-model="form.solucion"
                                            class="form-textarea"
                                            rows="6"
                                            placeholder="Describe detalladamente cómo tu emprendimiento soluciona el problema. Por ejemplo: 'Nuestra plataforma digital ofrece servicios de marketing accesibles y automatizados, diseñados específicamente para pequeñas empresas, con precios flexibles y herramientas fáciles de usar...' "
                                            required
                                        ></textarea>
                                        <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                            {{ form.solucion.length }} caracteres
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p class="font-medium mb-1">Considera incluir:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>¿Cómo funciona tu solución exactamente?</li>
                                            <li>¿Qué hace que tu solución sea única o mejor?</li>
                                            <li>¿Qué beneficios específicos obtendrán tus clientes?</li>
                                            <li>¿Cómo se implementará o entregará tu solución?</li>
                                        </ul>
                                    </div>
                                    <div v-if="form.errors.solucion" class="error-message">{{ form.errors.solucion }}</div>
                                </div>
                            </div>

                            <!-- Sección: Validación de la Oportunidad -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg border border-blue-200">
                                <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Validación de la Oportunidad
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div class="space-y-2">
                                        <h5 class="font-medium text-blue-700">Preguntas de Reflexión:</h5>
                                        <ul class="list-disc list-inside space-y-1 text-blue-600">
                                            <li>¿Has validado este problema con clientes potenciales?</li>
                                            <li>¿Existe un mercado suficientemente grande?</li>
                                            <li>¿Los clientes estarían dispuestos a pagar por tu solución?</li>
                                        </ul>
                                    </div>
                                    <div class="space-y-2">
                                        <h5 class="font-medium text-blue-700">Próximos Pasos:</h5>
                                        <ul class="list-disc list-inside space-y-1 text-blue-600">
                                            <li>Realizar entrevistas con clientes potenciales</li>
                                            <li>Analizar la competencia existente</li>
                                            <li>Estimar el tamaño del mercado objetivo</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-between items-center pt-8 border-t border-gray-200 mt-8">
                            <Link :href="route('projects.stages.show', proyectoactual.id)" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver a Etapas
                            </Link>
                            
                            <div class="flex space-x-4">
                                <button type="submit" class="btn-primary" :disabled="form.processing">
                                    <svg v-if="!form.processing" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <svg v-else class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ form.processing ? 'Guardando...' : (editando ? 'Actualizar Información' : 'Guardar Información') }}
                                </button>
                                
                                <Link :href="route('etapa4', { id: proyectoactual.id })" class="btn-accent">
                                    Continuar a Etapa 4
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Componentes de formulario */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease-in-out;
    color: #111827;
    background-color: white;
    font-size: 0.875rem;
    resize: vertical;
    min-height: 120px;
    line-height: 1.5;
}

.form-textarea::placeholder {
    color: #9ca3af;
    font-style: italic;
}

.form-textarea:hover {
    border-color: #9ca3af;
}

.form-textarea:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

/* Botones */
.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    background: linear-gradient(to right, #8b5cf6, #7c3aed);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(to right, #7c3aed, #6d28d9);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    background-color: white;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
}

.btn-secondary:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
    transform: translateY(-1px);
}

.btn-accent {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    background: linear-gradient(to right, #f59e0b, #d97706);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    text-decoration: none;
}

.btn-accent:hover {
    background: linear-gradient(to right, #d97706, #b45309);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

/* Mensajes de error */
.error-message {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    font-weight: 500;
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-white {
    animation: fadeIn 0.6s ease-out;
}

.form-group {
    animation: fadeIn 0.4s ease-out;
}

/* Loading spinner */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Estados de focus para accesibilidad */
button:focus,
.btn-primary:focus,
.btn-secondary:focus,
.btn-accent:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-textarea {
        font-size: 16px; /* Prevent zoom on iOS */
        min-height: 100px;
    }
    
    .btn-primary, .btn-secondary, .btn-accent {
        width: 100%;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
    
    .flex.justify-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .flex.space-x-4 {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Mejoras visuales */
.form-group:hover .form-label {
    color: #111827;
}

/* Contador de caracteres */
.absolute.bottom-3.right-3 {
    background: rgba(255, 255, 255, 0.9);
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

/* Estilos para las listas de ayuda */
ul.list-disc {
    padding-left: 1rem;
}

ul.list-disc li {
    margin-bottom: 0.25rem;
    color: inherit;
}

/* Secciones de información */
.border-l-4 {
    border-left-width: 4px;
}

/* Estados de validación */
.form-textarea.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-textarea.success {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Iconos SVG */
svg {
    flex-shrink: 0;
}

/* Efectos hover para las secciones informativas */
.bg-gradient-to-r:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease-in-out;
}

/* Mejoras en la tipografía */
.text-xs {
    line-height: 1.4;
}

.text-sm {
    line-height: 1.5;
}

/* Espaciado mejorado */
.space-y-1 > * + * {
    margin-top: 0.25rem;
}

.space-y-2 > * + * {
    margin-top: 0.5rem;
}

/* Custom scrollbar para textareas */
.form-textarea::-webkit-scrollbar {
    width: 6px;
}

.form-textarea::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.form-textarea::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.form-textarea::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
