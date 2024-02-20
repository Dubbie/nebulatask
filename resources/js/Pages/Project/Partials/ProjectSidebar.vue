<script setup>
import SidebarLink from "@/Components/SidebarLink.vue";
import { IconLayoutKanban } from "@tabler/icons-vue";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="col-span-3 xl:col-span-2 px-4 py-8 sm:px-6 lg:px-8 border-r border-zinc-950/10"
    >
        <div class="mb-6">
            <h1 class="text-zinc-700 font-semibold dark:text-white">
                {{ project.name }}
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ project.description ?? "No description" }}
            </p>
        </div>

        <div>
            <SidebarLink
                :href="route('project.show', { project: project.id })"
                :active="route().current('project.show')"
            >
                <IconLayoutKanban class="w-6 h-6" />
                <p>Board</p>
            </SidebarLink>
        </div>
    </div>
</template>
