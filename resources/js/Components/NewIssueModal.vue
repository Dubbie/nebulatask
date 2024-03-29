<script setup>
import { useForm } from "@inertiajs/vue3";
import DialogModal from "@/Components/DialogModal.vue";
import AppButton from "@/Components/AppButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import {
    computed,
    getCurrentInstance,
    inject,
    onMounted,
    onUpdated,
    ref,
} from "vue";

const props = defineProps({
    boardSectionId: {
        type: Number,
        required: false,
    },
    show: {
        type: Boolean,
        default: false,
    },
});

const darkMode = ref(document.documentElement.classList.contains("dark"));
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const storing = ref(false);
const buttonLabel = computed(() => {
    return storing.value ? "Creating issue..." : "Create";
});

const form = useForm({
    title: "",
    description: "",
    due_date: null,
    board_section_id: props.boardSectionId ? props.boardSectionId : null,
});

const handleSubmit = () => {
    // Update board section id
    if (props.boardSectionId) {
        form.board_section_id = props.boardSectionId;
    }

    storing.value = true;
    axios
        .post(route("api.issue.store"), form)
        .then((response) => {
            emitter.emit("issue-created", response.data.data);
            form.reset();
            emit("close");
        })
        .finally(() => {
            storing.value = false;
        });
};

const emit = defineEmits(["close"]);

onUpdated(() => {
    darkMode.value = document.documentElement.classList.contains("dark");
});
</script>

<template>
    <DialogModal :show="show" @close="$emit('close')">
        <template #title> New Issue </template>
        <template #description
            >Please fill out the form below to create a new issue.</template
        >

        <template #content>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="col-span-2 space-y-3">
                    <div>
                        <InputLabel for="title" value="Title *" class="mb-1" />

                        <TextInput
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="text-sm block w-full"
                            @keydown="form.clearErrors('title')"
                            autofocus
                            required
                        />

                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div>
                        <InputLabel
                            for="description"
                            value="Description"
                            class="mb-1"
                        />

                        <TextareaInput
                            id="description"
                            v-model="form.description"
                            type="text"
                            class="text-sm block w-full"
                            @keydown="form.clearErrors('description')"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.description"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="due_date"
                            value="Due date"
                            class="mb-1"
                        />

                        <TextInput
                            id="due_date"
                            v-model="form.due_date"
                            type="date"
                            class="text-sm block w-full max-w-40"
                            @change="form.clearErrors('due_date')"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.due_date"
                        />
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <InputLabel>Assigned user</InputLabel>
                        <div class="flex items-center space-x-2 mt-1">
                            <img
                                :src="$page.props.auth.user.profile_photo_url"
                                class="rounded-full size-8"
                                alt=""
                            />
                            <p class="font-semibold dark:text-white">
                                {{ $page.props.auth.user.name }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <AppButton
                plain
                @click="$emit('close')"
                :color="darkMode ? 'white' : 'dark'"
                >Cancel</AppButton
            >
            <AppButton
                :disabled="storing"
                @click="handleSubmit"
                :color="darkMode ? 'white' : 'dark'"
            >
                {{ buttonLabel }}
            </AppButton>
        </template>
    </DialogModal>
</template>
