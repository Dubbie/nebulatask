<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});
</script>

<template>
    <Link
        :href="href"
        class="h-full inline-flex justify-center items-center pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out"
        :class="{
            'border-indigo-400 text-zinc-900 dark:border-transparent': active,
            'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300 dark:hover:border-transparent':
                !active,
        }"
    >
        <div
            class="px-2 py-2 rounded-xl dark:px-4"
            :class="{
                'dark:bg-white/15 dark:text-white': active,
                'dark:hover:bg-white/5 dark:text-zinc-400 dark:hover:text-white':
                    !active,
            }"
        >
            <slot />
        </div>
    </Link>
</template>
