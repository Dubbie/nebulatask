<script setup>
import { IconDots, IconPlus } from "@tabler/icons-vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import AppButton from "@/Components/AppButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { getCurrentInstance, ref } from "vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    projectId: {
        type: Number,
        required: true,
    },
    section: {
        type: Object,
        required: true,
    },
});

const deleteSectionForm = useForm({
    _method: "DELETE",
});
const handleHover = ref(false);
const showConfirmDelete = ref(false);

const handleDeleteBoardSection = () => {
    deleteSectionForm.delete(
        route("board-section.destroy", {
            project: props.section.project_id,
            boardSection: props.section.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showConfirmDelete.value = false;
            },
        }
    );

    showConfirmDelete.value = false;
};

const issues = [];
</script>

<template>
    <div
        class="shrink-0 flex flex-col rounded-lg w-64 px-2"
        :class="{
            'bg-transparent': !handleHover,
            'bg-zinc-200/30 cursor-pointer': handleHover,
        }"
    >
        <div
            class="handle flex items-center p-2 mb-2"
            @mouseenter="handleHover = true"
            @mouseleave="handleHover = false"
        >
            <p class="flex-1 text-sm text-zinc-500 font-semibold">
                {{ section.name }}
                <span class="text-xs text-zinc-300 ms-1">0</span>
            </p>

            <div class="flex space-x-1 -my-1">
                <div
                    class="p-1 rounded-lg text-zinc-400 hover:bg-zinc-950/5 hover:text-zinc-600"
                >
                    <IconPlus size="16" />
                </div>
                <Dropdown>
                    <template #trigger>
                        <div
                            class="p-1 rounded-lg text-zinc-400 hover:bg-zinc-950/5 hover:text-zinc-600"
                        >
                            <IconDots size="16" />
                        </div>
                    </template>

                    <template #content>
                        <DropdownLink
                            as="button"
                            @click="showConfirmDelete = true"
                        >
                            Delete section
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </div>

        <div v-if="issues.length > 0">
            <div class="bg-white rounded-lg">
                <p>Ticket list</p>
            </div>
        </div>
        <div
            v-else
            class="h-full bg-gradient-to-b rounded-2xl from-zinc-950/5 to-transparent p-2"
        >
            <div
                class="bg-white rounded-xl py-1 flex items-center justify-center space-x-2"
            >
                <IconPlus class="text-zinc-400 -ms-4" size="16" />
                <p class="font-semibold text-sm text-zinc-600">Add task</p>
            </div>
        </div>

        <ConfirmationModal
            max-width="sm"
            :show="showConfirmDelete"
            @close="showConfirmDelete = false"
        >
            <template #title>Delete section</template>

            <template #content>
                Are you sure you would like to delete this section?
            </template>

            <template #footer>
                <AppButton plain @click="showConfirmDelete = false">
                    Cancel
                </AppButton>

                <AppButton
                    color="red"
                    :disabled="deleteSectionForm.processing"
                    @click="handleDeleteBoardSection"
                >
                    Delete
                </AppButton>
            </template>
        </ConfirmationModal>
    </div>
</template>

<style scoped>
.ghost {
    opacity: 0.5;
}
</style>
