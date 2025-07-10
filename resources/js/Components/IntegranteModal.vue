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
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl">
            <h3 class="text-lg font-bold mb-4">{{ editMode ? 'Editar Integrante' : 'Nuevo Integrante' }}</h3>
            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label>Nombre</label>
                    <input v-model="form.nombre" type="text" class="input" required />
                </div>
                <div>
                    <label>Apellido</label>
                    <input v-model="form.apellido" type="text" class="input" required />
                </div>
                <div>
                    <label>Tipo de Identificación</label>
                    <select v-model="form.tipo_identificacion" class="input" required>
                        <option value="">Seleccione</option>
                        <option v-for="tipo in tipoIdentificaciones" :key="tipo" :value="tipo">{{ tipo }}</option>
                    </select>
                </div>
                <div>
                    <label>Número de Identificación</label>
                    <input v-model="form.numero_identificacion" type="text" class="input" required />
                </div>
                <div>
                    <label>Nacionalidad</label>
                    <input v-model="form.nacionalidad" type="text" class="input" />
                </div>
                <div>
                <label>País de Residencia</label>
                <select v-model="form.pais_residencia" class="input">
                    <option value="">Seleccione</option>
                    <option v-for="pais in paisesSurAmerica" :key="pais" :value="pais">{{ pais }}</option>
                </select>
            </div>
                <div>
                    <label>Ciudad de Residencia</label>
                    <input v-model="form.ciudad_residencia" type="text" class="input" />
                </div>
                <div>
                    <label>Sector</label>
                    <input v-model="form.sector" type="text" class="input" />
                </div>
                <div>
                    <label>Fecha de Nacimiento</label>
                    <input v-model="form.fecha_nacimiento" type="date" class="input" />
                </div>
                <div>
                    <label>Email</label>
                    <input v-model="form.email" type="email" class="input" />
                </div>
                <div>
                    <label>Teléfono</label>
                    <input v-model="form.telefono" type="text" class="input" />
                </div>
                <div>
                    <label>Discapacidad</label>
                    <input v-model="form.discapcidad" type="text" class="input" />
                </div>
                <div>
                    <label>¿Dispone de cuenta?</label>
                    <select v-model="form.dispone_cuenta" class="input">
                        <option value="">Seleccione</option>
                        <option value="Si">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-end gap-2 mt-4">
                    <button type="button" @click="emit('close')" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ editMode ? 'Actualizar' : 'Crear' }}</button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem;
    margin-top: 0.25rem;
    margin-bottom: 0.5rem;
}
</style>