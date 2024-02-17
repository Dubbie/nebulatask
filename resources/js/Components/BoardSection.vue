<script setup>
import { IconDots, IconPlus } from "@tabler/icons-vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import AppButton from "@/Components/AppButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import {
    computed,
    getCurrentInstance,
    reactive,
    ref,
    toRef,
    toRefs,
    watch,
} from "vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { useForm } from "@inertiajs/vue3";
import IssueCard from "@/Components/IssueCard.vue";
import AddIssueButton from "@/Pages/Project/Partials/AddIssueButton.vue";
import IssueDetailsModal from "@/Components/IssueDetailsModal.vue";
import draggable from "vuedraggable";

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

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const deleteSectionForm = useForm({
    _method: "DELETE",
});
const issues = ref(props.section.issues);
const rootIssues = computed(() => {
    return issues.value.filter((issue) => {
        return issue.parent_issue_id === null;
    });
});
const showIssueDetailsModal = ref(false);
const selectedIssue = ref(null);
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

const handleShowIssueDetailsModal = (issue) => {
    selectedIssue.value = issue;
    showIssueDetailsModal.value = true;
};

const handleHideIssueDetailsModal = () => {
    selectedIssue.value = null;
    showIssueDetailsModal.value = false;
};

const handleChange = (event) => {
    if ("added" in event) {
        emitter.emit("issue-moved-across", {
            issueId: event.added.element.id,
            boardSectionId: props.section.id,
            newIndex: event.added.newIndex,
        });
    } else if ("removed" in event) {
        // Remove, dont care
    } else {
        emitter.emit("issue-sequence-updated", issues);
    }
};

const checkSelected = (issue) => {
    return issue.id === selectedIssue.value?.id;
};

const emit = defineEmits(["new-issue", "added-issue", "removed-issue"]);

watch(
    () => props.section.issues,
    (newIssues) => {
        issues.value = newIssues;

        if (selectedIssue.value) {
            const foundIssue = newIssues.find((issue) => {
                return issue.id === selectedIssue.value.id;
            });

            if (foundIssue) {
                selectedIssue.value = foundIssue;
            }
        }
    },
    { deep: true }
);
</script>

<template>
    <div
        class="relative shrink-0 flex flex-col rounded-lg w-64 px-2"
        :class="{
            'bg-transparent': !handleHover,
            'bg-zinc-200/30 cursor-pointer': handleHover,
        }"
    >
        <div
            class="handle flex items-center p-2 mb-2"
            @mouseenter="handleHover = true"
            @mouseleave="handleHover = false"
            @click.stop
        >
            <p class="flex-1 text-sm text-zinc-500 font-semibold">
                {{ section.name }}
                <span class="text-xs text-zinc-300 ms-1">{{
                    section.issues.length
                }}</span>
            </p>

            <div class="flex space-x-1 -my-1">
                <div
                    class="p-1 rounded-lg text-zinc-400 hover:bg-zinc-950/5 hover:text-zinc-600"
                    @click="$emit('new-issue', section)"
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

        <draggable
            v-model="rootIssues"
            group="section-issues"
            @change="handleChange"
            item-key="id"
            handle=".handle"
            ghost-class="ghost"
        >
            <template #item="{ element }">
                <IssueCard
                    @click="handleShowIssueDetailsModal(element)"
                    :issue="element"
                    :selected="checkSelected(element)"
                    class="mb-3"
                />
            </template>
        </draggable>
        <div
            v-if="issues.length === 0"
            class="h-full absolute top-0 pt-11 inset-x-0 pointer-events-none"
        >
            <div
                class="h-full rounded-2xl bg-gradient-to-b from-zinc-950/5 to-transparent"
            ></div>
        </div>

        <AddIssueButton
            class="relative z-10"
            :class="{
                'mt-2': issues.length == 0,
            }"
            @click="$emit('new-issue', section)"
        />

        <IssueDetailsModal
            :issue="selectedIssue"
            :show="showIssueDetailsModal"
            @close="handleHideIssueDetailsModal"
        />

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
