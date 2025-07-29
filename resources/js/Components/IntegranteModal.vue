<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch, toRefs } from 'vue';

const props = defineProps({
    show: Boolean,
    integrante: Object,
    proyectoId: Number,
    editMode: Boolean
});
const emit = defineEmits(['close', 'saved']);

const tipoIdentificaciones = ['Cédula', 'Pasaporte', 'RUC', 'Visa'];
const paisesSurAmerica = [
    'Argentina', 'Bolivia', 'Brasil', 'Chile', 'Colombia', 'Ecuador',
    'Guyana', 'Paraguay', 'Perú', 'Surinam', 'Uruguay', 'Venezuela'
];

const form = useForm({
    nombre: '',
    apellido: '',
    nacionalidad: '',
    pais_residencia: '',
    ciudad_residencia: '',
    sector: '',
    tipo_identificacion: '',
    numero_identificacion: '',
    fecha_nacimiento: '',
    email: '',
    telefono: '',
    discapcidad: '',
    dispone_cuenta: ''
});

// Rellenar el formulario si es edición
watch(() => props.integrante, (val) => {
    if (val) {
        form.defaults({ ...val });
        form.reset();
    } else {
        form.reset();
    }
}, { immediate: true });

const submit = () => {
    if (props.editMode) {
        form.put(`/integrantes/${props.integrante.id}`, {
            onSuccess: () => emit('saved')
        });
    } else {
        form.post(`/proyectos/${props.proyectoId}/integrantes`, {
            onSuccess: () => emit('saved')
        });
    }
};

