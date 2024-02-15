<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import AppButton from "@/Components/AppButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputError from "./InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    name: "",
    code: "",
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

const slugifyString = (text) => {
    const trimmedInput = text.trim();

    // Convert accents to regular letters
    const normalizedString = trimmedInput
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "");

    // Split the string into words
    const words = normalizedString.split(" ");

    // If it's a single word, return the slugified version
    if (words.length === 1) {
        return slugifyWord(words[0].slice(0, 8));
    }

    // If it's multiple words, return the starting letters
    return words.map((word) => word[0].toUpperCase()).join("");
};

const slugifyWord = (word) => {
    // Convert accents to regular letters
    const normalizedWord = word
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "");

    // Remove non-alphanumeric characters
    const slug = normalizedWord.replace(/[^a-zA-Z0-9]/g, "");

    return slug.toUpperCase();
};

const emit = defineEmits(["close"]);

watch(
    () => form.name,
    (newProjectName) => {
        form.code = slugifyString(newProjectName);
    }
);
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
                <div class="grid grid-cols-3 gap-x-3">
                    <div class="col-span-2">
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
                        <InputLabel for="project_code" class="mb-2"
                            >Code</InputLabel
                        >
                        <TextInput
                            id="project_code"
                            type="text"
                            maxlength="8"
                            class="text-sm block w-full"
                            v-model="form.code"
                            :invalid="form.errors.code !== undefined"
                            @keydown="form.clearErrors('code')"
                        />
                    </div>

                    <InputError
                        class="col-span-full mt-2"
                        :message="form.errors.code"
                    />
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
