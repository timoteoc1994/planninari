<!-- resources/js/Pages/Etapa8/MaterialInsumo.vue -->
<template>
    <AppLayout title="Materiales e Insumos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 text-center">
                Materiales e Insumos – Precio de Compra
            </h2>
        </template>

        <div class="py-8 max-w-4xl mx-auto space-y-6">
            <!-- Buscador + Nuevo -->
            <div class="flex items-center gap-4">
                <input v-model="search" type="text" placeholder="Buscar por REF o ingrediente..."
                    class="border w-full rounded p-2 flex-grow focus:outline-none focus:ring" />
                <button @click="openCreateModal"
                    class="px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700 transition">
                    Crear
                </button>
            </div>

            <!-- Tablas agrupadas por ref -->
            <div v-if="groupedInsumos.length">
                <div v-for="[ref, items] in groupedInsumos" :key="ref" class="space-y-2">
                    <h3 class="text-lg font-semibold text-gray-700 text-center mt-6">
                        Insumos para {{ ref }}
                    </h3>

                    <div class="overflow-x-auto rounded-xl border-2">
                        <table class="w-full bg-white rounded shadow mx-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">Ingrediente</th>
                                    <th class="px-4 py-2">Descripción</th>
                                    <th class="px-4 py-2">Peso (g)</th>
                                    <th class="px-4 py-2">Valor (USD)</th>
                                    <th class="px-4 py-2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="i in items" :key="i.id" class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ i.ingrediente }}</td>
                                    <td class="px-4 py-2">{{ i.descripcion }}</td>
                                    <td class="px-4 py-2">
                                        {{ i.peso_g || '—' }}
                                    </td>
                                    <td class="px-4 py-2">{{ i.valor_usd }}</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <button @click="openEditModal(i)"
                                            class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
                                            Editar
                                        </button>
                                        <button @click="deleteItem(i)"
                                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-t font-semibold bg-gray-200">
                                    <td class="px-4 py-2 text-left">Subtotal:</td>
                                    <td></td>
                                    <td></td>
                                    <td class="px-4 py-2">${{ groupSubtotals[ref].toFixed(2) }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-500">
                No se encontraron insumos.
            </div>
        </div>

        <!-- Modal Crear -->
        <div v-if="showCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeCreateModal">
            <div class="bg-white p-6 rounded shadow w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4 text-center">Nuevo Insumo</h3>
                <form @submit.prevent="submitCreate" class="space-y-4">
                    <div>
                        <label class="block text-sm">REF *</label>
                        <select v-model="form.ref" required class="w-full border rounded p-2">
                            <option disabled value="">— Selecciona —</option>
                            <option v-for="r in props.recetas" :key="r.ref" :value="r.ref">
                                {{ r.ref }} – {{ r.nombre_producto }}
                            </option>
                        </select>
                        <p v-if="form.errors.ref" class="text-red-600 text-sm">
                            {{ form.errors.ref }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm">Ingrediente *</label>
                        <input v-model="form.ingrediente" required class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm">Descripción</label>
                        <textarea v-model="form.descripcion" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm">Peso (g)</label>
                        <input v-model="form.peso_g" type="number" step="0.01" class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm">Valor (USD) *</label>
                        <input v-model="form.valor_usd" type="number" step="0.01" required
                            class="w-full border rounded p-2" />
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="closeCreateModal"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ form.processing ? 'Guardando...' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Editar -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeEditModal">
            <div class="bg-white p-6 rounded shadow w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4 text-center">Editar Insumo</h3>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm">REF *</label>
                        <select v-model="form.ref" required class="w-full border rounded p-2">
                            <option disabled value="">— Selecciona —</option>
                            <option v-for="r in props.recetas" :key="r.ref" :value="r.ref">
                                {{ r.ref }} – {{ r.nombre_producto }}
                            </option>
                        </select>
                        <p v-if="form.errors.ref" class="text-red-600 text-sm">
                            {{ form.errors.ref }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm">Ingrediente *</label>
                        <input v-model="form.ingrediente" required class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm">Descripción</label>
                        <textarea v-model="form.descripcion" class="w-full border rounded p-2" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm">Peso (g)</label>
                        <input v-model="form.peso_g" type="number" step="0.01" class="w-full border rounded p-2" />
                    </div>
                    <div>
                        <label class="block text-sm">Valor (USD) *</label>
                        <input v-model="form.valor_usd" type="number" step="0.01" required
                            class="w-full border rounded p-2" />
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="closeEditModal"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            {{ form.processing ? 'Actualizando...' : 'Actualizar' }}
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

const props = defineProps({
    proyecto_id: Number,
    insumos: Array,
    recetas: Array,
})

const search = ref('')
const filteredInsumos = computed(() => {
    const term = search.value.toLowerCase().trim()
    return props.insumos.filter(i =>
        i.ref.toLowerCase().includes(term) ||
        i.ingrediente.toLowerCase().includes(term)
    )
})

const groupedInsumos = computed(() => {
    const groups = {}
    filteredInsumos.value.forEach(i => {
        if (!groups[i.ref]) groups[i.ref] = []
        groups[i.ref].push(i)
    })
    return Object.entries(groups)
})

const groupSubtotals = computed(() => {
    const subs = {}
    filteredInsumos.value.forEach(i => {
        subs[i.ref] = (subs[i.ref] || 0) + (parseFloat(i.valor_usd) || 0)
    })
    return subs
})

const form = useForm({
    ref: '',
    ingrediente: '',
    descripcion: '',
    peso_g: '',
    valor_usd: '',
})
const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingId = ref(null)

function openCreateModal() {
    form.reset()
    form.clearErrors()
    showCreateModal.value = true
}
function closeCreateModal() {
    showCreateModal.value = false
}

function submitCreate() {
    form.post(
        route('materialinsumo.store', { id: props.proyecto_id }),
        {
            onSuccess: () => {
                closeCreateModal()
                router.reload({ only: ['insumos'] })
            }
        }
    )
}

function openEditModal(item) {
    editingId.value = item.id
    form.ref = item.ref
    form.ingrediente = item.ingrediente
    form.descripcion = item.descripcion
    form.peso_g = item.peso_g
    form.valor_usd = item.valor_usd
    form.clearErrors()
    showEditModal.value = true
}
function closeEditModal() {
    showEditModal.value = false
    form.reset()
    form.clearErrors()
}

function submitEdit() {
    form.put(
        route('materialinsumo.update', {
            id: props.proyecto_id,
            materialinsumo: editingId.value
        }),
        {
            onSuccess: () => {
                closeEditModal()
                router.reload({ only: ['insumos'] })
            }
        }
    )
}

function deleteItem(item) {
    Swal.fire({
        title: '¿Eliminar este insumo?',
        text: `"${item.ingrediente}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            router.delete(
                route('materialinsumo.destroy', {
                    id: props.proyecto_id,
                    materialinsumo: item.id
                }),
                {
                    onSuccess: () => Swal.fire('Eliminado', 'El insumo fue eliminado.', 'success')
                }
            )
        }
    })
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (showCreateModal.value) closeCreateModal()
        if (showEditModal.value) closeEditModal()
    }
})
</script>

<style scoped>
th,
td {
    text-align: center;
}
</style>
