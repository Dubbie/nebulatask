<script setup>
import BoardSectionPlaceholder from "@/Pages/Project/Partials/BoardSectionPlaceholder.vue";

defineProps({
    boardSectionCount: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div class="flex-1 flex">
        <div class="flex-1 flex flex-nowrap overflow-x-hidden space-x-3">
            <BoardSectionPlaceholder v-for="i in boardSectionCount" />
            <BoardSectionPlaceholder />
        </div>
    </div>
</template>
