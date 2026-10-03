<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ pessoa: { type: Object, required: true } });
const form = useForm({});

function destroy() {
    if (window.confirm(`Deseja excluir ${props.pessoa.nome}?`)) {
        form.delete(route('pessoas.destroy', props.pessoa.id));
    }
}
</script>

<template>
    <Head :title="pessoa.nome" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Detalhes da pessoa</h2>
                <Link :href="route('pessoas.index')" class="text-sm text-gray-600 hover:text-gray-900">Voltar à lista</Link>
            </div>
        </template>
        <div class="py-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg sm:p-8">
                    <dl class="grid gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2"><dt class="text-sm text-gray-500">Nome / Razão social</dt><dd class="mt-1 font-medium text-gray-900">{{ pessoa.nome }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Tipo</dt><dd class="mt-1 text-gray-900">{{ pessoa.tipo === 'física' ? 'Pessoa física' : 'Pessoa jurídica' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">CPF / CNPJ</dt><dd class="mt-1 font-mono text-gray-900">{{ pessoa.cpf }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Telefone</dt><dd class="mt-1 text-gray-900">{{ pessoa.telefone || 'Não informado' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">E-mail</dt><dd class="mt-1 text-gray-900">{{ pessoa.email }}</dd></div>
                    </dl>
                    <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 pt-5">
                        <Link :href="route('pessoas.edit', pessoa.id)" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Editar</Link>
                        <button type="button" :disabled="form.processing" class="rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50" @click="destroy">Excluir</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>