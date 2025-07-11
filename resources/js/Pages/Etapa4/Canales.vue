<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    canales: Object,
    proyecto_id: [Number, String], // Acepta tanto Number como String
});
// Inicializa el formulario con los datos si existen, o vacío si no
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

// Acceder a los mensajes flash
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
    <AppLayout title="Actividades clave">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Actividades clave
                </h2>
            </div>
        </template>

        <div class="py-12">
            <!-- Mensaje de error -->
            <div
                v-if="errorMessage"
                class="max-w-4xl mx-auto sm:px-6 lg:px-8 mb-4"
            >
                <div
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"
                >
                    {{ errorMessage }}
                </div>
            </div>

            <!-- Mensaje de éxito -->
            <div
                v-if="successMessage"
                class="max-w-4xl mx-auto sm:px-6 lg:px-8 mb-4"
            >
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"
                >
                    {{ successMessage }}
                </div>
            </div>

            <div
                class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow"
            >
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="canales" class="block mb-1 font-semibold"
                            >Canales de comunicación</label
                        >
                        <textarea
                            id="canales"
                            v-model="form.canalescomunicacion"
                            rows="4"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="canales" class="block mb-1 font-semibold"
                            >Canales de distribución</label
                        >
                        <textarea
                            id="canales"
                            v-model="form.canalesdistribucion"
                            rows="4"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                for="facebook"
                                class="block mb-1 font-semibold"
                                >Facebook</label
                            ><input type="text" v-model="form.facebook" />
                        </div>
                        <div>
                            <label
                                for="instagram"
                                class="block mb-1 font-semibold"
                                >Instagram</label
                            ><input type="text" v-model="form.instagram" />
                        </div>
                        <div>
                            <label
                                for="whatsapp"
                                class="block mb-1 font-semibold"
                                >WhatsApp</label
                            ><input type="text" v-model="form.whatsapp" />
                        </div>
                        <div>
                            <label
                                for="linkedin"
                                class="block mb-1 font-semibold"
                                >LinkedIn</label
                            ><input type="text" v-model="form.linkedin" />
                        </div>
                        <div>
                            <label for="tiktok" class="block mb-1 font-semibold"
                                >TikTok</label
                            ><input type="text" v-model="form.tiktok" />
                        </div>
                        <div>
                            <label
                                for="twitter"
                                class="block mb-1 font-semibold"
                                >X</label
                            ><input type="text" v-model="form.twitter" />
                        </div>
                        <div>
                            <label
                                for="youtube"
                                class="block mb-1 font-semibold"
                                >YouTube</label
                            ><input type="text" v-model="form.youtube" />
                        </div>
                        <div>
                            <label for="web" class="block mb-1 font-semibold"
                                >Sitio web</label
                            ><input type="text" v-model="form.web" />
                        </div>
                        <div>
                            <label for="otro" class="block mb-1 font-semibold"
                                >Otro</label
                            ><input type="text" v-model="form.otro" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                        >
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
