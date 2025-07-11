<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { usePage, router, Link } from "@inertiajs/vue3";

const props = defineProps({
    proyecto_id: Number,
});


</script>

<template>
    <AppLayout title="Etapa 4">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Etapa 4 - Modelo de negocio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow"
                    >
                        <h3
                            class="text-lg font-bold mb-4 text-gray-700 text-center"
                        >
                            Propuesta de valor
                        </h3>
                        <Link
                            :href="
                                route('index.recursosclave', {
                                    id: props.proyecto_id,
                                })
                            "
                        >
                            <button
                                @click="irARuta(tarjeta.ruta)"
                                class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                Llenar
                            </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow"
                    >
                        <h3
                            class="text-lg font-bold mb-4 text-gray-700 text-center"
                        >
                            Segmento de clientes
                        </h3>
                        <Link
                            :href="
                                route('index.segmentoclientes', {
                                   id: props.proyecto_id,
                                })
                            "
                        >
                            <button
                                @click="irARuta(tarjeta.ruta)"
                                class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                Llenar
                            </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow"
                    >
                        <h3
                            class="text-lg font-bold mb-4 text-gray-700 text-center"
                        >
                            Relación con los clientes
                        </h3>
                        <Link
                            :href="
                                route('index.relacionclientes', {
                                    id: props.proyecto_id,
                                })
                            "
                        >
                            <button
                                @click="irARuta(tarjeta.ruta)"
                                class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                Llenar
                            </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow"
                    >
                        <h3
                            class="text-lg font-bold mb-4 text-gray-700 text-center"
                        >
                            Canales
                        </h3>
                        <Link
                            :href="
                                route('index.canales', {
                                    id: props.proyecto_id,
                                })
                            "
                        >
                            <button
                                @click="irARuta(tarjeta.ruta)"
                                class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                Llenar
                            </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow"
                    >
                        <h3
                            class="text-lg font-bold mb-4 text-gray-700 text-center"
                        >
                            Actividades clave
                        </h3>
                        <Link
                            :href="
                                route('index.actividadesclave', {
                                    id: props.proyecto_id,
                                })
                            "
                        >
                            <button
                                @click="irARuta(tarjeta.ruta)"
                                class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                Llenar
                            </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow"
                    >
                        <h3
                            class="text-lg font-bold mb-4 text-gray-700 text-center"
                        >
                            Recursos clave
                        </h3>
                        <Link
                            :href="
                                route('index.recursosclave', {
                                    id: props.proyecto_id,
                                })
                            "
                        >
                            <button
                                @click="irARuta(tarjeta.ruta)"
                                class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                Llenar
                            </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow"
                    >
                        <h3
                            class="text-lg font-bold mb-4 text-gray-700 text-center"
                        >
                            Alianza clave
                        </h3>
                        <Link
                            :href="
                                route('index.alianzasclave', {
                                    id: props.proyecto_id,
                                })
                            "
                        >
                            <button
                                @click="irARuta(tarjeta.ruta)"
                                class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                            >
                                Llenar
                            </button>
                        </Link>
                    </div>

                    
                </div>
            </div>
        </div>
    </AppLayout>
</template>
