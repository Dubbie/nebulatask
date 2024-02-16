<script setup>
import { IconPencil } from "@tabler/icons-vue";
import { IconCalendarEvent } from "@tabler/icons-vue";
import { IconCheck } from "@tabler/icons-vue";
import { IconBackground } from "@tabler/icons-vue";
import { computed } from "vue";

const props = defineProps({
    status: {
        type: Object,
        required: true,
    },
    sizeClass: {
        type: String,
        default: "size-4",
    },
});

const colorClasses = computed(() => {
    return {
        BACKLOG: "bg-zinc-400 text-white",
        TO_DO: "bg-blue-400 text-white",
        IN_PROGRESS: "bg-orange-400 text-white",
        DONE: "bg-green-400 text-white",
    }[props.status.name];
});
</script>

<template>
    <div
        class="flex items-center justify-center w-fit p-1 aspect-square rounded-md"
        :class="colorClasses"
    >
        <IconBackground :class="sizeClass" v-if="status.name === 'BACKLOG'" />
        <IconCalendarEvent :class="sizeClass" v-if="status.name === 'TO_DO'" />
        <IconPencil :class="sizeClass" v-if="status.name === 'IN_PROGRESS'" />
        <IconCheck
            :class="sizeClass"
            :stroke-width="3"
            v-if="status.name === 'DONE'"
        />
    </div>
</template>
