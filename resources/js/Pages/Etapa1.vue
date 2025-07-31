<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    etapa1: Object,
    proyecto_id: Number,
    proyectoactual: Object,
});

const paisesSurAmerica = [
    'Argentina', 'Bolivia', 'Brasil', 'Chile', 'Colombia', 'Ecuador',
    'Guyana', 'Paraguay', 'Perú', 'Surinam', 'Uruguay', 'Venezuela'
];

const girosNegocio = [
    'Tecnología', 'Belleza', 'Alimentos', 'Educación', 'Salud', 'Construcción',
    'Comercio', 'Servicios', 'Turismo', 'Artesanía', 'Transporte', 'Otro'
];

const tiposInstitucion = [
    'Banco', 'Cooperativa', 'Financiera', 'Caja de ahorro', 'Otro'
];

const tiposEmprendimiento = [
    'Individual', 'Asociativo'
];

const etapasEmprendimiento = [
    'Ideación', 'Naciente', 'Puesta en marcha', 'Desarrollo inicial', 'Consolidación'
];

const form = useForm({
    nombre_emprendimiento: props.etapa1?.nombre_emprendimiento ?? '',
    nacionalidad_emprendedor: props.etapa1?.nacionalidad_emprendedor ?? '',
    pais_emprendedor: props.etapa1?.pais_emprendedor ?? '',
    ciudad_emprendedor: props.etapa1?.ciudad_emprendedor ?? '',
    entidad_asesora: props.etapa1?.entidad_asesora ?? '',
    nombre_asesor: props.etapa1?.nombre_asesor ?? '',
    nombre_representante_emprendimiento: props.etapa1?.nombre_representante_emprendimiento ?? '',
    giro_negocio: props.etapa1?.giro_negocio ?? '',
    direccion_emprendimiento: props.etapa1?.direccion_emprendimiento ?? '',
    nro_emprendimiento: props.etapa1?.nro_emprendimiento ?? '',
    calle_secundaria_emprendimiento: props.etapa1?.calle_secundaria_emprendimiento ?? '',
    barrio_distrito_emprendimiento: props.etapa1?.barrio_distrito_emprendimiento ?? '',
    ubicacion_geoespacial_emprendimiento: props.etapa1?.ubicacion_geoespacial_emprendimiento ?? '',
    cuenta_corriente: props.etapa1?.cuenta_corriente ?? '',
    tipo_institucion_financiera: props.etapa1?.tipo_institucion_financiera ?? '',
    nombre_institucion_financiera: props.etapa1?.nombre_institucion_financiera ?? '',
    ingresos_actuales: props.etapa1?.ingresos_actuales ?? '',
    motivo_no_ingresos: props.etapa1?.motivo_no_ingresos ?? '',
    tipo_emprendimiento: props.etapa1?.tipo_emprendimiento ?? '',
    etapa_emprendimiento: props.etapa1?.etapa_emprendimiento ?? '',
    url_imagen_emprendimiento: null,
    telefono_emprendimiento: props.etapa1?.telefono_emprendimiento ?? '',
    email_emprendimiento: props.etapa1?.email_emprendimiento ?? '',
});

const mostrarInstitucion = computed(() => form.cuenta_corriente === 'Si');
const mostrarMotivoNoIngresos = computed(() => form.cuenta_corriente === 'No');

const getUbicacion = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                form.ubicacion_geoespacial_emprendimiento = `${position.coords.latitude},${position.coords.longitude}`;
            },
            () => {
                alert('No se pudo obtener la ubicación');
            }
        );
    } else {
        alert('Geolocalización no soportada');
    }
};

const submit = () => {
    form.post(`/etapa1/${props.proyecto_id}`, {
        forceFormData: true
    });
};
</script>

