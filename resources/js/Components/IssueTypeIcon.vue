<script setup>
import { IconCopyCheck } from "@tabler/icons-vue";
import { IconLayersIntersect } from "@tabler/icons-vue";
import { IconCheck } from "@tabler/icons-vue";
import { computed } from "vue";

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    sizeClass: {
        type: String,
        default: "size-4",
    },
});

const colorClasses = computed(() => {
    return {
        ISSUE: "bg-zinc-400 text-white dark:bg-zinc-600",
        SUB_ISSUE: "bg-blue-400 text-white",
        EPIC: "bg-orange-400 text-white",
    }[props.type];
});
</script>

<template>
    <div
        class="flex items-center justify-center w-fit p-1 aspect-square rounded-lg"
        :class="colorClasses"
    >
        <IconCheck
            :class="sizeClass"
            :stroke-width="3"
            v-if="type === 'ISSUE'"
        />
        <IconLayersIntersect
            :class="sizeClass"
            :stroke-width="2"
            v-if="type === 'SUB_ISSUE'"
        />
    </div>
</template>
