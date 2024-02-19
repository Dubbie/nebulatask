<script setup>
import draggable from "vuedraggable";
import BoardSectionIssue from "@/Pages/Project/Partials/BoardSectionIssue.vue";
import {
    computed,
    getCurrentInstance,
    onMounted,
    onUnmounted,
    ref,
    watch,
} from "vue";
import AddIssueButton from "@/Pages/Project/Partials/AddIssueButton.vue";
import BoardSectionHeader from "./BoardSectionHeader.vue";
import axios from "axios";

const props = defineProps({
    section: {
        type: Object,
        required: true,
    },
    processing: {
        type: Boolean,
        default: false,
    },
    dragging: {
        type: Boolean,
        default: false,
    },
});

const draggableElement = ref(null);
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const highlight = ref(false);
const draggingElement = ref(null);
const childCount = ref(props.section.issues.length);
const moveButton = computed(() => {
    return childCount.value === 0;
});
const cursorClass = computed(() => {
    return props.dragging ? "cursor-grabbing" : "cursor-auto";
});

const handleHighlight = (shouldHighlight) => {
    highlight.value = shouldHighlight;
};

const handleStart = (event) => {
    draggingElement.value = event.oldIndex;
};

const handleEnd = (event) => {
    draggingElement.value = null;
};

const handleIssueChange = (event) => {
    if ("moved" in event) {
        emitter.emit("issue-moved", {
            issueId: event.moved.element.id,
            boardSectionId: event.moved.element.board_section_id,
            newIndex: event.moved.newIndex,
        });
    } else if ("added" in event) {
        emitter.emit("issue-moved-across", {
            issueId: event.added.element.id,
            boardSectionId: props.section.id,
            newIndex: event.added.newIndex,
        });
    }
};

const checkChildren = (event) => {
    if (event.from !== event.to) {
        emitter.emit(
            "remove-from-section",
            parseInt(event.from.dataset.boardSectionId)
        );
        emitter.emit(
            "add-to-section",
            parseInt(event.to.dataset.boardSectionId)
        );
    } else {
        emitter.emit("reset-issue-count");
    }
};

const handleNewIssue = () => {
    emitter.emit("show-new-issue-modal", props.section.id);
};

const handleDeleteSection = () => {
    const boardSectionId = props.section.id;

    axios
        .delete(
            route("api.board-section.destroy", { boardSection: boardSectionId })
        )
        .then(() => {
            emitter.emit("board-section-deleted", boardSectionId);
        });
};

watch(
    props,
    () => {
        childCount.value = props.section.issues.length;
    },
    {
        deep: true,
    }
);

onMounted(() => {
    emitter.on("remove-from-section", (sectionId) => {
        if (sectionId !== props.section.id) {
            return;
        }

        childCount.value = props.section.issues.length - 1;
    });

    emitter.on("add-to-section", (sectionId) => {
        if (sectionId !== props.section.id) {
            return;
        }

        childCount.value = props.section.issues.length + 1;
    });

    emitter.on("reset-issue-count", () => {
        childCount.value = props.section.issues.length;
    });
});

onUnmounted(() => {
    emitter.off("remove-from-section");
    emitter.off("add-to-section");
    emitter.off("reset-issue-count");
});
</script>

<template>
    <div class="flex flex-col relative w-56" :class="[cursorClass]">
        <div
            v-if="dragging"
            class="absolute inset-0 bg-zinc-100 -m-px z-20"
        ></div>

        <BoardSectionHeader
            :section="section"
            @new-issue="handleNewIssue"
            @highlight="handleHighlight"
            @delete-section="handleDeleteSection"
        />

        <div class="h-full relative p-2">
            <div
                v-if="highlight || moveButton"
                class="pointer-events-none absolute inset-0 bg-gradient-to-b to-transparent rounded-2xl"
                :class="{
                    'from-zinc-100': highlight || moveButton,
                    'from-zinc-200': highlight && moveButton,
                }"
            ></div>

            <draggable
                class="w-full min-h-10"
                :data-board-section-id="section.id"
                :list="section.issues"
                :force-fallback="true"
                :remove-clone-on-hide="true"
                :invert-swap-threshold="true"
                filter=".ignore"
                group="issues"
                item-key="id"
                ref="draggableElement"
                @change="handleIssueChange"
                @start="handleStart"
                @end="handleEnd"
                @move="checkChildren"
                @click.stop
            >
                <template #item="{ element, index }">
                    <BoardSectionIssue
                        v-if="!element.parent_issue_id"
                        class="mb-3"
                        :class="{
                            'pointer-events-none': draggingElement === index,
                        }"
                        :issue="element"
                        :dragging="draggingElement === index"
                        @click.stop="
                            emitter.emit('show-issue-details', element.id)
                        "
                    />
                </template>
            </draggable>

            <AddIssueButton
                class="relative z-10 ignore"
                :class="{ '-mt-10': moveButton }"
                @click="handleNewIssue"
            />
        </div>
    </div>
</template>
