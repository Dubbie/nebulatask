<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
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
    <Link
        :href="href"
        class="cursor-pointer group flex items-center space-x-3 p-4 rounded-xl hover:bg-zinc-50"
    >
        <div v-if="$slots.icon">
            <slot name="icon" />
        </div>

        <div>
            <p class="font-semibold">{{ title }}</p>
            <p v-if="$slots"><slot name="description" /></p>
        </div>
    </Link>
</template>
