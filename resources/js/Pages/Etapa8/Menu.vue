<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { usePage, router, Link } from "@inertiajs/vue3";

const props = defineProps({
    proyecto_id: Number,
    proyectoactual: Object
});

// Iconos para cada tarjeta
const icons = {
    receta: 'M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3zm0 0V4m0 10v4m-4-4H4m16 0h-4',
    insumo: 'M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6',
    variable: 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 10v4',
    empleados: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    fijo: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
    pagos: 'M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197',
    equilibrio: 'M13 10V3L4 14h7v7l9-11h-7z',
    propuesta: 'M13 10V3L4 14h7v7l9-11h-7z',
    segmento: 'M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    relacion: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
    canales: 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
    actividades: 'M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3a2 2 0 112 0v2M9 5a2 2 0 112 0v2',
    recursos: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
    alianza: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197',
};
</script>

<template>
    <AppLayout title="Etapa 8">
        <template #header>
            <div class="bg-gradient-to-r from-cyan-600 to-blue-500 py-8">
                <div class="max-w-4xl mx-auto px-4">
                    <nav class="flex items-center space-x-2 text-cyan-100 mb-4">
                        <Link class="hover:text-white transition-colors" :href="route('projects.stages.show', proyectoactual.id)">
                            {{ proyectoactual.name }}
                        </Link>
                        <span>/</span>
                        <span class="text-white font-medium">Etapa 8</span>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            8
                        </div>
                        <div>
                            <h2 class="font-bold text-3xl text-white leading-tight">
                                Planificación financiera e inversiones
                            </h2>
                            <p class="text-cyan-100 mt-1">Gestiona todos los recursos, costos y proyecciones financieras de tu proyecto</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-cyan-50 py-12">
            <div class="max-w-6xl mx-auto px-4">
                <!-- Panel educativo -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-l-4 border-cyan-500">
                    <div class="flex items-center mb-4">
                        <svg class="w-7 h-7 mr-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-xl font-bold text-cyan-800">¿Por qué es importante la planificación financiera?</h3>
                    </div>
                    <p class="text-gray-700 mb-2">Una buena planificación financiera te permite anticipar necesidades, optimizar recursos y asegurar la viabilidad económica de tu proyecto.</p>
                    <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">
                        <li>Controla los costos y gastos de tu negocio.</li>
                        <li>Identifica los recursos clave y su impacto financiero.</li>
                        <li>Proyecta ingresos y evalúa la rentabilidad.</li>
                    </ul>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Tarjeta Receta estándar -->
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.receta" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Receta estándar</h3>
                        <Link :href="route('recetaestandar.index', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <!-- Tarjeta Material Insumo -->
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.insumo" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Material Insumo</h3>
                        <Link :href="route('materialinsumo.index', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.variable" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Costo Variable</h3>
                        <Link :href="route('costovariable.index', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.empleados" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Empleados</h3>
                        <Link :href="route('empleados.index', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.fijo" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Costos fijos</h3>
                        <Link :href="route('costos-fijos.index', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.pagos" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Pagos Mensuales</h3>
                        <Link :href="route('pagos-mensuales.index', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.equilibrio" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Punto de Equilibrio Dinero</h3>
                        <Link :href="route('punto-equilibrio.index', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.propuesta" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Propuesta de valor</h3>
                        <Link :href="route('index.propuestavalor', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.segmento" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Segmento de clientes</h3>
                        <Link :href="route('index.segmentoclientes', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.relacion" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Relación con los clientes</h3>
                        <Link :href="route('index.relacionclientes', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.canales" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Canales</h3>
                        <Link :href="route('index.canales', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.actividades" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Actividades clave</h3>
                        <Link :href="route('index.actividadesclave', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.recursos" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Recursos clave</h3>
                        <Link :href="route('index.recursosclave', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                    <div class="modern-card-cyan">
                        <div class="icon-card-cyan">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons.alianza" />
                            </svg>
                        </div>
                        <h3 class="card-title-cyan">Alianza clave</h3>
                        <Link :href="route('index.alianzasclave', { id: props.proyecto_id })">
                            <button class="modern-btn-cyan">Llenar</button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.modern-card-cyan {
    background: linear-gradient(135deg, #e0f2fe 0%, #f0fdfa 100%);
    border-left: 4px solid #06b6d4;
    border-radius: 1rem;
    box-shadow: 0 2px 8px 0 rgba(6,182,212,0.08);
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: box-shadow 0.2s, transform 0.2s;
    min-height: 220px;
}
.modern-card-cyan:hover {
    box-shadow: 0 8px 24px 0 rgba(6,182,212,0.18);
    transform: translateY(-4px) scale(1.03);
}
.card-title-cyan {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0891b2;
    margin-bottom: 1.5rem;
    text-align: center;
}
.modern-btn-cyan {
    background: linear-gradient(90deg, #06b6d4 0%, #0ea5e9 100%);
    color: #fff;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    box-shadow: 0 2px 8px 0 rgba(6,182,212,0.10);
    border: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
    margin-top: 1.5rem;
}
.modern-btn-cyan:hover {
    background: linear-gradient(90deg, #0ea5e9 0%, #06b6d4 100%);
    transform: scale(1.04);
}
.icon-card-cyan {
    background: linear-gradient(135deg, #bae6fd 0%, #a7f3d0 100%);
    border-radius: 0.75rem;
    width: 3.5rem;
    height: 3.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px 0 rgba(6,182,212,0.10);
}
@media (max-width: 768px) {
    .grid-cols-3 {
        grid-template-columns: 1fr !important;
    }
    .modern-card-cyan {
        min-height: 180px;
        padding: 1.25rem 1rem;
    }
}
</style>
