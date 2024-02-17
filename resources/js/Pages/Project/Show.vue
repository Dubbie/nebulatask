<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import BoardSection from "@/Components/BoardSection.vue";
import ProjectSidebar from "@/Pages/Project/Partials/ProjectSidebar.vue";
import AddBoardSection from "@/Components/AddBoardSection.vue";
import draggable from "vuedraggable";
import { getCurrentInstance, onMounted, provide, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import NewIssueModal from "@/Components/NewIssueModal.vue";
import axios from "axios";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Array,
        required: true,
    },
});

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const sections = ref(props.project.board_sections);
const showNewIssueModal = ref(false);
const selectedBoardSection = ref(null);
const loadingIssues = ref(false);

provide("statuses", props.statuses);

const handleChange = () => {
    const form = useForm({
        board_sections: sections.value,
    });

    form.put(
        route("board-section.sequence.update", { project: props.project.id }),
        {
            preserveScroll: true,
        }
    );
};

const handleNewIssueModal = (boardSection) => {
    selectedBoardSection.value = boardSection;
    showNewIssueModal.value = true;
};

const handleHideNewIssueModal = () => {
    selectedBoardSection.value = null;
    showNewIssueModal.value = false;
};

const updateIssue = (issueId) => {
    loadingIssues.value = true;
    axios
        .get(route("api.issue.fetch", { issue: issueId }))
        .then((response) => {
            const targetBoardId = response.data.board_section_id;

            sections.value
                .filter((section) => {
                    return section.id === targetBoardId;
                })
                .map((section) => {
                    section.issues = section.issues.map((issue) => {
                        if (issue.id === issueId) {
                            issue = response.data;
                        }
                        return issue;
                    });

                    return section;
                });
        })
        .finally(() => {
            loadingIssues.value = false;
        });
};

const handleIssueMovedAcross = (issueId, boardSectionId, newIndex) => {
    loadingIssues.value = true;
    axios
        .put(route("api.issue.move", { issue: issueId }), {
            board_section_id: boardSectionId,
            sequence: newIndex,
        })
        .then((response) => {
            // updateIssues(response.data);
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loadingIssues.value = false;
        });
};

const handleIssueMoved = (issues) => {
    loadingIssues.value = true;
    axios
        .put(route("api.issue.sequence.update"), {
            issues: issues,
        })
        .then((response) => {
            // updateIssues(response.data);
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            loadingIssues.value = false;
        });
};

const fetchProjectIssues = () => {
    loadingIssues.value = true;
    axios
        .get(route("api.issue.fetch-by-project", { project: props.project.id }))
        .then((response) => {
            updateIssues(response.data);
        })
        .finally(() => {
            loadingIssues.value = false;
        });
};

const updateIssues = (issues) => {
    sections.value.forEach((section) => {
        section.issues = issues.filter(
            (issue) => issue.board_section_id === section.id
        );
    });
};

watch(
    props,
    (newProps) => {
        sections.value = newProps.project.board_sections;
    },
    { deep: true }
);

onMounted(() => {
    emitter.on("update-issue", (issueId) => {
        updateIssue(issueId);
    });

    emitter.on("update-issues", () => {
        fetchProjectIssues();
    });

    emitter.on("issue-moved-across", (data) => {
        handleIssueMovedAcross(
            data.issueId,
            data.boardSectionId,
            data.newIndex
        );
    });

    emitter.on("issue-sequence-updated", (issueId) => {
        handleIssueMoved(issueId);
    });
});
</script>

<template>
    <AppLayout :full-width="true" :title="project.name">
        <div class="grid grid-cols-12 flex-1">
            <ProjectSidebar :project="project" />

            <div
                class="col-span-9 lg:col-span-9 xl:col-span-10 bg-white py-8 px-4 sm:px-6 lg:px-8 flex flex-col"
            >
                <h2 class="text-3xl font-semibold mb-6">Board</h2>

                <div
                    class="flex-1 flex flex-nowrap space-x-3 overflow-x-scroll"
                    :class="{
                        'opacity-50 pointer-events-none': loadingIssues,
                    }"
                >
                    <draggable
                        v-model="sections"
                        group="sections"
                        @change="handleChange"
                        item-key="id"
                        handle=".handle"
                        ghost-class="ghost"
                        class="flex flex-nowrap space-x-3"
                    >
                        <template #item="{ element }">
                            <BoardSection
                                :project-id="project.id"
                                :section="element"
                                @new-issue="handleNewIssueModal"
                            />
                        </template>
                    </draggable>
                    <AddBoardSection :project="project" />
                </div>
            </div>
        </div>

        <NewIssueModal
            :show="showNewIssueModal"
            @close="handleHideNewIssueModal"
            :boardSection="selectedBoardSection"
        />
    </AppLayout>
</template>
