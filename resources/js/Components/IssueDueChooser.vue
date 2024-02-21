<script setup>
import { IconCalendarTime } from "@tabler/icons-vue";
import { getCurrentInstance, inject, ref, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";

const issue = inject("issue");
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const editing = ref(false);
const loading = ref(false);

const dueDate = ref(issue.value.due_date);

const handleUpdate = (forceReset = false) => {
    if (dueDate.value === issue.value.due_date || forceReset) {
        editing.value = false;
        dueDate.value = issue.value.due_date;
        return;
    }
    loading.value = true;

    axios
        .put(route("api.issue.due-date.update", { issue: issue.value.id }), {
            due_date: dueDate.value,
        })
        .then((response) => {
            emitter.emit("issue-updated", response.data.data);
        })
        .finally(() => {
            loading.value = false;
            editing.value = false;
        });
};

watch(issue, (newIssue) => {
    dueDate.value = newIssue ? newIssue.due_date : null;
});
</script>

<template>
    <div class="grid grid-cols-3 text-sm items-center">
        <div class="col-start-1">
            <p class="text-zinc-500">Due date</p>
        </div>

        <div v-if="!editing" @click="editing = true">
            <div class="flex items-center space-x-2">
                <div class="p-2 rounded-full dark:bg-white/10" :class="{
                    'bg-zinc-100': !issue.due_date,
                    'bg-indigo-400': issue.due_date
                }">
                    <IconCalendarTime :class="{
                        'text-zinc-500': !issue.due_date,
                        'text-white': issue.due_date
                    }" size="16" />
                </div>
                <p class="dark:text-zinc-500">
                    {{ issue.due_date ?? "No due date" }}
                </p>
            </div>
        </div>
        <div v-else>
            <TextInput type="date" v-model="dueDate" autofocus :disabled="loading" @blur="handleUpdate()"
                @keyup.enter="handleUpdate()" @keydown.stop.esc="handleUpdate(true)" />
        </div>
    </div>
</template>
