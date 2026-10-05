<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { vMaska } from 'maska/vue';

const props = defineProps({ pessoa: { type: Object, default: null } });

const form = useForm({
    nome: props.pessoa?.nome ?? '',
    cpf: props.pessoa?.cpf ?? '',
    tipo: props.pessoa?.tipo ?? 'física',
    telefone: props.pessoa?.telefone ?? '',
    email: props.pessoa?.email ?? '',
});

function formatName(value) {
    return value
        .toLocaleLowerCase('pt-BR')
        .replace(/(^|[^\p{L}\p{M}])(\p{L})/gu, (_, separator, letter) => (
            `${separator}${letter.toLocaleUpperCase('pt-BR')}`
        ));
}

function capitalizeName(event) {
    if (event.isComposing) {
        return;
    }

    const input = event.target;
    const cursorPosition = input.selectionStart;
    const rawValue = input.value;
    const formattedValue = formatName(rawValue);

    form.nome = formattedValue;
    input.value = formattedValue;

    if (cursorPosition !== null) {
        const formattedCursorPosition = formatName(rawValue.slice(0, cursorPosition)).length;

        input.setSelectionRange(formattedCursorPosition, formattedCursorPosition);
    }
}

function submit() {
    if (props.pessoa) {
        form.put(route('pessoas.update', props.pessoa.id));
        return;
    }

    form.post(route('pessoas.store'));
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <span class="block text-sm font-medium text-gray-700">Tipo de pessoa</span>
            <div class="mt-2 flex gap-6">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input v-model="form.tipo" type="radio" value="física" class="border-gray-300 text-indigo-600 focus:ring-indigo-500" /> Física</label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input v-model="form.tipo" type="radio" value="jurídica" class="border-gray-300 text-indigo-600 focus:ring-indigo-500" /> Jurídica</label>
            </div>
            <p v-if="form.errors.tipo" class="mt-1 text-sm text-red-600">{{ form.errors.tipo }}</p>
        </div>

        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome / Razão social</label>
            <input id="nome" v-model="form.nome" @input="capitalizeName" type="text" required maxlength="255" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            <p v-if="form.errors.nome" class="mt-1 text-sm text-red-600">{{ form.errors.nome }}</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="cpf" class="block text-sm font-medium text-gray-700">{{ form.tipo === 'física' ? 'CPF' : 'CNPJ' }}</label>
                <input id="cpf" v-model="form.cpf" v-maska :data-maska="form.tipo === 'física' ? '###.###.###-##' : '##.###.###/####-##'" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p v-if="form.errors.cpf" class="mt-1 text-sm text-red-600">{{ form.errors.cpf }}</p>
            </div>
            <div>
                <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                <input id="telefone" v-model="form.telefone" v-maska data-maska="['(##) ####-####', '(##) #####-####']" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p v-if="form.errors.telefone" class="mt-1 text-sm text-red-600">{{ form.errors.telefone }}</p>
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
            <input id="email" v-model="form.email" type="email" required maxlength="255" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
            <Link :href="route('pessoas.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancelar</Link>
            <button type="submit" :disabled="form.processing" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                {{ form.processing ? 'Salvando...' : (pessoa ? 'Salvar alterações' : 'Cadastrar pessoa') }}
            </button>
        </div>
    </form>
</template>