<template>
    <AppLayout title="Etapa 1 - Datos Generales">
        <template #header>
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-8">
                <div class="max-w-6xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-blue-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <Link class="hover:text-white transition-colors" :href="route('etapa1.menu', proyectoactual.id)">
                            /Menu
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Etapa 1</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Datos Generales
                            </h2>
                            <p class="text-blue-100 mt-1">Información básica del proyecto y emprendimiento</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-6xl mx-auto px-4">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-blue-100">
                        <h3 class="text-xl font-semibold text-gray-800">Formulario de Datos Generales</h3>
                        <p class="text-gray-600 mt-1">Complete la información básica de su emprendimiento</p>
                    </div>
                    
                    <form @submit.prevent="submit" class="p-8" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Sección: Información del Emprendimiento -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                                    <h4 class="font-semibold text-blue-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6m-6 4h6"></path>
                                        </svg>
                                        Información del Emprendimiento
                                    </h4>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nombre del Emprendimiento *</label>
                                    <input v-model="form.nombre_emprendimiento" type="text" class="form-input" placeholder="Ingrese el nombre de su emprendimiento" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Giro del Negocio *</label>
                                    <select v-model="form.giro_negocio" class="form-input">
                                        <option value="">Seleccione un giro de negocio</option>
                                        <option v-for="giro in girosNegocio" :key="giro" :value="giro">{{ giro }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nombre del Representante *</label>
                                    <input v-model="form.nombre_representante_emprendimiento" type="text" class="form-input" placeholder="Nombre completo del representante" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Tipo de Emprendimiento *</label>
                                    <select v-model="form.tipo_emprendimiento" class="form-input">
                                        <option value="">Seleccione el tipo</option>
                                        <option v-for="tipo in tiposEmprendimiento" :key="tipo" :value="tipo">{{ tipo }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Etapa del Emprendimiento *</label>
                                    <select v-model="form.etapa_emprendimiento" class="form-input">
                                        <option value="">Seleccione la etapa actual</option>
                                        <option v-for="etapa in etapasEmprendimiento" :key="etapa" :value="etapa">{{ etapa }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Imagen del Emprendimiento</label>
                                    <div class="mt-2">
                                        <input type="file" class="form-input" @change="e => form.url_imagen_emprendimiento = e.target.files[0]" accept="image/*" />
                                        <p class="text-sm text-gray-500 mt-1">Formatos aceptados: JPG, PNG, GIF (máx. 5MB)</p>
                                    </div>
                                    <div v-if="props.etapa1?.url_imagen_emprendimiento" class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Imagen actual:</p>
                                        <img
                                            :src="`/storage/${props.etapa1.url_imagen_emprendimiento}`"
                                            alt="Imagen del emprendimiento"
                                            class="h-24 w-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Sección: Datos del Emprendedor -->
                            <div class="space-y-6">
                                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                                    <h4 class="font-semibold text-green-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Datos del Emprendedor
                                    </h4>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nacionalidad *</label>
                                    <input v-model="form.nacionalidad_emprendedor" type="text" class="form-input" placeholder="Ej: Boliviana, Peruana, etc." />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">País *</label>
                                    <select v-model="form.pais_emprendedor" class="form-input">
                                        <option value="">Seleccione un país</option>
                                        <option v-for="pais in paisesSurAmerica" :key="pais" :value="pais">{{ pais }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ciudad *</label>
                                    <input v-model="form.ciudad_emprendedor" type="text" class="form-input" placeholder="Ciudad de residencia" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Entidad Asesora</label>
                                    <input v-model="form.entidad_asesora" type="text" class="form-input" placeholder="Nombre de la entidad asesora" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nombre del Asesor</label>
                                    <input v-model="form.nombre_asesor" type="text" class="form-input" placeholder="Nombre del asesor asignado" />
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Ubicación del Emprendimiento -->
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500 mb-6">
                                <h4 class="font-semibold text-purple-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ubicación del Emprendimiento
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">Dirección Principal *</label>
                                    <input v-model="form.direccion_emprendimiento" type="text" class="form-input" placeholder="Calle principal" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Número</label>
                                    <input v-model="form.nro_emprendimiento" type="text" class="form-input" placeholder="Número de la dirección" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Calle Secundaria</label>
                                    <input v-model="form.calle_secundaria_emprendimiento" type="text" class="form-input" placeholder="Calle de referencia" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Barrio/Distrito</label>
                                    <input v-model="form.barrio_distrito_emprendimiento" type="text" class="form-input" placeholder="Zona o distrito" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Teléfono</label>
                                    <input v-model="form.telefono_emprendimiento" type="text" class="form-input" placeholder="Número de teléfono" />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input v-model="form.email_emprendimiento" type="text" class="form-input" placeholder="Email del emprendimiento" />
                                </div>

                                <div class="form-group lg:col-span-2">
                                    <label class="form-label">Ubicación Geoespacial</label>
                                    <div class="flex gap-3 mt-2">
                                        <input v-model="form.ubicacion_geoespacial_emprendimiento" type="text" class="form-input flex-1" placeholder="Latitud, Longitud" readonly />
                                        <button type="button" @click="getUbicacion" class="btn-secondary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            Obtener Ubicación
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Información Financiera -->
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-4 rounded-lg border-l-4 border-orange-500 mb-6">
                                <h4 class="font-semibold text-orange-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Información Financiera
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">¿Dispone de Cuenta Corriente? *</label>
                                    <select v-model="form.cuenta_corriente" class="form-input">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Si">Sí</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <template v-if="mostrarInstitucion">
                                    <div class="form-group">
                                        <label class="form-label">Tipo de Institución Financiera</label>
                                        <select v-model="form.tipo_institucion_financiera" class="form-input">
                                            <option value="">Seleccione el tipo</option>
                                            <option v-for="tipo in tiposInstitucion" :key="tipo" :value="tipo">{{ tipo }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Nombre de la Institución</label>
                                        <input v-model="form.nombre_institucion_financiera" type="text" class="form-input" placeholder="Nombre del banco o institución" />
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Ingresos Actuales (USD)</label>
                                        <input v-model="form.ingresos_actuales" type="number" step="0.01" class="form-input" placeholder="0.00" />
                                    </div>
                                </template>

                                <template v-if="mostrarMotivoNoIngresos">
                                    <div class="form-group lg:col-span-2">
                                        <label class="form-label">Motivo por el cual no tiene ingresos</label>
                                        <textarea v-model="form.motivo_no_ingresos" class="form-input" rows="3" placeholder="Explique brevemente el motivo"></textarea>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                            <Link :href="route('projects.stages.show', proyectoactual.id)" class="btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </Link>
                            <button type="submit" class="btn-primary" :disabled="form.processing">
                                <svg v-if="!form.processing" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg v-else class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Guardando...' : 'Guardar Información' }}
                            </button>
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

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease-in-out;
    color: #111827;
    background-color: white;
}

.form-input::placeholder {
    color: #9ca3af;
}

.form-input:hover {
    border-color: #9ca3af;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
    background: linear-gradient(to right, #2563eb, #1d4ed8);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.btn-primary:hover {
    background: linear-gradient(to right, #1d4ed8, #1e40af);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #9ca3af;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
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
}

/* Selectores personalizados */
select.form-input {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    cursor: pointer;
}

/* Campos de archivo */
input[type="file"].form-input {
    padding: 0.5rem;
}

input[type="file"].form-input::file-selector-button {
    margin-right: 1rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    background-color: #eff6ff;
    color: #1d4ed8;
    cursor: pointer;
    transition: background-color 0.2s;
}

input[type="file"].form-input::file-selector-button:hover {
    background-color: #dbeafe;
}

/* Textarea */
textarea.form-input {
    resize: vertical;
    min-height: 4rem;
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-group {
    animation: fadeIn 0.3s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-input {
        font-size: 16px; /* Prevent zoom on iOS */
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
        margin-bottom: 0.5rem;
    }
}

/* Focus states mejorados */
.btn-primary:focus,
.btn-secondary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Hover effects */
.form-group:hover .form-label {
    color: #111827;
}

/* Iconos SVG */
.form-group svg {
    flex-shrink: 0;
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

/* Mejoras visuales para las secciones */
.section-header {
    background: linear-gradient(to right, #eff6ff, #e0e7ff);
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 4px solid;
    margin-bottom: 1.5rem;
}

.section-header.blue {
    border-left-color: #3b82f6;
}

.section-header.green {
    border-left-color: #10b981;
    background: linear-gradient(to right, #ecfdf5, #d1fae5);
}

.section-header.purple {
    border-left-color: #8b5cf6;
    background: linear-gradient(to right, #f5f3ff, #e9d5ff);
}

.section-header.orange {
    border-left-color: #f59e0b;
    background: linear-gradient(to right, #fffbeb, #fef3c7);
}

/* Estilos para mensajes de error */
.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Mejorar el aspecto de las imágenes */
.image-preview {
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    object-fit: cover;
}
</style>