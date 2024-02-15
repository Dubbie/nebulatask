<script setup>
const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="bg-white shadow-xl shadow-zinc-200 text-zinc-950 rounded-2xl p-6"
    >
        <p class="text-xl font-semibold">{{ project.name }}</p>
        <p>{{ project.description ?? "No Description" }}</p>
    </div>
</template>
