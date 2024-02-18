<script setup>
import { getCurrentInstance, onMounted, ref } from "vue";
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
</script>

<template>
    <NewProjectModal :show="showModal" @close="showModal = false" />
</template>
