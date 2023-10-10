<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head,Link,useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    categories: {type:Object}
});

const form = useForm({
    id: ''
});

const deleteCategory = (id,name) =>{
    const alert = Swal.mixin({
        buttonsStyling:true
    });
    alert.fire({
        title: 'Estas seguro de Eliminar '+name+' ?',
        icon: 'question', showCancelButton:true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Si',
        cancelButtonText: '<i class="fa-solid fa-ban"></i> Cancelar',
    }).then((result) =>{
        if(result.isConfirmed) {
            Swal.fire(
                '¡Eliminado!',
                'Su archivo ha sido eliminado.',
                'success'
            ),
            form.delete(route('categories.destroy',id));
        }
    })
}
</script>

<template>
    <Head title="Categories" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categorias</h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <Link :href="route('categories.create')" :class="'px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs'" >
                        <i class="fa-solid fa-plus-circle"></i> Agregar
                    </Link>
                </div>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-4">#</th>
                            <th class="px-4 py-4">Categoria</th>
                            <th class="px-4 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cat, i in categories" :key="cat.id">
                            <td class="border border-gray-400 px-4 py-4">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-4 py-4">{{ (cat.name) }}</td>
                            <td class="border border-gray-400 px-4 py-4">
                                <Link :href="route('categories.edit',cat.id)" :class="'px-4 py-2 bg-yellow-800 text-white border rounded-md font-semibold text-xs'" >
                                    <i class="fa-solid fa-edit"></i>
                                </Link>
                                <DangerButton v-on:click="$event => deleteCategory(cat.id,cat.name)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
