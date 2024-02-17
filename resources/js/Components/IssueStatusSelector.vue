<script setup>
import { getCurrentInstance, inject, ref, watch } from "vue";
import IssueStatusIcon from "@/Components/IssueStatusIcon.vue";
import SelectInput from "@/Components/SelectInput.vue";

const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const issue = inject("issue");
const status = ref(1);
const savingStatus = ref(false);
const statuses = inject("statuses");
const statusOptions = statuses.map((status) => {
    return {
        label: status.formatted_name,
        value: status.id,
    };
});

const updateStatus = (newStatus) => {
    const oldStatus = issue.value.issue_status_id;
    savingStatus.value = true;

    axios
        .put(route("api.issue.status.update", { issue: issue.value.id }), {
            issue_status_id: newStatus,
        })
        .then((response) => {
            status.value = newStatus;
            emitter.emit("update-issue", issue.value.id);
        })
        .catch((error) => {
            status.value = oldStatus;
        })
        .finally(() => {
            savingStatus.value = false;
        });
};

watch(issue, (newIssue) => {
    status.value = newIssue ? newIssue.issue_status_id : 1;
});
</script>

<template>
    <div class="flex space-x-2 items-center">
        <IssueStatusIcon size-class="size-6" :status="issue.status" />

        <SelectInput
            :model-value="status"
            :options="statusOptions"
            :disabled="savingStatus"
            class="text-sm"
            @update:model-value="updateStatus"
        />
    </div>
</template>
