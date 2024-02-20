<script setup>
import { IconPlus } from "@tabler/icons-vue";
</script>

<template>
    <div
        class="group cursor-pointer bg-white rounded-xl py-1 flex items-center justify-center space-x-2 hover:ring-1 hover:ring-zinc-950/10 dark:bg-zinc-950 dark:hover:bg-zinc-800"
    >
        <IconPlus
            class="text-zinc-400 -ms-4 group-hover:text-zinc-700 dark:text-zinc-500 dark:group-hover:text-zinc-400"
            size="16"
        />
        <p
            class="font-semibold text-sm text-zinc-600 group-hover:text-zinc-700 dark:text-zinc-400 dark:group-hover:text-zinc-300"
        >
            New issue
        </p>
    </div>
</template>
