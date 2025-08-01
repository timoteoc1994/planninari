<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { usePage, router, Link } from "@inertiajs/vue3";

const props = defineProps({
    proyecto_id: Number,
});


</script>

<template>
    <AppLayout title="Etapa 8">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Etapa 8 - Planificación financiera e inversiones
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Tarjeta Receta estándar -->
                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Receta estándar
                        </h3>
                        <Link :href="route('recetaestandar.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>

                    <!-- Tarjeta Material Insumo -->
                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Material Insumo
                        </h3>
                        <Link :href="route('materialinsumo.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Costo Variable
                        </h3>
                        <Link :href="route('costovariable.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Empleados
                        </h3>
                        <Link :href="route('empleados.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Costos fijos
                        </h3>
                        <Link :href="route('costos-fijos.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>


                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Pagos Mensuales
                        </h3>
                        <Link :href="route('pagos-mensuales.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>


                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Punto de Equilibrio Dinero
                        </h3>
                        <Link :href="route('punto-equilibrio.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Punto de Equilibrio Productos
                        </h3>
                        <Link :href="route('punto-equilibrio-productos.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>

                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Presupuesto Ventas
                        </h3>
                        <Link :href="route('presupuesto-ventas.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>
                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                            Flujo de Caja
                        </h3>
                        <Link :href="route('flujo-caja.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>
                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                         TIR y VAN
                        </h3>
                        <Link :href="route('flujo-caja.analisis', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>
                    
                    <div
                        class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-bold mb-4 text-gray-700 text-center">
                         Presupuesto Marketing
                        </h3>
                        <Link :href="route('presupuesto-marketing.index', { id: props.proyecto_id })">
                        <button class="mt-auto px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Llenar
                        </button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>