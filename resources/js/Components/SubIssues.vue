<script setup>
import { getCurrentInstance, inject, onMounted, ref } from "vue";
import AppButton from "@/Components/AppButton.vue";
import NewSubIssue from "@/Components/NewSubIssue.vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import SubIssueLine from "@/Components/SubIssueLine.vue";

const issue = inject("issue");
const showNewIssue = ref(false);
const savingSubIssue = ref(false);
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;

const newIssueForm = useForm({
    title: "",
});

const handleNewIssue = () => {
    if (newIssueForm.title.length < 1) {
        return;
    }

    savingSubIssue.value = true;
    axios
        .post(
            route("api.issue.sub-issue.store", { issue: issue.value.id }),
            newIssueForm.data()
        )
        .then((response) => {})
        .catch((error) => {
            console.log(error);
        })
        .finally(() => {
            savingSubIssue.value = false;
        });
};

onMounted(() => {
    emitter.on("stop-editing-issue", () => {
        if (showNewIssue.value) {
            handleNewIssue();
            showNewIssue.value = false;
        }
    });
});
</script>

<template>
    <div>
        <AppButton
            outline
            @click.stop="showNewIssue = true"
            v-show="!showNewIssue"
            >Add sub-issue</AppButton
        >

        <p
            class="text-sm text-zinc-500 mb-1"
            :class="{
                'mt-2': issue.sub_issues.length > 0,
            }"
            v-show="showNewIssue || issue.sub_issues.length > 0"
        >
            Sub-issues
        </p>
        <NewSubIssue
            v-model:title="newIssueForm.title"
            class="mb-2"
            v-show="showNewIssue"
            @click.stop
        />

        <SubIssueLine
            v-for="subIssue in issue.sub_issues"
            :key="subIssue.id"
            :subIssue="subIssue"
        />
    </div>
</template>
