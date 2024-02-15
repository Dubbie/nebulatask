<script setup>
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import AppButton from "@/Components/AppButton.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    project: {
        type: Object,
        required: false,
    },
});

const deleteProjectForm = useForm({});

const handleDeleteProject = () => {
    deleteProjectForm.delete(
        route("project.destroy", { project: props.project.id }),
        {
            preserveScroll: true,
            onSuccess: () => {
                emit("close");
            },
        }
    );
};

const emit = defineEmits(["close"]);
</script>

<template>
    <ConfirmationModal :show="show" @close="$emit('close')">
        <template #title> Delete Project </template>

        <template #content>
            Are you sure you would like to delete this project?
        </template>

        <template #footer>
            <AppButton @click="$emit('close')"> Cancel </AppButton>

            <AppButton
                color="red"
                :class="{ 'opacity-25': deleteProjectForm.processing }"
                :disabled="deleteProjectForm.processing"
                @click="handleDeleteProject"
            >
                Delete
            </AppButton>
        </template>
    </ConfirmationModal>
</template>
