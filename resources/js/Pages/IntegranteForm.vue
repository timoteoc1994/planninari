<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    proyecto_id: Number,
    integrante: Object
});

const tipoIdentificaciones = ['Cédula', 'Pasaporte', 'RUC', 'Visa'];
const paisesSurAmerica = [
    'Argentina', 'Bolivia', 'Brasil', 'Chile', 'Colombia', 'Ecuador',
    'Guyana', 'Paraguay', 'Perú', 'Surinam', 'Uruguay', 'Venezuela'
];

const form = useForm({
    nombre: props.integrante?.nombre ?? '',
    apellido: props.integrante?.apellido ?? '',
    nacionalidad: props.integrante?.nacionalidad ?? '',
    pais_residencia: props.integrante?.pais_residencia ?? '',
    ciudad_residencia: props.integrante?.ciudad_residencia ?? '',
    sector: props.integrante?.sector ?? '',
    tipo_identificacion: props.integrante?.tipo_identificacion ?? '',
    numero_identificacion: props.integrante?.numero_identificacion ?? '',
    fecha_nacimiento: props.integrante?.fecha_nacimiento ?? '',
    email: props.integrante?.email ?? '',
    telefono: props.integrante?.telefono ?? '',
    discapcidad: props.integrante?.discapcidad ?? '',
    dispone_cuenta: props.integrante?.dispone_cuenta ?? '',
});

const submit = () => {
    if (props.integrante) {
        form.put(`/integrantes/${props.integrante.id}`, {
            onSuccess: () => router.visit(`/proyectos/${props.proyecto_id}/integrantes`)
        });
    } else {
        form.post(`/proyectos/${props.proyecto_id}/integrantes`, {
            onSuccess: () => router.visit(`/proyectos/${props.proyecto_id}/integrantes`)
        });
    }
};
</script>

<template>
    <AppLayout :title="props.integrante ? 'Editar Integrante' : 'Nuevo Integrante'">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ props.integrante ? 'Editar Integrante' : 'Nuevo Integrante' }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">
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
                        <label>¿Discapacidad?</label>
                        <select v-model="form.discapcidad" class="input">
                            <option value="">Seleccione</option>
                            <option value="Si">Sí</option>
                            <option value="No">No</option>
                        </select>
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
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            {{ props.integrante ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
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