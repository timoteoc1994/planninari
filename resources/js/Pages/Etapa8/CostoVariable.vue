<!-- resources/js/Pages/Etapa8/CostoVariable.vue -->
<template>
    <AppLayout title="Costo Variable">
        <template #header>
            <div class="text-center">
                <h1 class="text-2xl font-bold">PROGRAMA ACTIVATE Y CRECE</h1>
                <h2 class="text-lg text-gray-700">COSTOS VARIABLES (Estimación de ventas)</h2>
            </div>
        </template>

        <div class="py-8 max-w-6xl mx-auto space-y-6">
            <div class="flex justify-end">
                <button @click="openCreateModal" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    + Nuevo Registro
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse bg-white rounded shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Producto</th>
                            <th class="px-4 py-2">Unidad</th>
                            <th class="px-4 py-2">Unid. mes</th>
                            <th class="px-4 py-2">Costo Unit.</th>
                            <th class="px-4 py-2">Venta Unit.</th>
                            <th class="px-4 py-2">Prod/Comp Total</th>
                            <th class="px-4 py-2">Venta Total</th>
                            <th class="px-4 py-2">%Costo Venta</th>
                            <th class="px-4 py-2">%Margen Contr.</th>
                            <th class="px-4 py-2">%Participación</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in registros" :key="r.id" class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ r.ref }} – {{ recetaNombre[r.ref] }}</td>
                            <td class="px-4 py-2">{{ r.unidad || recetaUnidad[r.ref] }}</td>
                            <td class="px-4 py-2">{{ r.unidades_mes }}</td>
                            <td class="px-4 py-2">${{ Number(r.costo_unitario).toFixed(2) }}</td>
                            <td class="px-4 py-2">${{ Number(r.venta_unitario).toFixed(2) }}</td>
                            <td class="px-4 py-2">${{ Number(r.total_costo).toFixed(2) }}</td>
                            <td class="px-4 py-2">${{ Number(r.total_venta).toFixed(2) }}</td>
                            <td class="px-4 py-2">{{ r.pct_costo_venta }}%</td>
                            <td class="px-4 py-2">{{ r.pct_margen }}%</td>
                            <td class="px-4 py-2">{{ r.pct_participacion }}%</td>
                            <td class="px-4 py-2">
                                <div class="flex space-x-2">
                                    <button @click="openEditModal(r)"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
                                        Editar
                                    </button>
                                    <button @click="deleteRegistro(r)"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                        Eliminar
                                    </button>
                                </div>
                            </td>

                        </tr>
                        <tr class="font-semibold bg-gray-50 border-t">
                            <td>TOTALES</td>
                            <td></td>
                            <td>{{ totales.unidades }}</td>
                            <td></td>
                            <td></td>
                            <td>${{ Number(totales.prodTotal).toFixed(2) }}</td>
                            <td>${{ Number(totales.ventaTotal).toFixed(2) }}</td>
                            <td>{{ totales.pctCostoVenta }}%</td>
                            <td>{{ totales.pctMargen }}%</td>
                            <td>100%</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- debajo de la tabla de registros, antes del modal -->
        <div class="max-w-6xl mx-auto py-10">
            <table class="w-full table-auto border-collapse bg-white rounded shadow">
                <tbody>
                    <tr>
                        <td class="px-4 py-2 font-semibold">
                            Margen de Contribución = (Venta Total − Costo Total)
                        </td>
                        <td class="px-4 py-2 font-semibold">
                            ${{ Number(totales.ventaTotal - totales.prodTotal).toFixed(2) }}
                        </td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-4 py-2">
                            % de Margen de Contribución = (Margen de Contribución / Venta Total) × 100
                        </td>
                        <td class="px-4 py-2 font-semibold">
                            {{ totales.pctMargen }}%
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">
                            % de Costo Variable = (Costo Total / Venta Total) × 100
                        </td>
                        <td class="px-4 py-2 font-semibold">
                            {{ totales.pctCostoVenta }}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Modal Crear/Editar -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closeModal">
            <div class="bg-white p-6 rounded shadow w-full max-w-lg">
                <h3 class="text-lg font-semibold mb-4 text-center">
                    {{ isEditing ? 'Editar Registro' : 'Nuevo Registro' }}
                </h3>
                <form @submit.prevent="isEditing ? submitEdit() : submitCreate()" class="space-y-4">
                    <div>
                        <label class="block text-sm">REF *</label>
                        <select v-model="form.ref" @change="updateDerivedFields" required
                            class="w-full border rounded p-2">
                            <option disabled value="">— Selecciona —</option>
                            <option v-for="r in recetas" :key="r.ref" :value="r.ref">
                                {{ r.ref }} – {{ r.nombre_producto }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm">Unidad</label>
                        <input v-model="form.unidad" placeholder="Ej: Servicio, 1" class="w-full border rounded p-2" />
                    </div>

                    <div>
                        <label class="block text-sm">Unid. mes *</label>
                        <input v-model="form.unidades_mes" type="number" required class="w-full border rounded p-2" />
                    </div>

                    <div>
                        <label class="block text-sm">Costo Unit.</label>
                        <input :value="Number(costoInsumo[form.ref] ?? 0).toFixed(2)" disabled
                            class="w-full border rounded p-2 bg-gray-100" />
                    </div>

                    <div>
                        <label class="block text-sm">Venta Unit.</label>
                        <input :value="Number(recetaPrecio[form.ref] ?? 0).toFixed(2)" disabled
                            class="w-full border rounded p-2 bg-gray-100" />
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="closeModal"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{
                                form.processing
                                    ? isEditing
                                        ? 'Actualizando...'
                                        : 'Guardando...'
                                    : isEditing
                                        ? 'Actualizar'
                                        : 'Guardar'
                            }}
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
    proyecto_id: { type: [Number, String], required: true },
    recetas: { type: Array, required: true },
    registros: { type: Array, required: true },
    costosInsumos: { type: Object, default: () => ({}) }  // <-- por si viene undefined
})

