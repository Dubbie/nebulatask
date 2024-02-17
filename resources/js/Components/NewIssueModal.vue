<script setup>
import { useForm } from "@inertiajs/vue3";
import DialogModal from "@/Components/DialogModal.vue";
import AppButton from "@/Components/AppButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputError from "@/Components/InputError.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { computed, getCurrentInstance, inject, ref } from "vue";

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

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const statuses = inject("statuses");
const storing = ref(false);
const statusOptions = statuses.map((status) => {
    return {
        label: status.formatted_name,
        value: status.id,
    };
});
const buttonLabel = computed(() => {
    return storing.value ? "Creating issue..." : "Create";
});

const form = useForm({
    title: "",
    description: "",
    due_date: null,
    issue_status_id: statusOptions[0].value,
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
                        <p class="text-zinc-950 font-semibold mb-2.5">
                            Assigned user
                        </p>
                        <div class="flex items-center space-x-2">
                            <img
                                :src="$page.props.auth.user.profile_photo_url"
                                class="rounded-full size-8"
                                alt=""
                            />
                            <p class="font-semibold">
                                {{ $page.props.auth.user.name }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <InputLabel for="status" class="mb-1" value="Status" />
                        <SelectInput
                            id="status"
                            v-model="form.issue_status_id"
                            :options="statusOptions"
                        />
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <AppButton plain @click="$emit('close')">Cancel</AppButton>
            <AppButton :disabled="storing" @click="handleSubmit">
                {{ buttonLabel }}
            </AppButton>
        </template>
    </DialogModal>
</template>
