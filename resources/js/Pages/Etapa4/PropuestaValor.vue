<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import MenuNavegacion from './MenuNavegacion.vue';
const props = defineProps({
    proyecto_id: Number,
    propuestavalor: Object,
    proyectoactual: Object, // Añadido para acceder al proyecto actual
});

const form = useForm({
    propuestavalor: props.propuestavalor?.propuestavalor ?? "",
    caracteristicas: props.propuestavalor?.caracteristicas ?? "",
    proyecto_id: props.proyecto_id,
    imagenes: [],
});

const imagenesPreview = ref([]);

// Computed para procesar las imágenes existentes
const imagenesExistentes = computed(() => {
    if (!props.propuestavalor?.imagenes) return [];
    
    try {
        const rutas = JSON.parse(props.propuestavalor.imagenes);
        return rutas.map(ruta => ({
            url: `/storage/${ruta}`,
            ruta: ruta
        }));
    } catch (error) {
        console.error('Error parsing imagenes:', error);
        return [];
    }
});

function handleImagenes(e) {
    const files = Array.from(e.target.files).filter((f) =>
        f.type.startsWith("image/")
    );
    form.imagenes = [...form.imagenes, ...files];
    actualizarPreviews();
}

function handleDrop(e) {
    e.preventDefault();
    const files = Array.from(e.dataTransfer.files).filter((f) =>
        f.type.startsWith("image/")
    );
    form.imagenes = [...form.imagenes, ...files];
    actualizarPreviews();
}

function actualizarPreviews() {
    imagenesPreview.value = form.imagenes.map((file) => ({
        url: URL.createObjectURL(file),
        name: file.name,
    }));
}

function eliminarImagen(idx) {
    form.imagenes.splice(idx, 1);
    actualizarPreviews();
}

// Función para eliminar imágenes existentes (necesitarás implementar esto en el backend)
function eliminarImagenExistente(idx) {
    const imagenAEliminar = imagenesExistentes.value[idx];
    
    if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
        router.delete(route('propuestavalor.eliminar.imagen', props.proyecto_id), {
            data: {
                ruta_imagen: imagenAEliminar.ruta
            },
            onSuccess: () => {
                // Recargar la página o actualizar los datos
                router.reload({ only: ['propuestavalor'] });
            },
            onError: (errors) => {
                console.error('Error al eliminar imagen:', errors);
                alert('Error al eliminar la imagen');
            }
        });
    }
}

const editando = computed(() => !!props.propuestavalor);

function submit() {
    if (editando.value) {
        form.post(route("propuestavalor.update", props.propuestavalor.id), {
            forceFormData: true,
            onStart: () => console.log("Enviando..."),
           onSuccess: () => {
                console.log("¡Listo!");
                // Limpiar el formulario de imágenes después del éxito
                limpiarImagenes();
            },
            data: { _method: "PUT" },
        });
    } else {
        form.post(route("propuestavalor.store"), {
            forceFormData: true,
            onSuccess: () => {
                // Limpiar el formulario de imágenes después del éxito
                limpiarImagenes();
            },
        });
    }
}
// Nueva función para limpiar las imágenes
function limpiarImagenes() {
    // Limpiar el array de imágenes del formulario
    form.imagenes = [];
    
    // Limpiar el preview
    imagenesPreview.value = [];
    
    // Limpiar el input file
    if (document.querySelector('input[type="file"]')) {
        document.querySelector('input[type="file"]').value = '';
    }
    
    // O también puedes usar la referencia directa si tienes acceso a ella
    // this.$refs.inputImagenes.value = '';
}
</script>

