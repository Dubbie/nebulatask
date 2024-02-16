<script setup>
import { IconPlus } from "@tabler/icons-vue";
import { onMounted, ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

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
    newSectionForm.post(
        route("board-section.store", {
            project: props.project.id,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                newSectionForm.reset();
                editing.value = false;
                loading.value = false;
            },
        }
    );
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
    <div class="shrink-0 flex flex-col rounded-xl w-full max-w-64 px-2">
        <div
            v-if="!editing"
            class="cursor-pointer p-2 flex space-x-2 items-center mb-2 rounded-xl group hover:bg-zinc-100"
            @click="editing = true"
        >
            <IconPlus
                class="text-zinc-400 group-hover:text-zinc-700"
                size="16"
            />

            <p
                class="flex-1 text-sm text-zinc-500 font-semibold group-hover:text-zinc-700"
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
                    class="absolute left-2 top-2.5 text-zinc-700"
                    v-show="!loading"
                    size="16"
                />
                <TextInput
                    autofocus
                    class="mb-2 text-sm block w-full"
                    :class="{
                        'pl-8': !loading,
                    }"
                    v-model="newSectionForm.name"
                    @keydown="handleKeydown"
                    :disabled="loading"
                    placeholder="Section name"
                />
            </div>
        </div>

        <div
            class="h-full bg-gradient-to-b rounded-2xl from-zinc-950/5 to-transparent"
        ></div>
    </div>
</template>
