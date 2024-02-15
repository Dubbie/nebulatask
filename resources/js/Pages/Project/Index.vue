<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppButton from "@/Components/AppButton.vue";
import NewProjectModal from "@/Components/NewProjectModal.vue";
import ProjectsList from "@/Pages/Project/Partials/ProjectsList.vue";
import ConfirmDeleteProjectModal from "@/Pages/Project/Partials/ConfirmDeleteProjectModal.vue";
import { ref } from "vue";

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
});

const showNewProjectModal = ref(false);
const showConfirmModal = ref(false);
const selectedProject = ref(null);

const handleShowConfirmModal = (project) => {
    selectedProject.value = project;
    showConfirmModal.value = true;
};

const handleCancelConfirmModal = () => {
    selectedProject.value = null;
    showConfirmModal.value = false;
};
</script>

<template>
    <AppLayout title="Projects">
        <template #header>
            <div class="flex">
                <div class="flex-1">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        Projects
                    </h2>
                </div>

                <div>
                    <AppButton color="blue" @click="showNewProjectModal = true"
                        >New project</AppButton
                    >
                </div>
            </div>
        </template>

        <ProjectsList
            :projects="projects"
            @delete-project="handleShowConfirmModal"
        />

        <NewProjectModal
            :show="showNewProjectModal"
            @close="showNewProjectModal = false"
        />

        <ConfirmDeleteProjectModal
            :show="showConfirmModal"
            :project="selectedProject"
            @close="handleCancelConfirmModal"
        />
    </AppLayout>
</template>
