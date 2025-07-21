<!-- resources/js/Pages/Menu/RecetaEstandar.vue -->
<template>
    <AppLayout title="Receta Estándar">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">
                Receta Estándar – Productos Desarrollados
            </h2>
        </template>

        <div class="py-8 max-w-4xl mx-auto space-y-6">
            <!-- Barra de acciones: buscador + nuevo -->
            <div class="flex items-center gap-4">
                <input v-model="search" type="text" placeholder="Buscar..."
                    class="border w-full rounded p-2 flex-grow focus:outline-none focus:ring" />
                <button @click="openCreateModal"
                    class="px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700 transition">
                    Crear
                </button>
            </div>


            <!-- Tabla de Productos -->
            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">REF.</th>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Detalle</th>
                            <th class="px-4 py-2 text-left">Presentación</th>
                            <th class="px-4 py-2 text-left">Precio Venta</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in filteredProductos" :key="p.id" class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ p.ref }}</td>
                            <td class="px-4 py-2">{{ p.nombre_producto }}</td>
                            <td class="px-4 py-2">{{ p.detalle }}</td>
                            <td class="px-4 py-2">{{ p.presentacion }}</td>
                            <td class="px-4 py-2">
                                ${{ parseFloat(p.precio_venta || 0).toFixed(2) }}
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <button @click="openEditModal(p)"
                                    class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm">
                                    Editar
                                </button>
                                <button @click="deleteProduct(p)"
                                    class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!filteredProductos.length">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                No se encontró ningún producto.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Crear -->
        <div v-if="showCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeCreateModal">
            <div class="bg-white p-6 rounded shadow w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4">Nuevo Producto</h3>
                <form @submit.prevent="submitCreate" class="space-y-4">
                    <div>
                        <label class="block text-sm">Nombre *</label>
                        <input v-model="createForm.nombre_producto" required class="w-full border rounded p-2" />
                        <p v-if="createForm.errors.nombre_producto" class="text-red-600 text-sm">
                            {{ createForm.errors.nombre_producto }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm">Presentación</label>
                        <input v-model="createForm.presentacion" class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm">Detalle</label>
                        <textarea v-model="createForm.detalle" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm">Precio Venta *</label>
                        <input v-model="createForm.precio_venta" type="number" step="0.01" required
                            class="w-full border rounded p-2" />
                        <p v-if="createForm.errors.precio_venta" class="text-red-600 text-sm">
                            {{ createForm.errors.precio_venta }}
                        </p>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="closeCreateModal"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="createForm.processing"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            {{ createForm.processing ? 'Guardando...' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeEditModal">
            <div class="bg-white p-6 rounded shadow w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4">Editar Producto</h3>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm">Nombre *</label>
                        <input v-model="editForm.nombre_producto" required class="w-full border rounded p-2" />
                        <p v-if="editForm.errors.nombre_producto" class="text-red-600 text-sm">
                            {{ editForm.errors.nombre_producto }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm">Presentación</label>
                        <input v-model="editForm.presentacion" class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm">Detalle</label>
                        <textarea v-model="editForm.detalle" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm">Precio Venta *</label>
                        <input v-model="editForm.precio_venta" type="number" step="0.01" required
                            class="w-full border rounded p-2" />
                        <p v-if="editForm.errors.precio_venta" class="text-red-600 text-sm">
                            {{ editForm.errors.precio_venta }}
                        </p>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="closeEditModal"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="editForm.processing"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            {{ editForm.processing ? 'Actualizando...' : 'Actualizar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Swal from 'sweetalert2'

// Props
const { proyecto_id, productos } = defineProps({
    proyecto_id: Number,
    productos: Array,
})

// Buscador
const search = ref('')
const filteredProductos = computed(() => {
    const term = search.value.toLowerCase()
    return productos.filter(p =>
        p.ref.toLowerCase().includes(term) ||
        p.nombre_producto.toLowerCase().includes(term)
    )
})

// Crear
const showCreateModal = ref(false)
const createForm = useForm({
    nombre_producto: '',
    detalle: '',
    presentacion: '',
    precio_venta: '',
})
function openCreateModal() {
    createForm.reset()
    createForm.clearErrors()
    showCreateModal.value = true
}
function closeCreateModal() {
    showCreateModal.value = false
    createForm.reset()
    createForm.clearErrors()
}
function submitCreate() {
    createForm.post(route('recetaestandar.store', { id: proyecto_id }), {
        onSuccess: () => {
            closeCreateModal()
            router.reload({ only: ['productos'] })
        }
    })
}

// Editar
const showEditModal = ref(false)
const selectedProduct = ref(null)
const editForm = useForm({
    nombre_producto: '',
    detalle: '',
    presentacion: '',
    precio_venta: '',
})
function openEditModal(product) {
    selectedProduct.value = product
    editForm.reset()
    editForm.clearErrors()
    editForm.nombre_producto = product.nombre_producto
    editForm.detalle = product.detalle
    editForm.presentacion = product.presentacion
    editForm.precio_venta = product.precio_venta
    showEditModal.value = true
}
function closeEditModal() {
    showEditModal.value = false
    selectedProduct.value = null
    editForm.reset()
    editForm.clearErrors()
}
function submitEdit() {
    editForm.put(
        route('recetaestandar.update', {
            id: proyecto_id,
            recetaestandar: selectedProduct.value.id
        }),
        {
            onSuccess: () => {
                closeEditModal()
                router.reload({ only: ['productos'] })
            }
        }
    )
}

// Eliminar
function deleteProduct(product) {
    Swal.fire({
        title: '¿Eliminar este producto?',
        text: `"${product.nombre_producto}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            router.delete(
                route('recetaestandar.destroy', {
                    id: proyecto_id,
                    recetaestandar: product.id
                }),
                {
                    onSuccess: () => {
                        Swal.fire('Eliminado', 'El producto fue eliminado.', 'success')
                    }
                }
            )
        }
    })
}

// ESC para cerrar modales
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (showCreateModal.value) closeCreateModal()
        if (showEditModal.value) closeEditModal()
    }
})
</script>
