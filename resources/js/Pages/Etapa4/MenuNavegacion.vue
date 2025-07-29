<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  proyecto_id: { type: [Number, String], required: true },
  proyectoactual: { type: Object, required: true },
  actual: { type: String, required: false, default: '' }, // nombre de la ruta actual (ej: 'index.propuestavalor')
})

const secciones = [
  {
    titulo: 'Propuesta de Valor',
    ruta: 'index.propuestavalor',
    color: 'blue',
    icono: 'M13 10V3L4 14h7v7l9-11h-7z',
  },
  {
    titulo: 'Segmento de Clientes',
    ruta: 'index.segmentoclientes',
    color: 'green',
    icono: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
  },
  {
    titulo: 'Relación con Clientes',
    ruta: 'index.relacionclientes',
    color: 'pink',
    icono: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
  },
  {
    titulo: 'Canales',
    ruta: 'index.canales',
    color: 'purple',
    icono: 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
  },
  {
    titulo: 'Actividades Clave',
    ruta: 'index.actividadesclave',
    color: 'indigo',
    icono: 'M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V3a2 2 0 112 0v2M9 5a2 2 0 112 0v2',
  },
  {
    titulo: 'Recursos Clave',
    ruta: 'index.recursosclave',
    color: 'emerald',
    icono: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
  },
  {
    titulo: 'Alianzas Clave',
    ruta: 'index.alianzasclave',
    color: 'teal',
    icono: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
  },
]

const getColorClasses = (color, active) => {
  const base = {
    blue: 'text-blue-700 bg-blue-50 border-blue-500',
    green: 'text-green-700 bg-green-50 border-green-500',
    pink: 'text-pink-700 bg-pink-50 border-pink-500',
    purple: 'text-purple-700 bg-purple-50 border-purple-500',
    indigo: 'text-indigo-700 bg-indigo-50 border-indigo-500',
    emerald: 'text-emerald-700 bg-emerald-50 border-emerald-500',
    teal: 'text-teal-700 bg-teal-50 border-teal-500',
  }
  return active
    ? `${base[color]} font-bold shadow-md` 
    : `${base[color]} hover:bg-opacity-80 hover:shadow`;
}
</script>

<template>
  <nav class="mb-8">
    <div class="flex flex-wrap gap-2 justify-center">
      <Link
        v-for="seccion in secciones"
        :key="seccion.ruta"
        :href="route(seccion.ruta, { id: proyecto_id })"
        :class="[
          'flex items-center gap-2 px-4 py-2 rounded-lg border-2 transition-all duration-200',
          getColorClasses(seccion.color, actual === seccion.ruta)
        ]"
      >
        <svg :class="['w-5 h-5', actual === seccion.ruta ? 'opacity-100' : 'opacity-60']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="seccion.icono" />
        </svg>
        <span>{{ seccion.titulo }}</span>
      </Link>
    </div>
  </nav>
</template>

<style scoped>
nav {
  animation: fadeIn 0.5s;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