// Helpers rápidos
const proyectoId = Number(props.proyecto_id)
const recetaPrecio = Object.fromEntries(
    props.recetas.map(r => [r.ref, Number(r.precio_venta) || 0])
)
const recetaNombre = Object.fromEntries(
    props.recetas.map(r => [r.ref, r.nombre_producto])
)
const recetaUnidad = Object.fromEntries(
    props.recetas.map(r => [r.ref, r.presentacion])
)
const costoInsumo = props.costosInsumos

// Cálculo global de totales
const totales = computed(() => {
    const sumUn = props.registros.reduce((s, r) => s + r.unidades_mes, 0)
    const sumCost = props.registros.reduce((s, r) => s + parseFloat(r.total_costo), 0)
    const sumVent = props.registros.reduce((s, r) => s + parseFloat(r.total_venta), 0)
    const pctCost = sumVent ? Math.round((sumCost / sumVent) * 100) : 0
    return {
        unidades: sumUn,
        prodTotal: sumCost,
        ventaTotal: sumVent,
        pctCostoVenta: pctCost,
        pctMargen: 100 - pctCost,
    }
})

// Form y modal
const form = useForm({
    ref: '',
    unidad: '',
    unidades_mes: '',
})
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)

function openCreateModal() {
    form.reset()
    form.clearErrors()
    isEditing.value = false
    showModal.value = true
}

function openEditModal(r) {
    editingId.value = r.id
    form.ref = r.ref
    form.unidad = r.unidad
    form.unidades_mes = r.unidades_mes
    form.clearErrors()
    isEditing.value = true
    showModal.value = true
}

function closeModal() {
    showModal.value = false
}

// Al cambiar la ref, se recalculan valores de costo y venta
function updateDerivedFields() {
    // nada más dejamos que el template lea de recetaPrecio y costoInsumo
}

function submitCreate() {
    const costo = costoInsumo[form.ref] ?? 0
    const venta = recetaPrecio[form.ref] ?? 0
    const totalC = form.unidades_mes * costo
    const totalV = form.unidades_mes * venta
    const pctCost = totalV ? Math.round((totalC / totalV) * 100) : 0
    const pctMarg = 100 - pctCost

    form.post(
        route('costovariable.store', { id: proyectoId }),
        {
            data: {
                ...form,
                costo_unitario: costo,
                venta_unitario: venta,
                total_costo: totalC,
                total_venta: totalV,
                pct_costo_venta: pctCost,
                pct_margen: pctMarg,
                pct_participacion: 0,
            },
            onSuccess: closeModal,
            onFinish: () => router.reload({ only: ['registros'] }),
        }
    )
}

function submitEdit() {
    const costo = costoInsumo[form.ref] ?? 0
    const venta = recetaPrecio[form.ref] ?? 0
    const totalC = form.unidades_mes * costo
    const totalV = form.unidades_mes * venta
    const pctCost = totalV ? Math.round((totalC / totalV) * 100) : 0
    const pctMarg = 100 - pctCost

    form.put(
        route('costovariable.update', { id: proyectoId, registro: editingId.value }),
        {
            data: {
                ...form,
                costo_unitario: costo,
                venta_unitario: venta,
                total_costo: totalC,
                total_venta: totalV,
                pct_costo_venta: pctCost,
                pct_margen: pctMarg,
                pct_participacion: 0,
            },
            onSuccess: closeModal,
            onFinish: () => router.reload({ only: ['registros'] }),
        }
    )
}

function deleteRegistro(r) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: `"${r.ref}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            router.delete(
                route('costovariable.destroy', { id: proyectoId, registro: r.id }),
                { onSuccess: () => Swal.fire('Eliminado', '', 'success') }
            )
        }
    })
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && showModal.value) {
        closeModal()
    }
})
</script>

<style scoped>
th,
td {
    text-align: center;
}
</style>
