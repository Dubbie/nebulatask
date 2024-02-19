<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProjectSidebar from "@/Pages/Project/Partials/ProjectSidebar.vue";
import BoardSectionsLoading from "@/Pages/Project/Partials/BoardSectionsLoading.vue";
import BoardSection from "@/Pages/Project/Partials/BoardSection.vue";
import axios from "axios";
import { getCurrentInstance, onMounted, onUnmounted, provide, ref } from "vue";
import draggable from "vuedraggable";
import NewIssueModal from "@/Components/NewIssueModal.vue";
import AddBoardSection from "@/Components/AddBoardSection.vue";
import IssueDetailsModal from "@/Components/IssueDetailsModal.vue";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    boardSections: {
        type: Array,
        required: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const boardSectionsReactive = ref(null);
const processingBoardSection = ref(null);
const draggingElement = ref(null);
const showNewIssueModal = ref(false);
const selectedBoardSectionId = ref(null);
const selectedIssue = ref(null);
const showIssueDetails = ref(false);
const availableBoardSections = ref(props.boardSections);

provide("availableBoardSections", availableBoardSections);
provide("selectedIssue", selectedIssue);

const handleStart = (event) => {
    draggingElement.value = event.oldIndex;
};

const handleEnd = (event) => {
    draggingElement.value = null;
};

const handleChange = (event) => {
    axios.put(
        route("api.board-section.move", {
            boardSection: event.moved.element.id,
        }),
        {
            sequence: event.moved.newIndex,
        }
    );
};

const getBoardSections = () => {
    axios
        .get(route("api.project.board-sections", { project: props.project.id }))
        .then((response) => {
            boardSectionsReactive.value = response.data;
        });
};

const updateIssueSequence = (issueId, boardSectionId, newIndex) => {
    processingBoardSection.value = boardSectionId;

    axios
        .put(route("api.issue.sequence.update", { issue: issueId }), {
            board_section_id: boardSectionId,
            sequence: newIndex,
        })
        .finally(() => {
            processingBoardSection.value = null;
        });
};

const handleIssueMoved = (issueId, boardSectionId, newIndex) => {
    emitter.emit("board-section-move-start");
    axios
        .put(route("api.issue.move", { issue: issueId }), {
            board_section_id: boardSectionId,
            sequence: newIndex,
        })
        .then((response) => {
            updateIssue(response.data.data);
        })
        .finally(() => {
            emitter.emit("board-section-move-end");
        });
};

const updateIssue = (issueData, newIndex) => {
    boardSectionsReactive.value = boardSectionsReactive.value.map((section) => {
        if (section.id === issueData.board_section_id) {
            // Check if issue ID is not found in the issues then push it, else update it
            if (!section.issues.some((issue) => issue.id === issueData.id)) {
                section.issues.splice(newIndex, 0, issueData);
                return section;
            } else {
                section.issues = section.issues.map((issue) => {
                    if (issue.id === issueData.id) {
                        issue = issueData;
                    }
                    return issue;
                });
            }
        } else {
            // Check if issue ID is found in the issues, then remove from array
            if (section.issues.some((issue) => issue.id === issueData.id)) {
                section.issues = section.issues.filter(
                    (issue) => issue.id !== issueData.id
                );
            }
        }
        return section;
    });

    // Update selectedIssue if it matches the issueData
    if (selectedIssue.value && selectedIssue.value.id === issueData.id) {
        selectedIssue.value = issueData;
    }

    // Update parent if issue has one
    if (
        issueData.parent_issue_id &&
        selectedIssue.value.id === issueData.parent_issue_id
    ) {
        const parent = findIssueById(issueData.parent_issue_id);

        // Update the parent's sub issues aswell with the new data
        parent.sub_issues = parent.sub_issues.map((issue) => {
            if (issue.id === issueData.id) {
                issue = issueData;
            }
            return issue;
        });
    }
};

const createIssue = (issueData) => {
    if (issueData.parent_issue_id) {
        const parent = findIssueById(issueData.parent_issue_id);
        parent.sub_issues.push(issueData);
    } else {
        boardSectionsReactive.value
            .find((section) => section.id === issueData.board_section_id)
            .issues.push(issueData);
    }
};

const deleteIssue = (issueId) => {
    boardSectionsReactive.value = boardSectionsReactive.value.map((section) => {
        section.issues = section.issues.filter((issue) => issue.id !== issueId);
        return section;
    });
};

const createBoardSection = (boardSectionData) => {
    boardSectionsReactive.value.push(boardSectionData);

    updateAvailableBoardSections();
};

const deleteBoardSection = (boardSectionId) => {
    boardSectionsReactive.value = boardSectionsReactive.value.filter(
        (section) => section.id !== boardSectionId
    );

    // Check if selected issue is in the deleted section
    if (
        selectedIssue.value &&
        selectedIssue.value.board_section_id === boardSectionId
    ) {
        emitter.emit("close-issue-details");
        selectedIssue.value = null;
    }

    updateAvailableBoardSections();
};

const updateAvailableBoardSections = () => {
    availableBoardSections.value = boardSectionsReactive.value.map(
        (section) => {
            return {
                id: section.id,
                name: section.name,
            };
        }
    );
};

const handleClickEvent = (event) => {
    const clicked = document.elementFromPoint(event.clientX, event.clientY);

    // Find the closest .handle element
    const handle = clicked.closest(".handle");
    if (handle) {
        event.stopPropagation();
    }
};

const findIssueById = (issueId) => {
    for (const section of boardSectionsReactive.value) {
        const foundIssue = findIssueInIssues(section.issues, issueId);
        if (foundIssue) {
            return foundIssue;
        }
    }
    return null;
};

const findIssueInIssues = (issues, issueId) => {
    const topLevelIssue = issues.find((issue) => issue.id === issueId);
    if (topLevelIssue) {
        return topLevelIssue;
    }

    for (const issue of issues) {
        const foundIssue = findIssueInIssues(issue.sub_issues || [], issueId);
        if (foundIssue) {
            return foundIssue;
        }
    }

    return null;
};

onMounted(() => {
    getBoardSections();

    emitter.on("issue-updated", (issueData) => {
        updateIssue(issueData);
    });

    emitter.on("issue-created", (issueData) => {
        createIssue(issueData);
    });

    emitter.on("issue-deleted", (issueId) => {
        deleteIssue(issueId);
    });

    emitter.on("board-section-created", (boardSectionData) => {
        createBoardSection(boardSectionData);
    });

    emitter.on("board-section-deleted", (boardSectionId) => {
        deleteBoardSection(boardSectionId);
    });

    emitter.on("issue-moved", (data) => {
        updateIssueSequence(data.issueId, data.boardSectionId, data.newIndex);
    });

    emitter.on("issue-moved-across", (data) => {
        handleIssueMoved(data.issueId, data.boardSectionId, data.newIndex);
    });

    emitter.on("show-new-issue-modal", (boardSectionId) => {
        selectedBoardSectionId.value = boardSectionId;
        showNewIssueModal.value = true;
    });

    emitter.on("close-new-issue-modal", () => {
        selectedBoardSectionId.value = null;
        showNewIssueModal.value = false;
    });

    emitter.on("show-issue-details", (issueId) => {
        selectedIssue.value = findIssueById(issueId);
        console.log(selectedIssue.value);
        showIssueDetails.value = true;
    });

    emitter.on("close-issue-details", () => {
        selectedIssue.value = null;
        showIssueDetails.value = false;
    });

    emitter.on("update-section-issues", (data) => {
        // Update the section issues
        const sectionId = data.sectionId;
        const name = data.name;
        const issues = data.issues;

        boardSectionsReactive.value = boardSectionsReactive.value.map(
            (section) => {
                if (section.id === sectionId) {
                    section.name = name;
                    section.issues = issues;
                }
                return section;
            }
        );

        updateAvailableBoardSections();
    });
});

onUnmounted(() => {
    emitter.off("issue-updated");
    emitter.off("issue-created");
    emitter.off("issue-deleted");
    emitter.off("board-section-created");
    emitter.off("board-section-deleted");
    emitter.off("issue-moved");
    emitter.off("issue-moved-across");
    emitter.off("show-new-issue-modal");
    emitter.off("close-new-issue-modal");
    emitter.off("show-issue-details");
    emitter.off("close-issue-details");
    emitter.off("update-section-issues");
});
</script>

<template>
    <AppLayout :full-width="true" :title="project.name">
        <div class="grid grid-cols-12 flex-1" @click="closeSlideover">
            <ProjectSidebar :project="project" />

            <div
                class="col-span-9 lg:col-span-9 xl:col-span-10 bg-white py-8 px-4 sm:px-6 lg:px-8 flex flex-col"
                @click="emitter.emit('stop-editing')"
            >
                <h2 class="text-3xl font-semibold mb-6">Board</h2>

                <transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                    mode="out-in"
                >
                    <BoardSectionsLoading
                        v-if="!boardSectionsReactive"
                        :board-section-count="boardSections.length"
                    />

                    <div
                        class="flex-1 flex flex-nowrap overflow-x-scroll space-x-3"
                        v-else
                    >
                        <draggable
                            :list="boardSectionsReactive"
                            group="sections"
                            @change="handleChange"
                            @start="handleStart"
                            @end="handleEnd"
                            item-key="id"
                            :remove-clone-on-hide="true"
                            :force-fallback="true"
                            handle=".handle"
                            ghost-class="ghost"
                            class="flex flex-nowrap space-x-3"
                            @click="handleClickEvent"
                        >
                            <template #item="{ element, index }">
                                <BoardSection
                                    :class="{
                                        'pointer-events-none':
                                            draggingElement === index,
                                    }"
                                    :section="element"
                                    :dragging="draggingElement === index"
                                    :processing="
                                        processingBoardSection === element.id
                                    "
                                />
                            </template>
                        </draggable>

                        <AddBoardSection :project="project" />
                    </div>
                </transition>
            </div>
        </div>

        <NewIssueModal
            :show="showNewIssueModal"
            :board-section-id="selectedBoardSectionId"
            @close="emitter.emit('close-new-issue-modal')"
        />

        <IssueDetailsModal
            :show="showIssueDetails"
            :issue="selectedIssue"
            @close="emitter.emit('close-issue-details')"
        />
    </AppLayout>
</template>
