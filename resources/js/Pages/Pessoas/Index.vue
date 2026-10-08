<template>
  <div class="min-h-screen app-theme-page app-theme-text p-6 font-sans">
    <!-- Header da Página -->
    <div class="max-w-7xl mx-auto mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b app-theme-border pb-5">
      <div>
        <h1 class="text-2xl font-bold tracking-tight app-theme-heading flex items-center gap-2">
          <!-- Detalhe com a cor do Laravel -->
          <span class="w-3 h-6 bg-[#FF2D20] rounded-sm inline-block"></span>
          Gerenciamento de Pessoas
        </h1>
        <p class="text-sm text-gray-400 mt-1">Teste de desenvolvimento para candidatura da <Apresenta_me/> </p>
      </div>
      
      <!-- Ação Principal -->
      <div class="flex flex-wrap gap-2">
        <Link :href="route('dashboard')" class="inline-flex items-center justify-center gap-2 rounded-lg border app-theme-border px-4 py-2 text-sm font-medium app-theme-text transition-colors hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FF2D20]">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Dashboard
        </Link>
        <Link :href="route('pessoas.create')" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-[#FF2D20] hover:bg-[#e0241a] rounded-lg transition-colors shadow-lg shadow-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-[#FF2D20]">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Nova Pessoa
        </Link>
      </div>
    </div>

    <!-- Tabela / Container Principal -->
    <div class="max-w-7xl mx-auto app-theme-surface rounded-xl border app-theme-border shadow-xl overflow-hidden">
      <!-- Filtros e Busca -->
      <div class="p-5 border-b app-theme-border flex flex-col sm:flex-row gap-4 justify-between app-theme-raised">
        <div class="relative max-w-xs w-full">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </span>
          <input 
            v-model="filtersForm.search"
            type="text" 
            @keydown.enter.prevent="applyFilters"
            placeholder="Buscar por nome, documento..." 
            class="w-full pl-9 pr-4 py-2 text-sm app-theme-control border app-theme-border rounded-lg placeholder-gray-500 focus:outline-none focus:border-[#41B883] focus:ring-1 focus:ring-[#41B883] transition-colors"
          />
        </div>
        
        <div class="flex items-center gap-2 text-sm app-theme-muted">
          <span>Filtrar:</span>
          <select v-model="filtersForm.tipo" class="app-theme-control border app-theme-border rounded-lg px-3 py-1.5 focus:outline-none focus:border-[#41B883]">
            <option value="">Todos os tipos</option>
            <option value="física">Pessoa Física</option>
            <option value="jurídica">Pessoa Jurídica</option>
          </select>
          <button type="button" class="rounded-lg border app-theme-border px-3 py-1.5 app-theme-text hover:bg-gray-800" @click="applyFilters">Buscar</button>
        </div>
      </div>

      <!-- Tabela Responsiva -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
          <thead>
            <tr class="app-theme-raised text-xs font-semibold uppercase tracking-wider app-theme-muted border-b app-theme-border">
              <th class="py-4 px-6 w-16 text-center">ID</th>
              <th class="py-4 px-6">Nome / Razão Social</th>
              <th class="py-4 px-6 text-center">Tipo</th>
              <th class="py-4 px-6">CPF / CNPJ</th>
              <th class="py-4 px-6">Contato</th>
              <th class="py-4 px-6 text-right">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-800/60">
            <tr 
              v-for="pessoa in pessoas.data" 
              :key="pessoa.id" 
              class="app-theme-row transition-colors group"
            >
              <!-- ID -->
              <td class="py-4 px-6 text-center text-xs font-mono text-gray-500 group-hover:text-gray-400">
                #{{ pessoa.id }}
              </td>
              
              <!-- Nome -->
              <td class="py-4 px-6">
                <div class="font-medium app-theme-heading group-hover:text-[#41B883] transition-colors">
                  {{ pessoa.nome }}
                </div>
              </td>
              
              <!-- Tipo (Badge com cor Vue) -->
              <td class="py-4 px-6 text-center">
                <span 
                  :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border',
                    pessoa.tipo === 'física' 
                      ? 'bg-[#41B883]/10 text-[#41B883] border-[#41B883]/20' 
                      : 'bg-blue-500/10 text-blue-400 border-blue-500/20'
                  ]"
                >
                  {{ pessoa.tipo === 'física' ? 'Pessoa Física' : 'Pessoa Jurídica' }}
                </span>
              </td>
              
              <!-- Documento -->
              <td class="py-4 px-6 text-sm font-mono app-theme-text">
                {{ formatDocument(pessoa.cpf) }}
              </td>
              
              <!-- Contato (Telefone e Email) -->
              <td class="py-4 px-6 text-sm">
                <div class="flex flex-col gap-0.5">
                  <span class="app-theme-text flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ pessoa.telefone ? formatPhone(pessoa.telefone) : 'Sem telefone' }}
                  </span>
                  <span class="text-xs text-gray-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ pessoa.email }}
                  </span>
                </div>
              </td>
              
              <!-- Ações da Linha -->
              <td class="py-4 px-6 text-right text-sm">
                <div class="flex justify-end gap-3 opacity-80 group-hover:opacity-100 transition-opacity">
                  <Link :href="route('pessoas.show', pessoa.id)" class="text-gray-400 hover:text-white transition-colors" title="Visualizar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </Link>
                  <Link :href="route('pessoas.edit', pessoa.id)" class="text-gray-400 hover:text-white transition-colors" title="Editar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                  </Link>
                  <button @click="destroy(pessoa)" :disabled="deleteForm.processing" class="text-gray-400 hover:text-[#FF2D20] transition-colors" title="Excluir">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="pessoas.data.length === 0">
              <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400">Nenhuma pessoa encontrada.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginação / Footer -->
      <div class="p-4 border-t app-theme-border flex items-center justify-between text-xs app-theme-muted app-theme-raised">
        <div>Exibindo {{ pessoas.from ?? 0 }}–{{ pessoas.to ?? 0 }} de {{ pessoas.total }} registros</div>
        <div class="flex gap-1">
          <Link v-for="link in pessoas.links" :key="link.label" :href="link.url || '#'" :class="['px-2.5 py-1.5 rounded border app-theme-border app-theme-text transition-colors', link.active ? 'bg-[#FF2D20]' : 'app-theme-control hover:bg-gray-800', !link.url && 'pointer-events-none opacity-40']">
            <span v-html="link.label" />
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Apresenta_me from '@/Components/Apresenta_me.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { formatDocument, formatPhone } from '@/utils/pessoaFormatters';

const props = defineProps({
  pessoas: { type: Object, required: true },
  filters: { type: Object, default: () => ({}) },
});

const filtersForm = useForm({
  search: props.filters.search ?? '',
  tipo: props.filters.tipo ?? '',
});
const deleteForm = useForm({});

function applyFilters() {
  filtersForm.get(route('pessoas.index'), { preserveState: true, preserveScroll: true });
}

function destroy(pessoa) {
  if (window.confirm(`Deseja excluir ${pessoa.nome}?`)) {
    deleteForm.delete(route('pessoas.destroy', pessoa.id), { preserveScroll: true });
  }
}

</script>