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
            class="flex space-x-2 items-center p-2 font-semibold rounded-lg hover:bg-gray-100"
            :class="{
                'bg-zinc-200 text-zinc-600 ': active,
                'text-gray-600': !active,
            }"
        >
            <slot />
        </Link>
    </div>
</template>
