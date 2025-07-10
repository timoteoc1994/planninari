<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    etapa1: Object,
    proyecto_id: Number,
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
    <AppLayout title="Etapa 1">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Etapa 1
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
                    <div>
                        <label class="block font-semibold">Nombre del Emprendimiento</label>
                        <input v-model="form.nombre_emprendimiento" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Nacionalidad del Emprendedor</label>
                        <input v-model="form.nacionalidad_emprendedor" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">País del Emprendedor</label>
                        <select v-model="form.pais_emprendedor" class="input">
                            <option value="">Seleccione</option>
                            <option v-for="pais in paisesSurAmerica" :key="pais" :value="pais">{{ pais }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold">Ciudad del Emprendedor</label>
                        <input v-model="form.ciudad_emprendedor" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Entidad Asesora</label>
                        <input v-model="form.entidad_asesora" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Nombre del Asesor</label>
                        <input v-model="form.nombre_asesor" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Nombre del Representante del emprendimiento</label>
                        <input v-model="form.nombre_representante_emprendimiento" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Giro del Negocio</label>
                        <select v-model="form.giro_negocio" class="input">
                            <option value="">Seleccione</option>
                            <option v-for="giro in girosNegocio" :key="giro" :value="giro">{{ giro }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold">Dirección</label>
                        <input v-model="form.direccion_emprendimiento" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Nro.</label>
                        <input v-model="form.nro_emprendimiento" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Calle Secundaria</label>
                        <input v-model="form.calle_secundaria_emprendimiento" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Barrio/Distrito</label>
                        <input v-model="form.barrio_distrito_emprendimiento" type="text" class="input" />
                    </div>
                    <div>
                        <label class="block font-semibold">Ubicación Geoespacial</label>
                        <div class="flex gap-2">
                            <input v-model="form.ubicacion_geoespacial_emprendimiento" type="text" class="input flex-1" />
                            <button type="button" @click="getUbicacion" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Obtener</button>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold">¿Dispone de Cuenta Corriente?</label>
                        <select v-model="form.cuenta_corriente" class="input">
                            <option value="">Seleccione</option>
                            <option value="Si">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <template v-if="mostrarInstitucion">
                        <div>
                            <label class="block font-semibold">Tipo Institución Financiera</label>
                            <select v-model="form.tipo_institucion_financiera" class="input">
                                <option value="">Seleccione</option>
                                <option v-for="tipo in tiposInstitucion" :key="tipo" :value="tipo">{{ tipo }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold">Nombre Institución Financiera</label>
                            <input v-model="form.nombre_institucion_financiera" type="text" class="input" />
                        </div>
                        <div>
                            <label class="block font-semibold">Ingresos Actuales</label>
                            <input v-model="form.ingresos_actuales" type="text" class="input" />
                        </div>
                    </template>
                    <template v-if="mostrarMotivoNoIngresos">
                        <div>
                            <label class="block font-semibold">Motivo No Ingresos</label>
                            <input v-model="form.motivo_no_ingresos" type="text" class="input" />
                        </div>
                    </template>
                    <div>
                        <label class="block font-semibold">Tipo Emprendimiento</label>
                        <select v-model="form.tipo_emprendimiento" class="input">
                            <option value="">Seleccione</option>
                            <option v-for="tipo in tiposEmprendimiento" :key="tipo" :value="tipo">{{ tipo }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold">Etapa Emprendimiento</label>
                        <select v-model="form.etapa_emprendimiento" class="input">
                            <option value="">Seleccione</option>
                            <option v-for="etapa in etapasEmprendimiento" :key="etapa" :value="etapa">{{ etapa }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold">Imagen del Emprendimiento</label>
                        <input type="file" class="input" @change="e => form.url_imagen_emprendimiento = e.target.files[0]" accept="image/*" />
                        <div v-if="props.etapa1?.url_imagen_emprendimiento" class="mt-2">
                            <img
                                width='80px'
                                :src="`/storage/${props.etapa1.url_imagen_emprendimiento}`"
                                alt="Imagen actual"
                                class="h-24 rounded"
                            />
                        </div>
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Guardar
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