<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    etapa3: Object,
    proyecto_id: Number,
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
    <AppLayout title="Etapa 3">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Etapa 3 - {{ editando ? "Editar" : "Registrar" }} Problema y
                Solución
            </h2>
        </template>

        <div class="py-12">
            <div
                class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow"
            >
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Problema</label>
                        <textarea
                            v-model="form.problema"
                            class="w-full border rounded px-3 py-2"
                            required
                        ></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Solución</label>
                        <textarea
                            v-model="form.solucion"
                            class="w-full border rounded px-3 py-2"
                            required
                        ></textarea>
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
