<script setup>
const props = defineProps({
    textAlign: {
        type: String,
        default: "text-start",
    },
});
</script>

<template>
    <th class="font-medium px-2 py-1" :class="textAlign">
        <slot />
    </th>
</template>
