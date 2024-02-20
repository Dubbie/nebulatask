<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import AppContainer from "@/Components/AppContainer.vue";
import AppNavigation from "@/Components/AppNavigation.vue";
import NewProject from "@/Components/NewProject.vue";

defineProps({
    title: String,
    fullWidth: {
        type: Boolean,
        default: false,
    },
});

console.log(usePage().props);
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div
            class="min-h-svh bg-zinc-100 text-zinc-950 flex flex-col dark:bg-zinc-950 dark:text-white"
        >
            <AppNavigation :full-width="fullWidth" />

            <!-- Page Heading -->
            <header
                v-if="$slots.header"
                class="bg-white shadow dark:bg-zinc-950 dark:text-white"
            >
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 flex flex-col">
                <component
                    id="page-content"
                    :is="fullWidth ? 'div' : AppContainer"
                    :class="{
                        'py-8 w-full': !fullWidth,
                    }"
                    class="flex-1 flex flex-col"
                >
                    <slot />
                </component>
            </main>
        </div>

        <NewProject />
    </div>
</template>
