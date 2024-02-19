<script setup>
import { getCurrentInstance, inject, ref, watch } from "vue";
import TextInput from "@/Components/TextInput.vue";

const issue = inject("issue");
const emitter = getCurrentInstance().appContext.config.globalProperties.emitter;
const editing = ref(false);
const saving = ref(false);
const title = ref(issue.value?.title);

const updateTitle = () => {
    if (
        !title.value ||
        title.value === issue.value.title ||
        title.value === ""
    ) {
        return;
    }

    const oldTitle = issue.value.title;
    saving.value = true;

    axios
        .put(route("api.issue.title.update", { issue: issue.value.id }), {
            title: title.value,
        })
        .then((response) => {
            emitter.emit("issue-updated", response.data.data);
            reset(title.value);
        })
        .catch((error) => {
            title.value = oldTitle;
        })
        .finally(() => {
            saving.value = false;
        });
};

const reset = (newTitle = issue.value.title) => {
    editing.value = false;
    title.value = newTitle;
};

watch(issue, (newIssue) => {
    title.value = newIssue ? newIssue.title : null;
});
</script>

<template>
    <div>
        <h1
            class="text-2xl font-medium"
            v-if="!editing"
            @click="editing = true"
        >
            {{ title }}
        </h1>
        <div
            class="-ml-3 -my-[calc(theme(spacing[1])+0.5px)] flex space-x-3"
            v-else
        >
            <TextInput
                :disabled="saving"
                class="font-semibold py-1 text-2xl flex-1"
                autofocus
                v-model="title"
                @keyup.enter="updateTitle"
                @blur="updateTitle"
            />
        </div>
    </div>
</template>
