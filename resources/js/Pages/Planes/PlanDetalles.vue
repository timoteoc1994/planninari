<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";

const props = defineProps({
    planes: Array,
    proyectos: Object,
});
</script>

<template>
    <AppLayout title="Etapa 2">
        <template #header>
            <div class="flex justify-between items-center">
                <Link :href="route('planes.index')">Proyectos</Link>
                /{{ planes.nombre }}
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg p-6">
                    <table class="min-w-full divide-y divide-blue-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase"
                                >
                                    ID
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase"
                                >
                                    Nombre
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase"
                                >
                                    Descripción
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase"
                                >
                                    Creado
                                </th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-blue-700 uppercase"
                                >
                                    Opciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="proyecto in proyectos.data"
                                :key="proyecto.id"
                                class="hover:bg-blue-50"
                            >
                                <td class="px-4 py-2">{{ proyecto.id }}</td>
                                <td class="px-4 py-2 font-semibold text-blue-800 flex items-center gap-2">
                                    <img
                                        v-if="proyecto.etapa1 && proyecto.etapa1.url_imagen_emprendimiento"
                                        :src="'/storage/' + proyecto.etapa1.url_imagen_emprendimiento"
                                        alt="Logo"
                                        class="w-8 h-8 rounded-full object-cover border border-blue-200"
                                    />
                                    <span v-else class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4c0 .7.5 1.2 1.2 1.2h16.8c.7 0 1.2-.5 1.2-1.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                        </svg>
                                    </span>
                                    {{ proyecto.name }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ proyecto.description }}
                                </td>
                                <td class="px-4 py-2">
                                    {{
                                        proyecto.created_at
                                            ? new Date(
                                                  proyecto.created_at
                                              ).toLocaleDateString("es-ES")
                                            : "Sin fecha"
                                    }}
                                </td>
                                <td class="px-4 py-2 flex gap-2">
                                    <Link
                                        :href="
                                            route('plan.ver.planes', proyecto.id)
                                        "
                                        class="flex items-center gap-1 text-indigo-700 hover:text-white bg-indigo-100 hover:bg-indigo-600 px-2 py-1 rounded font-semibold shadow text-xs transition-all duration-200 w-full"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5l7 7-7 7"
                                            />
                                        </svg>
                                        Detalles
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex gap-2 justify-center mt-6">
                        <button
                            v-for="link in proyectos.links"
                            :key="link.label"
                            :disabled="!link.url || link.active"
                            @click="$inertia.visit(link.url)"
                            v-html="link.label"
                            class="px-3 py-1 rounded border text-blue-700 bg-blue-50 hover:bg-blue-200 font-semibold"
                            :class="{
                                'bg-blue-700 text-white': link.active,
                                'cursor-not-allowed opacity-50': !link.url,
                            }"
                        >
                           
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
