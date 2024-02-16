<script setup>
const props = defineProps({
    borderless: {
        type: Boolean,
        required: false,
    },
});
</script>

<template>
    <div
        class="rounded-xl bg-white"
        :class="{
            'shadow-[0px_0px_0px_1px_rgba(9,9,11,0.07),0px_2px_2px_0px_rgba(9,9,11,0.05)]':
                !borderless,
        }"
    >
        <slot />
    </div>
</template>
