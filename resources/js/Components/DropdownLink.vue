<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

defineProps({
    href: String,
    as: String,
});

const classes =
    "block w-full rounded-lg px-3 py-1.5 text-start text-sm/6 text-zinc-950 hover:bg-indigo-500 hover:text-white focus:outline-none focus:bg-indigo-500 dark:text-white/60 dark:hover:text-white";
</script>

<template>
    <div class="px-1">
        <button v-if="as == 'button'" type="submit" :class="classes">
            <slot />
        </button>

        <a v-else-if="as == 'a'" :href="href" :class="classes">
            <slot />
        </a>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
