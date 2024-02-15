<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label
        class="block select-none text-base/6 font-semibold text-zinc-950 sm:text-sm/6"
    >
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
