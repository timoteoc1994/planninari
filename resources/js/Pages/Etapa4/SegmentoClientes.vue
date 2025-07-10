<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    segmentoclientes: Object,
    proyecto_id: Number,
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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Segmento de Clientes
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div
                class="max-w-6xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow"
            >
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold"
                            >Indique su caracteristicas de nichos de
                            mercado</label
                        >
                        <textarea
                            v-model="form.caracteristicas_segmento"
                            class="w-full border rounded px-3 py-2"
                            required
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block mb-1 font-semibold text-center"
                                >Segmento de empresas</label
                            >
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr>
                                        <th class="text-center px-2 py-1">
                                            Segmento
                                        </th>
                                        <th class="text-center px-2 py-1">
                                            Descripción
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-2 py-1">Geográfico</td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_empresas_geografico
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1">
                                            Nivel de ingresos
                                        </td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_empresas_nivel_ingreso
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1">
                                            Número de empleados
                                        </td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_empresas_numero_empleador
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1">
                                            Tamaño del mercado
                                        </td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_empresas_tamano_empresa
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-center"
                                >Segmento personas</label
                            >

                            <table class="w-full border-collapse">
                                <thead>
                                    <tr>
                                        <th class="text-center px-2 py-1">
                                            Segmento
                                        </th>
                                        <th class="text-center px-2 py-1">
                                            Descripción
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-2 py-1">Conductal</td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_personas_conductal
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1">Geográfico</td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_personas_geografico
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1">Psicográfico</td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_personas_psicografico
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-2 py-1">
                                            Tamaño del mercado
                                        </td>
                                        <td class="px-2 py-1">
                                            <input
                                                v-model="
                                                    form.segmento_personas_tamano_mercado
                                                "
                                                type="text"
                                                class="w-full border rounded px-2 py-1"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        {{ editando ? "Actualizar" : "Guardar" }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
