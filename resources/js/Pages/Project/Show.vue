<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import BoardSection from "@/Components/BoardSection.vue";
import ProjectSidebar from "@/Pages/Project/Partials/ProjectSidebar.vue";
import AddBoardSection from "@/Components/AddBoardSection.vue";
import draggable from "vuedraggable";
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
});

const sections = ref(props.project.board_sections);

const handleChange = () => {
    console.log(sections.value);
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

watch(
    props,
    (newProps) => {
        sections.value = newProps.project.board_sections;
    },
    { deep: true }
);
</script>

<template>
    <AppLayout :full-width="true" :title="project.name">
        <div class="grid grid-cols-8 flex-1">
            <ProjectSidebar :project="project" />

            <div
                class="col-span-7 bg-white py-8 px-4 sm:px-6 lg:px-8 flex flex-col"
            >
                <h2 class="text-3xl font-semibold mb-6">Board</h2>

                <div
                    class="flex-1 flex flex-nowrap space-x-3 overflow-x-scroll"
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
                            />
                        </template>
                    </draggable>
                    <AddBoardSection :project="project" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
