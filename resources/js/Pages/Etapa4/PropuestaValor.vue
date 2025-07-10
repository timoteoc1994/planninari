<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    proyecto_id: Number,
    propuestavalor: Object,
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
    <AppLayout title="Propuesta de valor">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Propuesta de valor
                </h2>
            </div>
        </template>
        
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-8 rounded shadow">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Problema</label>
                        <textarea
                            v-model="form.propuestavalor"
                            class="w-full border rounded px-3 py-2"
                            required
                        ></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Solución</label>
                        <textarea
                            v-model="form.caracteristicas"
                            class="w-full border rounded px-3 py-2"
                            required
                        ></textarea>
                    </div>
                    
                    <!-- Imágenes existentes -->
                    <div v-if="imagenesExistentes.length" class="mb-4">
                        <label class="block mb-1 font-semibold">Imágenes actuales</label>
                        <div class="flex flex-wrap gap-2">
                            <div
                                v-for="(img, idx) in imagenesExistentes"
                                :key="img.ruta"
                                class="relative w-20 h-20 overflow-hidden rounded border bg-gray-100 flex items-center justify-center"
                            >
                                <img
                                    :src="img.url"
                                    class="object-cover w-full h-full"
                                    :alt="`Imagen ${idx + 1}`"
                                />
                                <button
                                    type="button"
                                    @click.stop="eliminarImagenExistente(idx)"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600"
                                    title="Eliminar imagen existente"
                                >
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Nueva área para subir imágenes -->
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Añadir nuevas imágenes</label>
                        <div
                            class="w-full border-2 border-dashed rounded px-3 py-6 text-center cursor-pointer hover:bg-gray-50"
                            @click="$refs.inputImagenes.click()"
                            @dragover.prevent
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
                            <span class="block text-gray-600"
                                >Haz clic o arrastra
                                <b>solo imágenes</b> aquí</span
                            >
                        </div>
                        
                        <!-- Preview de nuevas imágenes -->
                        <div
                            v-if="imagenesPreview.length"
                            class="mt-2 flex flex-wrap gap-2"
                        >
                            <div
                                v-for="(img, idx) in imagenesPreview"
                                :key="img.url"
                                class="relative w-20 h-20 overflow-hidden rounded border bg-gray-100 flex items-center justify-center"
                            >
                                <img
                                    :src="img.url"
                                    class="object-cover w-full h-full"
                                />
                                <button
                                    type="button"
                                    @click.stop="eliminarImagen(idx)"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600"
                                    title="Eliminar nueva imagen"
                                >
                                    ×
                                </button>
                            </div>
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