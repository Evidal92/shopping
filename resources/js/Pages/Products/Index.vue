<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import WarningButton from '@/Components/WarningButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head,useForm } from '@inertiajs/vue3';
import { nextTick,ref } from 'vue';
import Swal from 'sweetalert2';
import vueTailwindPaginationUmd from '@ocrv/vue-tailwind-pagination';

const nameInput = ref(null);
const modal = ref(false);
const title = ref('');
const operation = ref(1);
const id = ref('');

const props = defineProps({
    products: {type:Object},
    categories: {type:Object}
});

const form = useForm({
    name: '', description: '', slug: '', sku: '', price: '', quantity: '', category_id: ''
});

const formPage = useForm({});

const onPageClick = (event)=>{
    formPage.get(route('products.index',{page:event}))
}

const openModal = (op, name, description, slug, sku, price, quantity, category, product) => {
    modal.value = true;
    nextTick( () => nameInput.value.focus());
    operation.value = op;
    id.value = product;

    if(op == 1){
        title.value = 'Crear Producto';
    }
    else{
        title.value = 'Editar Producto'+product+category;
        form.name = name;
        form.description = description;
        form.slug = slug;
        form.sku = sku;
        form.price = price;
        form.quantity = quantity;
        form.category_id = category;
    }
}

const closeModal = () =>{
    modal.value = false;
    form.reset();
}

const save = () =>{
    if(operation.value == 1){
        form.post(route('products.store'),{
            onSuccess: () => {ok('Producto Creado')}
        });
    }
    else{
        form.put(route('products.update',id.value),{
            onSuccess: () => {ok('Producto Actualizado')}
        });
    }
}

const ok = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({
        title:msj,
        icon:'success'
    });
}

const deleteProduct = (id,name) =>{
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
                'El producto ha sido eliminado.',
                'success'
            ),
            form.delete(route('products.destroy',id),{
                onSuccess: () => {ok('Producto Eliminado')}
            });
        }
    })
}
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Productos</h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <PrimaryButton @click="$event => openModal(1)">
                        <i class="fa-solid fa-plus-circle"></i> Agregar Producto
                    </PrimaryButton>
                </div>
            </div>
            <div class="bg-white grid v-screen place-items-center overflow-x-auto">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Descripción</th>
                            <th class="px-2 py-2">Slug</th>
                            <th class="px-2 py-2">Sku</th>
                            <th class="px-2 py-2">Precio</th>
                            <th class="px-2 py-2">Cantidad</th>
                            <th class="px-2 py-2">Categoria</th>
                            <th class="px-2 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="pro, i in products.data" :key="pro.id">
                            <td class="border border-gray-400 px-2 py-2">{{ (i+1) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ (pro.name) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ (pro.description) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ (pro.slug) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ (pro.sku) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ (pro.price) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ (pro.quantity) }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ (pro.category) }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton @click="$event => openModal(2,pro.name,pro.description,pro.slug,pro.sku,pro.price,pro.quantity,pro.category_id,pro.id)">
                                    <i class="fa-solid fa-edit"></i> {{ pro.id }}
                                </WarningButton>
                                <DangerButton v-on:click="$event => deleteProduct(pro.id,pro.name)">
                                    <i class="fa-solid fa-trash"></i>{{ pro.id }}
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <vueTailwindPaginationUmd
                    :current="products.currentPage" :total="products.total"
                    :per-page="products.perPage"
                    @page-changed="$event => onPageClick($event)">
                </vueTailwindPaginationUmd>
            </div>
        </div>
        <modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font.medium text-gray-900">{{ title }}</h2>
            <div class="p-3">
                <InputLabel for="name" value="Nombre: "></InputLabel>
                <TextInput type="text" id="name" ref="NameInput" v-model="form.name" class="mt-1 block w-3/4"></TextInput>
                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="description" value="Descripción: "></InputLabel>
                <TextInput type="text" id="description" v-model="form.description" class="mt-1 block w-3/4"></TextInput>
                <InputError :message="form.errors.description" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="slug" value="Slug: "></InputLabel>
                <TextInput type="text" id="slug" v-model="form.slug" class="mt-1 block w-3/4"></TextInput>
                <InputError :message="form.errors.slug" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="sku" value="Sku: "></InputLabel>
                <TextInput type="text" id="sku" v-model="form.sku" class="mt-1 block w-3/4"></TextInput>
                <InputError :message="form.errors.sku" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="price" value="Precio: "></InputLabel>
                <TextInput type="number" id="price" step="0.01" v-model="form.price" class="mt-1 block w-3/4"></TextInput>
                <InputError :message="form.errors.price" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="quantity" value="Cantidad: "></InputLabel>
                <TextInput type="text" id="quantity" v-model="form.quantity" class="mt-1 block w-3/4"></TextInput>
                <InputError :message="form.errors.quantity" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="category_id" value="Categoria: "></InputLabel>
                <SelectInput type="text" id="category_id" v-model="form.category_id" :options="categories" class="mt-1 block w-3/4"></SelectInput>
                <InputError :message="form.errors.category_id" class="mt-2"></InputError>
            </div>
            <div class="p-3 mt-6">
                <PrimaryButton :disabled="form.processing" @click="save">
                    <i class="fa-solid fa-save"></i> Guardar
                </PrimaryButton>
            </div>
            <div class="p-3 mt-6 flex justify-end">
                <SecondaryButton class="ml-3" :disabled="form.processing" @click="closeModal">Cancelar</SecondaryButton>
            </div>
        </modal>
    </AuthenticatedLayout>
</template>
