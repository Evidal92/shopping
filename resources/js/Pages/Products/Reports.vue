<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SelectInput from '@/Components/SelectInput.vue';
import DataTable from 'datatables.net-vue3';
import 'datatables.net-dt/css/jquery.dataTables.css';
import ButtonsHtml5 from 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';
import 'datatables.net-responsive-dt';
import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';
import JsZip from 'jszip';
import pdfmake from 'pdfmake/build/pdfmake';
import * as pdfFonts from 'pdfmake/build/vfs_fonts';
pdfmake.vfs = pdfFonts.pdfMake ? pdfFonts.pdfMake.vfs : pdfMake.vfs;
window.JSZip = JsZip;
DataTable.use(ButtonsHtml5);

import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import vueTailwindPaginationUmd from '@ocrv/vue-tailwind-pagination';

const columns1 = ref([]);
const columns2 = ref([]);
const buttons1 = ref([]);
const buttons2 = ref([]);
const report = ref('1');
const types = ref([{'id':'1','name':'Emplo'},{'id':'2','name':'Depar'}]);

const props = defineProps({
    products: {type:Object},
    categories: {type:Object}
});

columns1.value = [{data:null, render:function(data,type,row,meta)
    {return(meta.row + 1)}},
    {data:'name'},
    {data:'name'},
    {data:'name'},
    {data:'name'},
]

columns2.value = [{data:null, render:function(data,type,row,meta)
    {return(meta.row + 1)}},
    {data:'name'},
]

buttons1.value = [
    {
        title:'CAtegorias',extend:'excelHtml5',
        text:'<i clas="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Categorias',extend:'pdfHtml5',
        text:'<i clas="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
</script>

<template>
    <Head title="Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporte de Productos</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="px-6 px-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <InputLabel for="rep" Value="Reportes: "></InputLabel>
                    <SelectInput id="rep" class="mt-1 block w-e/4" v-model="report" :option="types"></SelectInput>
                </div>
                <div v-if="report == '1'" class="px-6 px-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable :data="empl" :columns="columns1" class="w-full border border-gray-400"
                    :options="{responsive:true, autoWidth:false,dom:Bfrtip,buttons:buttons1}">
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Descripción</th>
                            <th class="px-2 py-2">Slug</th>
                            <th class="px-2 py-2">Sku</th>
                            <th class="px-2 py-2">Precio</th>
                            <th class="px-2 py-2">Cantidad</th>
                            <th class="px-2 py-2">Categoria</th>
                        </tr>
                    </DataTable>
                </div>
                <div v-else class="px-6 px-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                </div>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <vueTailwindPaginationUmd
                    :current="products.currentPage" :total="products.total"
                    :per-page="products.perPage"
                    @page-changed="$event => onPageClick($event)">
                </vueTailwindPaginationUmd>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
