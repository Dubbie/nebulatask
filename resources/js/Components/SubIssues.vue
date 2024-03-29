<script setup>
import { getCurrentInstance, inject, onUpdated, ref } from "vue";
import AppButton from "@/Components/AppButton.vue";
import NewSubIssue from "@/Components/NewSubIssue.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import SubIssueLine from "@/Components/SubIssueLine.vue";
import draggable from "vuedraggable";

const issue = inject("issue");
const showNewIssue = ref(false);
const savingSubIssue = ref(false);
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const darkMode = ref(document.documentElement.classList.contains("dark"));

const dragging = ref(null);
let cloneElement = null;

const newIssueForm = useForm({
    title: "",
});

const handleNewIssue = () => {
    if (newIssueForm.title.length < 1) {
        showNewIssue.value = false;
        return;
    }

    savingSubIssue.value = true;
    axios
        .post(
            route("api.sub-issue.store", { issue: issue.value.id }),
            newIssueForm.data()
        )
        .then((response) => {
            emitter.emit("issue-created", response.data.data);
            showNewIssue.value = false;
            newIssueForm.reset();
        })
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            savingSubIssue.value = false;
        });
};

const handleChange = (event) => {};

const updateClonePosition = (event) => {
    if (cloneElement) {
        cloneElement.style.left = `calc(${event.pageX}px + 1rem)`;
        cloneElement.style.top = `calc(${event.pageY}px - 1rem)`;
    }
};

const handleDragStart = (event) => {
    cloneElement = event.item.cloneNode(true);
    cloneElement.classList.remove("ghost");
    cloneElement.style.zIndex = 9999;
    cloneElement.style.position = "absolute";
    cloneElement.style.pointerEvents = "none";
    document.body.appendChild(cloneElement);
    dragging.value = true;
};

const handleDragEnd = () => {
    dragging.value = false;
    if (cloneElement) {
        document.body.removeChild(cloneElement);
        cloneElement = null;
    }
};

const updatePosition = (event) => {
    if (cloneElement) {
        updateClonePosition(event);
    }
};

onUpdated(() => {
    darkMode.value = document.documentElement.classList.contains("dark");
});
</script>

<template>
    <div>
        <AppButton
            outline
            :color="darkMode ? 'white' : 'dark'"
            @click="showNewIssue = true"
            v-show="!showNewIssue"
            >Add sub-issue</AppButton
        >

        <p
            class="text-sm text-zinc-500 mb-1"
            :class="{
                'mt-3': issue.sub_issues.length > 0,
            }"
            v-show="showNewIssue || issue.sub_issues.length > 0"
        >
            Sub-issues
        </p>
        <NewSubIssue
            v-model:title="newIssueForm.title"
            class="mb-2"
            v-if="showNewIssue"
            :disabled="savingSubIssue"
            @blur="handleNewIssue"
        />

        <draggable
            v-model="issue.sub_issues"
            group="sub-issues"
            @change="handleChange"
            item-key="id"
            handle=".handle"
            drag-class="opacity-0"
            ghost-class="ghost"
            @start="handleDragStart"
            @end="handleDragEnd"
            @drag="updatePosition"
        >
            <template #item="{ element }">
                <SubIssueLine :sub-issue="element" :dragging="dragging" />
            </template>
        </draggable>
    </div>
</template>
