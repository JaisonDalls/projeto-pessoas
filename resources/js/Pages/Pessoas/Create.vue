<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PessoaForm from '@/Components/PessoaForm.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Cadastrar pessoa" />
    <AuthenticatedLayout>
        <template #header><h2 class="text-xl font-semibold leading-tight text-gray-800">Cadastrar pessoa</h2></template>
        <div class="py-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg sm:p-8"><PessoaForm /></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>