const closeOnBackdrop = (event) => {
    if (event.target === event.currentTarget) {
        emit('close');
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click="closeOnBackdrop">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden transform transition-all duration-300" @click.stop>
            <!-- Header del Modal -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-8 py-6 relative">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">
                                {{ editMode ? 'Editar Integrante' : 'Nuevo Integrante' }}
                            </h3>
                            <p class="text-green-100 text-sm">
                                {{ editMode ? 'Actualiza la información del miembro' : 'Agrega un nuevo miembro al equipo' }}
                            </p>
                        </div>
                    </div>
                    <button @click="emit('close')" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
                <form @submit.prevent="submit" class="p-8">
                    <div class="space-y-8">
                        <!-- Sección: Información Personal -->
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border-l-4 border-blue-500">
                                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Información Personal
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">Nombre *</label>
                                    <input v-model="form.nombre" type="text" class="form-input" placeholder="Ingrese el nombre" required />
                                    <div v-if="form.errors.nombre" class="error-message">{{ form.errors.nombre }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Apellido *</label>
                                    <input v-model="form.apellido" type="text" class="form-input" placeholder="Ingrese el apellido" required />
                                    <div v-if="form.errors.apellido" class="error-message">{{ form.errors.apellido }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input v-model="form.fecha_nacimiento" type="date" class="form-input" />
                                    <div v-if="form.errors.fecha_nacimiento" class="error-message">{{ form.errors.fecha_nacimiento }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nacionalidad</label>
                                    <input v-model="form.nacionalidad" type="text" class="form-input" placeholder="Ej: Boliviana, Peruana, etc." />
                                    <div v-if="form.errors.nacionalidad" class="error-message">{{ form.errors.nacionalidad }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Identificación -->
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border-l-4 border-purple-500">
                                <h4 class="font-semibold text-purple-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0V4a2 2 0 014 0v2"></path>
                                    </svg>
                                    Documento de Identificación
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">Tipo de Identificación *</label>
                                    <select v-model="form.tipo_identificacion" class="form-input" required>
                                        <option value="">Seleccione el tipo de documento</option>
                                        <option v-for="tipo in tipoIdentificaciones" :key="tipo" :value="tipo">{{ tipo }}</option>
                                    </select>
                                    <div v-if="form.errors.tipo_identificacion" class="error-message">{{ form.errors.tipo_identificacion }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Número de Identificación *</label>
                                    <input v-model="form.numero_identificacion" type="text" class="form-input" placeholder="Número del documento" required />
                                    <div v-if="form.errors.numero_identificacion" class="error-message">{{ form.errors.numero_identificacion }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Ubicación -->
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-lg border-l-4 border-orange-500">
                                <h4 class="font-semibold text-orange-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ubicación y Residencia
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">País de Residencia</label>
                                    <select v-model="form.pais_residencia" class="form-input">
                                        <option value="">Seleccione un país</option>
                                        <option v-for="pais in paisesSurAmerica" :key="pais" :value="pais">{{ pais }}</option>
                                    </select>
                                    <div v-if="form.errors.pais_residencia" class="error-message">{{ form.errors.pais_residencia }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ciudad de Residencia</label>
                                    <input v-model="form.ciudad_residencia" type="text" class="form-input" placeholder="Ciudad donde reside" />
                                    <div v-if="form.errors.ciudad_residencia" class="error-message">{{ form.errors.ciudad_residencia }}</div>
                                </div>

                                <div class="form-group lg:col-span-2">
                                    <label class="form-label">Sector/Zona</label>
                                    <input v-model="form.sector" type="text" class="form-input" placeholder="Sector, barrio o zona" />
                                    <div v-if="form.errors.sector" class="error-message">{{ form.errors.sector }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Contacto -->
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border-l-4 border-green-500">
                                <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Información de Contacto
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input v-model="form.email" type="email" class="form-input" placeholder="correo@ejemplo.com" />
                                    <div v-if="form.errors.email" class="error-message">{{ form.errors.email }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Teléfono</label>
                                    <input v-model="form.telefono" type="text" class="form-input" placeholder="+591 12345678" />
                                    <div v-if="form.errors.telefono" class="error-message">{{ form.errors.telefono }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Información Adicional -->
                        <div class="space-y-6">
                            <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-4 rounded-lg border-l-4 border-gray-500">
                                <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Información Adicional
                                </h4>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">¿Tiene alguna discapacidad?</label>
                                    <input v-model="form.discapcidad" type="text" class="form-input" placeholder="Especifique si tiene alguna discapacidad" />
                                    <div v-if="form.errors.discapcidad" class="error-message">{{ form.errors.discapcidad }}</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">¿Dispone de cuenta bancaria?</label>
                                    <select v-model="form.dispone_cuenta" class="form-input">
                                        <option value="">Seleccione una opción</option>
                                        <option value="Si">Sí</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div v-if="form.errors.dispone_cuenta" class="error-message">{{ form.errors.dispone_cuenta }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200 mt-8">
                        <button type="button" @click="emit('close')" class="btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </button>
                        <button type="submit" class="btn-primary" :disabled="form.processing">
                            <svg v-if="!form.processing" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg v-else class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Guardando...' : (editMode ? 'Actualizar Integrante' : 'Crear Integrante') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
    font-size: 0.875rem;
}

.form-input::placeholder {
    color: #9ca3af;
}

.form-input:hover {
    border-color: #9ca3af;
}

.form-input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
    background: linear-gradient(to right, #10b981, #059669);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(to right, #059669, #047857);
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
}

.btn-secondary:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
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
@keyframes slideIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.bg-white {
    animation: slideIn 0.3s ease-out;
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
.btn-secondary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
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
    
    .max-w-5xl {
        max-width: 95vw;
    }
    
    .px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-6 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
}

/* Efectos visuales mejorados */
.form-group:hover .form-label {
    color: #111827;
}

/* Scrollbar personalizada */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Efectos de transición para las secciones */
.space-y-6 > * {
    animation: fadeIn 0.4s ease-out;
}

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

/* Mejoras visuales para las secciones */
.border-l-4 {
    position: relative;
}

.border-l-4::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    border-radius: 0 2px 2px 0;
}

/* Estados de validación */
.form-input.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-input.success {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Iconos SVG */
svg {
    flex-shrink: 0;
}

/* Backdrop blur effect */
.fixed.inset-0 {
    backdrop-filter: blur(4px);
}
</style>