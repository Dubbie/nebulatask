<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    color: {
        type: String,
        default: "dark",
    },
    plain: {
        type: Boolean,
        default: false,
    },
    outline: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: "button",
    },
    href: {
        type: String,
        default: null,
    },
    size: {
        type: String,
        default: "md",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    pill: {
        type: Boolean,
        default: false,
    },
    square: {
        type: Boolean,
        default: false,
    },
});

const colorClasses = computed(() => {
    const outline =
        " shadow-[inset_0_2px_0_0_rgba(255,255,255,0.2)] before:absolute before:inset-0 before:rounded-lg";

    if (props.plain) {
        return "bg-transparent text-black hover:bg-zinc-300/20";
    }

    if (props.outline) {
        return "bg-transparent text-white shadow-[inset_0_0_0_1px_rgba(255,255,255,0.15)] hover:bg-zinc-300/10";
    }

    return {
        zinc:
            "bg-zinc-300/35 text-white border-transparent hover:bg-zinc-300/45" +
            outline,
        red:
            "bg-red-600 text-white hover:bg-red-700 before:shadow-[inset_0_0_0_1px_rgba(185,28,28,0.9)]" +
            outline,
        green:
            "bg-green-600 text-white hover:bg-green-500 before:shadow-[inset_0_0_0_1px_rgba(21,128,64,0.9)]" +
            outline,
        blue:
            "bg-blue-600 text-white hover:bg-blue-500 before:shadow-[inset_0_0_0_1px_rgba(37,99,235,0.9)]" +
            outline,
        white: "bg-white text-zinc-900 hover:bg-white/95" + outline,
        dark: "bg-zinc-800 text-white hover:bg-zinc-700" + outline,
    }[props.color];
});
const sizeClasses = computed(() => {
    return {
        xs: "px-2 py-1 text-xs",
        sm: "px-2.5 py-1 text-xs",
        md: "px-3 py-1.5 text-sm",
        lg: "px-4 py-2.5 text-lg",
    }[props.size];
});

const disabledClasses = computed(() => {
    return props.disabled ? "opacity-50 pointer-events-none" : "";
});

const roundClasses = computed(() => {
    return props.pill ? "rounded-full" : "rounded-lg";
});

const squareClasses = computed(() => {
    return props.square ? "aspect-square" : "";
});
</script>

<template>
    <component
        :is="href ? Link : 'button'"
        :href="href"
        class="relative flex items-center justify-center space-x-2 font-semibold"
        :class="[
            colorClasses,
            sizeClasses,
            disabledClasses,
            roundClasses,
            squareClasses,
        ]"
        :type="href ? null : type"
    >
        <slot />
    </component>
</template>