<template>
    <AppLayout title="Propuesta de Valor - Modelo de Negocio">
        <template #header>
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-blue-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <Link class="hover:text-white transition-colors" :href="route('etapa4', proyectoactual.id)">
                            Modelo de negocio
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Propuesta de Valor</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Propuesta de Valor
                            </h2>
                            <p class="text-blue-100 mt-1">Define qué valor único ofreces a tus clientes y cómo resuelves sus problemas</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

         <!-- Menú de navegación entre secciones -->
        <MenuNavegacion :proyecto_id="proyecto_id" :proyectoactual="proyectoactual" :actual="actual" />
        
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-6xl mx-auto px-4">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-blue-100">
                        <h3 class="text-xl font-semibold text-gray-800">{{ editando ? "Editar" : "Definir" }} Propuesta de Valor</h3>
                        <p class="text-gray-600 mt-1">Establece claramente el problema que resuelves y la solución única que ofreces</p>
                    </div>
                    
                    <form @submit.prevent="submit" class="p-8">
                        <div class="space-y-8">
                            <!-- Sección: Problema que resuelves -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-lg border-l-4 border-red-500">
                                    <h4 class="font-semibold text-red-800 mb-3 flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Problema Principal que Resuelves
                                    </h4>
                                    <p class="text-red-700 text-sm">
                                        Describe específicamente el problema o dolor que experimentan tus clientes y que tu propuesta de valor aborda.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Problema que resuelves *
                                        <span class="text-sm font-normal text-gray-500 ml-1">(Mínimo 50 caracteres)</span>
                                    </label>
                                    <div class="relative">
                                        <textarea
                                            v-model="form.propuestavalor"
                                            :class="[
                                                'form-textarea-modern',
                                                form.errors.propuestavalor ? 'textarea-error' : '',
                                                form.propuestavalor.length < 50 ? 'textarea-warning' : ''
                                            ]"
                                            rows="5"
                                            placeholder="¿Qué problema específico enfrentan tus clientes? Por ejemplo: 'Las pequeñas empresas luchan por gestionar eficientemente sus inventarios debido a la falta de herramientas simples y asequibles...'"
                                            required
                                        ></textarea>
                                        <div
                                            :class="[
                                                'char-counter',
                                                form.propuestavalor.length < 50 ? 'char-counter-warning' : 'char-counter-ok'
                                            ]"
                                        >
                                            {{ form.propuestavalor.length }} caracteres
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p class="font-medium mb-1">Considera incluir:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>¿Qué frustra o preocupa a tus clientes?</li>
                                            <li>¿Qué tareas les resultan difíciles o tediosas?</li>
                                            <li>¿Qué necesidades no están siendo satisfechas?</li>
                                            <li>¿Cuánto tiempo o dinero pierden por este problema?</li>
                                        </ul>
                                    </div>
                                    <div v-if="form.errors.propuestavalor" class="error-message">{{ form.errors.propuestavalor }}</div>
                                    <div v-if="form.errors.propuestavalor" class="error-message-modern">
                                        {{ form.errors.propuestavalor }}
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Solución y características -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border-l-4 border-green-500">
                                    <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        Solución y Características Únicas
                                    </h4>
                                    <p class="text-green-700 text-sm">
                                        Explica cómo tu producto/servicio resuelve el problema y qué lo hace especial o diferente.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Solución y características distintivas *
                                        <span class="text-sm font-normal text-gray-500 ml-1">(Mínimo 50 caracteres)</span>
                                    </label>
                                    <div class="relative">
                                        <textarea
                                            v-model="form.caracteristicas"
                                            :class="[
                                                'form-textarea-modern',
                                                form.errors.caracteristicas ? 'textarea-error' : '',
                                                form.caracteristicas.length < 50 ? 'textarea-warning' : ''
                                            ]"
                                            rows="5"
                                            placeholder="¿Cómo resuelves el problema? ¿Qué características únicas ofreces? Por ejemplo: 'Nuestra plataforma de gestión de inventarios permite a las pequeñas empresas automatizar el control de stock con una interfaz intuitiva, alertas automáticas y reportes en tiempo real...'"
                                            required
                                        ></textarea>
                                        <div
                                            :class="[
                                                'char-counter',
                                                form.caracteristicas.length < 50 ? 'char-counter-warning' : 'char-counter-ok'
                                            ]"
                                        >
                                            {{ form.caracteristicas.length }} caracteres
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p class="font-medium mb-1">Incluye:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>¿Cómo funciona tu solución específicamente?</li>
                                            <li>¿Qué características la hacen única?</li>
                                            <li>¿Qué beneficios concretos obtienen los clientes?</li>
                                            <li>¿Por qué es mejor que las alternativas existentes?</li>
                                        </ul>
                                    </div>
                                    <div v-if="form.errors.caracteristicas" class="error-message">{{ form.errors.caracteristicas }}</div>
                                    <div v-if="form.errors.caracteristicas" class="error-message-modern">
                                        {{ form.errors.caracteristicas }}
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Galería de imágenes -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-purple-50 to-violet-50 p-6 rounded-lg border-l-4 border-purple-500">
                                    <h4 class="font-semibold text-purple-800 mb-3 flex items-center">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Galería Visual de tu Propuesta
                                    </h4>
                                    <p class="text-purple-700 text-sm">
                                        Agrega imágenes que ayuden a visualizar tu propuesta: mockups, prototipos, diagramas, capturas de pantalla, etc.
                                    </p>
                                </div>

                                <!-- Imágenes existentes -->
                                <div v-if="imagenesExistentes.length" class="form-group">
                                    <label class="form-label">Imágenes actuales</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                        <div
                                            v-for="(img, idx) in imagenesExistentes"
                                            :key="img.ruta"
                                            class="group relative aspect-square overflow-hidden rounded-lg border-2 border-gray-200 hover:border-purple-300 transition-colors"
                                        >
                                            <img
                                                :src="img.url"
                                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-200"
                                                :alt="`Imagen ${idx + 1}`"
                                            />
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 flex items-center justify-center">
                                                <button
                                                    type="button"
                                                    @click.stop="eliminarImagenExistente(idx)"
                                                    class="opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-all duration-200 transform hover:scale-110"
                                                    title="Eliminar imagen"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Área para subir nuevas imágenes -->
                                <div class="form-group">
                                    <label class="form-label">Agregar nuevas imágenes (opcional)</label>
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-200"
                                        @click="$refs.inputImagenes.click()"
                                        @dragover.prevent
                                        @dragenter.prevent
                                        @drop="handleDrop"
                                    >
                                        <input
                                            type="file"
                                            multiple
                                            accept="image/*"
                                            @change="handleImagenes"
                                            class="hidden"
                                            ref="inputImagenes"
                                        />
                                        <div class="space-y-4">
                                            <div class="w-16 h-16 mx-auto bg-purple-100 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-lg font-medium text-gray-700">Haz clic o arrastra imágenes aquí</p>
                                                <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF hasta 10MB cada una</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview de nuevas imágenes -->
                                    <div v-if="imagenesPreview.length" class="mt-6">
                                        <p class="text-sm font-medium text-gray-700 mb-3">Nuevas imágenes a agregar:</p>
                                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                            <div
                                                v-for="(img, idx) in imagenesPreview"
                                                :key="img.url"
                                                class="group relative aspect-square overflow-hidden rounded-lg border-2 border-green-200 hover:border-green-400 transition-colors"
                                            >
                                                <img
                                                    :src="img.url"
                                                    class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-200"
                                                    :alt="`Nueva imagen ${idx + 1}`"
                                                />
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-200 flex items-center justify-center">
                                                    <button
                                                        type="button"
                                                        @click.stop="eliminarImagen(idx)"
                                                        class="opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center transition-all duration-200 transform hover:scale-110"
                                                        title="Quitar imagen"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="absolute bottom-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                                                    Nuevo
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel de ayuda -->
                            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-lg border border-yellow-200">
                                <h4 class="font-semibold text-yellow-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ejemplos de Propuesta de Valor Efectiva
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="font-medium text-yellow-700 mb-2">Problema:</p>
                                        <p class="text-yellow-600 italic">"Los restaurantes pequeños pierden clientes porque no pueden ofrecer delivery eficiente como las grandes cadenas."</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-yellow-700 mb-2">Solución:</p>
                                        <p class="text-yellow-600 italic">"Plataforma de delivery colaborativo que permite a restaurantes locales compartir repartidores y reducir costos 60%."</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex justify-between items-center pt-8 border-t border-gray-200 mt-8">
                            <Link :href="route('etapa4', proyectoactual.id)" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver al Modelo de Negocio
                            </Link>
                            
                            <div class="flex space-x-4">
                                <button type="submit" class="btn-primary-modern btn-super-visible mt-4" :disabled="form.processing">
                                    <span class="flex items-center justify-center gap-3">
                                        <span v-if="!form.processing" class="icon-circle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" fill="#fff" stroke="#22c55e" stroke-width="2"/>
                                                <path stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4"/>
                                            </svg>
                                        </span>
                                        <span v-else class="icon-circle">
                                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#6366f1" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="#6366f1" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                        <span class="btn-text-main">
                                            {{ form.processing ? 'GUARDANDO...' : (editando ? 'ACTUALIZAR PROPUESTA' : 'GUARDAR PROPUESTA') }}
                                        </span>
                                    </span>
                                </button>
                                
                                <Link :href="route('index.segmentoclientes', { id: proyectoactual.id })" class="btn-accent">
                                    Siguiente: Segmento de Clientes
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
    border-color: #6366f1;
    box-shadow: 0 0 0 2px #6366f133;
}
.textarea-error {
    border-color: #ef4444 !important;
    background: #fef2f2;
}
.textarea-warning {
    border-color: #f59e42;
}
.char-counter {
    position: absolute;
    bottom: 0.75rem;
    right: 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    transition: color 0.2s;
}
.char-counter-warning {
    color: #ef4444;
}
.char-counter-ok {
    color: #22c55e;
}
.error-message-modern {
    color: #ef4444;
    font-weight: 600;
    margin-top: 0.5rem;
    background: #fef2f2;
    border-left: 4px solid #ef4444;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
}
</style>

