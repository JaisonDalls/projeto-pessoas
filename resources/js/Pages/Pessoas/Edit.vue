<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PessoaForm from '@/Components/PessoaForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ pessoa: { type: Object, required: true } });
</script>

<template>
    <Head :title="`Editar ${pessoa.nome}`" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-semibold leading-tight text-gray-800">Editar pessoa</h2></template>
        <div class="py-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg sm:p-8"><PessoaForm :pessoa="pessoa" /></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>