<script setup>
import { computed } from "vue";

const props = defineProps({
    color: {
        type: String,
        default: "zinc",
    },
    size: {
        type: String,
        default: "md",
    },
    outline: {
        type: Boolean,
        default: false,
    },
});

const colorClass = computed(() => {
    if (props.outline) {
        return {
            zinc: "ring-zinc-300/25 text-zinc-400",
            red: "ring-red-500/25 text-red-400",
            green: "ring-green-500/25 text-green-500",
            yellow: "ring-yellow-300/25 text-yellow-400",
            teal: "ring-teal-500/25 text-teal-500",
            blue: "ring-blue-600/25 text-blue-400",
            brown: "ring-amber-700/25 text-amber-400",
            purple: "ring-purple-500/25 text-purple-500",
            orange: "ring-orange-700/25 text-orange-500",
            gray: "ring-gray-200/25 text-gray-200",
            white: "ring-white text-zinc-900",
        }[props.color];
    }

    return {
        zinc: "bg-zinc-300/15 text-zinc-400",
        red: "bg-red-500/30 text-red-400",
        green: "bg-green-500/20 text-green-500",
        yellow: "bg-yellow-300/15 text-yellow-300",
        teal: "bg-teal-500/20 text-teal-500",
        blue: "bg-blue-600/40 text-blue-400",
        brown: "bg-amber-700/15 text-amber-300",
        purple: "bg-purple-500/30 text-purple-400",
        orange: "bg-orange-700/30 text-orange-500",
        gray: "bg-gray-200/30 text-gray-200",
        white: "bg-white text-zinc-900",
    }[props.color];
});

const sizeClass = computed(() => {
    return {
        xs: "text-xs px-2 py-0.5 rounded-md font-medium",
        sm: "text-sm px-2 py-1 rounded-md font-medium",
        md: "text-base px-2 py-1 rounded-lg font-medium",
        lg: "text-lg px-4 py-3 rounded-lg font-medium",
    }[props.size];
});
</script>

<template>
    <div
        class="inline-flex items-center justify-center gap-x-2 ring-inset"
        :class="[{ 'ring-1': outline }, colorClass, sizeClass]"
    >
        <slot />
    </div>
</template>
