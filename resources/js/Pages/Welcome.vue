<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const technologies = [
    'Laravel 13',
    'PHP 8.3',
    'Vue 3',
    'Inertia.js 2',
    'Tailwind CSS',
    'Docker Compose',
];

const features = [
    {
        number: '01',
        title: 'Cadastro completo',
        description:
            'Crie e mantenha registros de pessoas físicas e jurídicas em um só lugar.',
    },
    {
        number: '02',
        title: 'Busca e organização',
        description:
            'Pesquise, filtre e consulte os registros com uma interface simples e responsiva.',
    },
    {
        number: '03',
        title: 'Backend validado',
        description:
            'Operações e regras de validação organizadas no padrão Laravel, com Form Requests.',
    },
];
</script>

<template>
    <Head title="Projeto Pessoas" />

    <div class="app-theme-page min-h-screen">
        <header class="app-theme-surface app-theme-border border-b">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8"
            >
                <a
                    href="#inicio"
                    class="app-theme-heading text-lg font-bold tracking-tight"
                >
                    Projeto <span class="text-[var(--app-accent)]">Pessoas</span>
                </a>

                <nav
                    v-if="canLogin"
                    class="flex items-center gap-2"
                    aria-label="Acesso à aplicação"
                >
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="app-theme-text rounded-lg px-4 py-2 text-sm font-medium transition hover:bg-[var(--app-hover-bg)]"
                    >
                        Painel
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="app-theme-text rounded-lg px-4 py-2 text-sm font-medium transition hover:bg-[var(--app-hover-bg)]"
                        >
                            Entrar
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-lg bg-[var(--app-accent)] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[var(--app-accent-hover)]"
                        >
                            Criar conta
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <main id="inicio" class="mx-auto max-w-7xl px-6 lg:px-8">
            <section
                class="grid items-center gap-12 py-16 sm:py-20 lg:grid-cols-[1.2fr_0.8fr] lg:gap-20 lg:py-28"
            >
                <div>
                    <p
                        class="mb-5 inline-flex items-center gap-2 rounded-full border app-theme-border px-3 py-1.5 text-sm app-theme-muted"
                    >
                        <span
                            class="size-2 rounded-full bg-[var(--app-accent)]"
                            aria-hidden="true"
                        ></span>
                        Projeto de demonstração
                    </p>

                    <h1
                        class="app-theme-heading max-w-3xl text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl"
                    >
                        Gestão de pessoas,
                        <span class="text-[var(--app-accent)]"
                            >simples e organizada.</span
                        >
                    </h1>

                    <p
                        class="app-theme-text mt-6 max-w-2xl text-lg leading-8"
                    >
                        O Projeto Pessoas é uma aplicação web para cadastrar,
                        pesquisar, visualizar, editar e excluir pessoas físicas
                        e jurídicas. Desenvolvido como projeto de demonstração
                        técnica durante um processo de candidatura.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('login')"
                            class="rounded-lg bg-[var(--app-accent)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--app-accent-hover)]"
                        >
                            Acessar aplicação
                        </Link>
                        <Link
                            v-else-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-lg bg-[var(--app-accent)] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[var(--app-accent-hover)]"
                        >
                            Ir para o painel
                        </Link>
                        <a
                            href="https://github.com/JaisonDalls/projeto-pessoas"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="app-theme-border app-theme-text rounded-lg border px-5 py-3 text-sm font-semibold transition hover:bg-[var(--app-hover-bg)]"
                        >
                            Ver código no GitHub
                        </a>
                    </div>

                    <ul
                        class="mt-8 flex flex-wrap gap-2"
                        aria-label="Tecnologias utilizadas"
                    >
                        <li
                            v-for="technology in technologies"
                            :key="technology"
                            class="app-theme-raised app-theme-text rounded-md px-3 py-1.5 text-xs font-medium"
                        >
                            {{ technology }}
                        </li>
                    </ul>
                </div>

                <aside
                    class="app-theme-surface app-theme-border rounded-2xl border p-7 shadow-sm sm:p-9"
                    aria-labelledby="author-heading"
                >
                    <div
                        class="mb-6 flex size-14 items-center justify-center rounded-2xl bg-[var(--app-accent)]/10 text-xl font-bold text-[var(--app-accent)]"
                        aria-hidden="true"
                    >
                        JD
                    </div>
                    <p class="app-theme-muted text-sm font-medium">
                        Desenvolvido por
                    </p>
                    <h2
                        id="author-heading"
                        class="app-theme-heading mt-1 text-2xl font-bold"
                    >
                        Jaison Dallabrida
                    </h2>
                    <p class="app-theme-text mt-4 leading-7">
                        Profissional de tecnologia interessado em oportunidades
                        que tragam conhecimento e experiência na área de TI,
                        com participação em projetos corporativos e sociais.
                    </p>

                    <div class="app-theme-border mt-7 border-t pt-6">
                        <p
                            class="app-theme-muted mb-3 text-xs font-semibold uppercase tracking-wider"
                        >
                            Encontre-me
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a
                                href="https://github.com/JaisonDalls"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="app-theme-text inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition hover:bg-[var(--app-hover-bg)]"
                            >
                                GitHub
                                <span aria-hidden="true">↗</span>
                            </a>
                            <a
                                href="https://www.linkedin.com/in/jaisondallabrida/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="app-theme-text inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition hover:bg-[var(--app-hover-bg)]"
                            >
                                LinkedIn
                                <span aria-hidden="true">↗</span>
                            </a>
                        </div>
                    </div>
                </aside>
            </section>

            <section
                class="app-theme-border border-t py-14 sm:py-16"
                aria-labelledby="features-heading"
            >
                <div class="mb-8 max-w-2xl">
                    <p
                        class="text-sm font-semibold uppercase tracking-wider text-[var(--app-accent)]"
                    >
                        Sobre o projeto
                    </p>
                    <h2
                        id="features-heading"
                        class="app-theme-heading mt-2 text-2xl font-bold sm:text-3xl"
                    >
                        Uma aplicação prática, do backend à interface.
                    </h2>
                    <p class="app-theme-text mt-3 leading-7">
                        O projeto reúne operações essenciais de cadastro em
                        uma experiência acessível, adaptável a telas diferentes
                        e com suporte aos temas claro e escuro do dispositivo.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <article
                        v-for="feature in features"
                        :key="feature.number"
                        class="app-theme-surface app-theme-border rounded-xl border p-6"
                    >
                        <p
                            class="text-sm font-bold text-[var(--app-accent)]"
                        >
                            {{ feature.number }}
                        </p>
                        <h3
                            class="app-theme-heading mt-4 text-lg font-semibold"
                        >
                            {{ feature.title }}
                        </h3>
                        <p class="app-theme-text mt-2 text-sm leading-6">
                            {{ feature.description }}
                        </p>
                    </article>
                </div>
            </section>
        </main>

        <footer class="app-theme-border border-t">
            <div
                class="app-theme-muted mx-auto flex max-w-7xl flex-col gap-2 px-6 py-6 text-sm sm:flex-row sm:items-center sm:justify-between lg:px-8"
            >
                <p>Projeto de demonstração técnica, não um produto oficial.</p>
                <a
                    href="https://github.com/JaisonDalls/projeto-pessoas"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="transition hover:text-[var(--app-text-primary)]"
                >
                    Código-fonte no GitHub <span aria-hidden="true">↗</span>
                </a>
            </div>
        </footer>
    </div>
</template>
