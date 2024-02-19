<script setup>
import { getCurrentInstance, onMounted, onUnmounted, ref } from "vue";
import NewProjectModal from "./NewProjectModal.vue";

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const showModal = ref(false);

onMounted(() => {
    emitter.on("show-new-project-modal", () => {
        showModal.value = true;
    });

    emitter.on("hide-new-project-modal", () => {
        showModal.value = false;
    });
});

onUnmounted(() => {
    emitter.off("show-new-project-modal");
    emitter.off("hide-new-project-modal");
});
</script>

<template>
    <NewProjectModal :show="showModal" @close="showModal = false" />
</template>
