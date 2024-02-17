<script setup>
const props = defineProps({
    borderless: {
        type: Boolean,
        required: false,
    },
    backgroundClasses: {
        type: String,
        default: "bg-white",
    },
});
</script>

<template>
    <div
        class="rounded-xl"
        :class="[
            {
                'shadow-[0px_0px_0px_1px_rgba(9,9,11,0.07),0px_2px_2px_0px_rgba(9,9,11,0.05)]':
                    !borderless,
            },
            backgroundClasses,
        ]"
    >
        <slot />
    </div>
</template>
