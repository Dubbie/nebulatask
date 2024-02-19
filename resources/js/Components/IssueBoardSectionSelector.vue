<script setup>
import {
    getCurrentInstance,
    inject,
    onMounted,
    onUnmounted,
    ref,
    watch,
} from "vue";
import SelectInput from "@/Components/SelectInput.vue";

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const issue = inject("issue");
const boardSection = ref(issue.board_section_id);
const savingStatus = ref(false);
const availableBoardSections = inject("availableBoardSections");
const boardSectionOptions = availableBoardSections.value.map(
    (_boardSection) => {
        return {
            label: _boardSection.name,
            value: _boardSection.id,
        };
    }
);

const updateBoardSection = (newBoardSection) => {
    // const oldStatus = issue.value.issue_status_id;
    emitter.emit("issue-moved-across", {
        issueId: issue.value.id,
        boardSectionId: newBoardSection,
        newIndex: 0,
    });
};

watch(
    issue,
    (newIssue) => {
        boardSection.value = newIssue ? newIssue.board_section_id : null;
    },
    {
        immediate: true,
    }
);

onMounted(() => {
    emitter.on("board-section-move-start", () => {
        savingStatus.value = true;
    });

    emitter.on("board-section-move-end", () => {
        savingStatus.value = false;
    });
});

onUnmounted(() => {
    emitter.off("board-section-move-start");
    emitter.off("board-section-move-end");
});
</script>

<template>
    <SelectInput
        :model-value="boardSection"
        :options="boardSectionOptions"
        :disabled="savingStatus"
        class="text-sm"
        @update:model-value="updateBoardSection"
    />
</template>