.btn-super-visible {
    background: #2563eb !important;
    border: 3.5px solid #1e293b !important;
    color: #fff !important;
    font-size: 1.25rem !important;
    font-weight: 900 !important;
    border-radius: 2.5rem !important;
    box-shadow: 0 12px 40px 0 #1e293b99, 0 2px 8px 0 #6366f1cc !important;
    min-width: 270px !important;
    min-height: 70px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.08em !important;
    margin-top: 1.5rem !important;
    margin-bottom: 0.5rem !important;
    transition: background 0.18s, box-shadow 0.18s, border 0.18s, transform 0.13s;
}
.btn-super-visible:focus,
.btn-super-visible:hover {
    background: #1e293b !important;
    color: #fff !important;
    border-color: #22c55e !important;
    box-shadow: 0 20px 60px 0 #1e293bcc, 0 4px 16px 0 #22c55ecc !important;
    transform: scale(1.06) !important;
}
.icon-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 8px #22c55e33;
    margin-right: 0.5rem;
}
.btn-text-main {
    font-size: 1.18rem;
    font-weight: 900;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #fff;
}


.btn-primary-modern {
    background: linear-gradient(90deg, #1e3a8a 0%, #2563eb 60%, #7c3aed 100%);
    color: #fff;
    font-weight: 900;
    font-size: 1.18rem;
    padding: 1.25rem 2.8rem;
    border-radius: 1.1rem;
    box-shadow: 0 10px 32px 0 #3730a3cc, 0 2px 8px 0 #6366f1cc;
    border: 3.5px solid #fff;
    outline: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background 0.18s, transform 0.13s, box-shadow 0.18s, border 0.18s, filter 0.18s;
    cursor: pointer;
    letter-spacing: 0.04em;
    margin-right: 0.5rem;
    margin-left: 0.5rem;
    min-width: 240px;
    min-height: 62px;
    gap: 0.7rem;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 0 8px #6366f1cc);
    box-sizing: border-box;
    animation: btn-pop 0.5s cubic-bezier(.36,1.64,.7,.98);
}
.btn-primary-modern:focus,
.btn-primary-modern:hover {
    background: linear-gradient(90deg, #7c3aed 0%, #2563eb 60%, #1e3a8a 100%);
    box-shadow: 0 16px 40px 0 #3730a3ee, 0 4px 16px 0 #6366f1ee;
    border-color: #fff;
    transform: translateY(-4px) scale(1.07);
    filter: brightness(1.08) drop-shadow(0 0 16px #6366f1cc);
    animation: btn-pulse 0.7s;
}
.btn-primary-modern:active {
    background: linear-gradient(90deg, #3730a3 0%, #2563eb 100%);
    box-shadow: 0 2px 8px 0 #6366f133;
    border-color: #fff;
    transform: scale(0.98);
}
.btn-primary-modern[disabled],
.btn-primary-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: #a5b4fc;
    color: #fff;
    box-shadow: none;
    border-color: #a5b4fc;
}
@keyframes btn-pop {
    0% { transform: scale(0.92); }
    80% { transform: scale(1.08); }
    100% { transform: scale(1); }
}
@keyframes btn-pulse {
    0% { filter: brightness(1.2) drop-shadow(0 0 0 #6366f1cc); }
    50% { filter: brightness(1.35) drop-shadow(0 0 24px #6366f1cc); }
    100% { filter: brightness(1.08) drop-shadow(0 0 16px #6366f1cc); }
}