<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head,useForm } from '@inertiajs/vue3';

const props = defineProps({
    category:{type:Object}
})

const form = useForm({
    name: props.category.name
});
</script>

<template>
    <Head title="EditCategory" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Catergoria</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <form @submit.prevent="$event => form.patch(route('categories.update', category))" class="mt-6 space-y-6 max-w-xl">
                        <InputLabel for="name" value="Categoria"></InputLabel>
                        <TextInput type="text" id="name" class="mt-1 block w-full" v-model="form.name" autofocus required></TextInput>
                        <InputError :message="form.errors.name" class="mt-2"></InputError>
                        <PrimaryButton :disabled="form.processing">
                            <i class="fa-solid fa-save"></i> Guardar
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
