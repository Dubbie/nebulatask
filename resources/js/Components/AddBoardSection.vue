<script setup>
import { IconPlus } from "@tabler/icons-vue";
import { getCurrentInstance, ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const editing = ref(false);
const loading = ref(false);
const newSectionForm = useForm({
    name: "",
});

const handleClose = () => {
    // Check if value is not empty
    if (!newSectionForm.name) {
        editing.value = false;
        return;
    }

    // Create new section
    loading.value = true;

    axios
        .post(route("api.board-section.store"), {
            name: newSectionForm.name,
            project_id: props.project.id,
        })
        .then((response) => {
            newSectionForm.reset();
            editing.value = false;
            loading.value = false;
            emitter.emit("board-section-created", response.data.data);
        });
};

const handleKeydown = (event) => {
    if (event.key === "Enter") {
        event.preventDefault();
        event.currentTarget.blur();
        handleClose();
    }
};
</script>

<template>
    <div class="shrink-0 flex flex-col rounded-xl w-full max-w-56">
        <div
            v-if="!editing"
            class="cursor-pointer p-1 flex space-x-2 items-center rounded-xl mb-1.5 px-2 group hover:bg-zinc-100"
            @click="editing = true"
        >
            <IconPlus
                class="text-zinc-400 group-hover:text-zinc-700"
                size="16"
            />

            <p
                class="flex-1 text-sm mb-0.5 text-zinc-500 font-medium group-hover:text-zinc-700"
            >
                New section
            </p>
        </div>
        <div v-else>
            <!-- Full Screen Dropdown Overlay -->
            <div
                v-if="editing"
                class="fixed inset-0 z-40"
                @click.stop="handleClose"
            />

            <div class="relative">
                <IconPlus
                    class="absolute left-1 top-[calc(0.325rem_+_1px)] text-zinc-700"
                    v-show="!loading"
                    size="16"
                />
                <TextInput
                    autofocus
                    class="rounded mb-2 mt-px block w-full"
                    size="sm"
                    :class="{
                        'pl-7  pt-[calc(0.325rem_-_2px)]': !loading,
                    }"
                    v-model="newSectionForm.name"
                    @keydown="handleKeydown"
                    :disabled="loading"
                    placeholder="Section name"
                />
            </div>
        </div>

        <div
            class="h-full bg-gradient-to-b rounded-2xl from-zinc-100 to-transparent"
        ></div>
    </div>
</template>
