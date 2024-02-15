<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import AppButton from "@/Components/AppButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputError from "./InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    name: "",
    description: "",
});

const handleSubmit = () => {
    form.post(route("project.store"), {
        onSuccess: () => {
            form.reset();
            emit("close");
        },
    });
};

const emit = defineEmits(["close"]);
</script>

<template>
    <DialogModal
        max-width="md"
        :show="show"
        @close="$emit('close')"
        @submit="handleSubmit"
    >
        <template #title>New Project</template>
        <template #description
            >Create a new project where you can track issues.</template
        >

        <template #content>
            <div class="space-y-3">
                <div>
                    <InputLabel for="project_name" class="mb-2"
                        >Project name</InputLabel
                    >
                    <TextInput
                        id="project_name"
                        type="text"
                        class="text-sm block w-full"
                        v-model="form.name"
                        :invalid="form.errors.name !== undefined"
                        @keydown="form.clearErrors('name')"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="project_description" class="mb-2"
                        >Description</InputLabel
                    >
                    <TextareaInput
                        id="project_description"
                        type="text"
                        class="text-sm block w-full resize-none"
                        :invalid="form.errors.description !== undefined"
                        v-model="form.description"
                        rows="3"
                        @keydown="form.clearErrors('description')"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.description"
                    />
                </div>
            </div>
        </template>

        <template #footer>
            <AppButton plain @click="$emit('close')"> Cancel </AppButton>
            <AppButton type="submit" :disabled="form.processing">
                Create
            </AppButton>
        </template>
    </DialogModal>
</template>
