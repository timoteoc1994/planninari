<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Bienvenido a Planninari" />
    <div class="bg-gradient-to-br from-blue-100 via-white to-blue-300 min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-2xl px-6 py-12 bg-white rounded-lg shadow-lg mt-12">
            <div class="flex justify-center mb-6">
                <img src="https://cdn-icons-png.flaticon.com/512/2920/2920236.png" alt="Planes de negocio" class="h-24 w-24 rounded-full shadow" />
            </div>
            <h1 class="text-3xl font-bold text-blue-700 mb-4 text-center">Bienvenido a Planninari</h1>
            <p class="text-gray-700 text-center mb-8">
                Tu plataforma para crear, administrar y visualizar planes de negocio de manera fácil y profesional.<br>
                ¡Comienza tu emprendimiento hoy!
            </p>
            <div class="flex flex-col items-center gap-4">
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition"
                >
                    Iniciar sesión
                </Link>
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded transition"
                >
                    Registrarse
                </Link>
            </div>
        </div>
        <footer class="py-8 text-center text-sm text-gray-500">
            By Ninari
        </footer>
    </div>
</template>
