<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div>
        <Link
            :href="href"
            class="flex space-x-2 items-center p-2 font-semibold rounded-lg"
            :class="{
                'bg-zinc-200 text-zinc-900 dark:bg-white/15 dark:text-white':
                    active,
                'text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-600 dark:hover:bg-transparent dark:hover:text-white':
                    !active,
            }"
        >
            <slot />
        </Link>
    </div>
</template>
